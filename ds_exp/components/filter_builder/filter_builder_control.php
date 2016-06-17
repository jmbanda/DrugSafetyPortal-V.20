<?php

class FilterBuilderControl {
    /** @var \Grid */
    private $parentGrid;

    /** @var \Filter */
    private $filter;

    /** @var array[] */
    private $fields;

    /** @var \Captions */
    private $captions;

    /**
     * @param Grid     $grid
     * @param Captions $captions
     */
    public function __construct(Grid $grid, Captions $captions) {
        $this->captions = $captions;
        $this->superGlobals = GetApplication()->GetSuperGlobals();
        $this->parentGrid = $grid;
        $this->filter = new Filter($grid->GetPage()->GetContentEncoding());
        $this->generator = $this->createSQLGenerator();
        $this->fields = array();
    }

    private function createSQLGenerator() {
        $commandImp = $this->parentGrid->GetDataset()->GetConnectionFactory()->CreateEngCommandImp();
        $this->parentGrid->GetDataset()->Connect();
        if ($this->parentGrid->GetDataset()->GetConnection()) {
            $commandImp->SetServerVersion($this->parentGrid->GetDataset()->GetConnection()->GetServerVersion());
        }
        return new GenericFilterSQLGenerator($commandImp);
    }

    private function GetStorageProperty() {
        return 'filter_json';
    }

    public final function GetActiveFilterAsJson() {
        return $this->filter->AsJson();
    }

    public final function GetActiveFilterAsString() {
        return $this->filter->AsString($this->captions);
    }

    /**
     * @param            $searchColumn
     * @param string     $name
     * @param string     $caption
     * @param int        $fieldType
     * @param string     $editorClass
     * @param null|array $editorOptions
     */
    public final function AddField($searchColumn, $name, $caption, $fieldType, $editorClass, $editorOptions) {
        $this->generator->AddField($name, $fieldType);
        $this->fields[$name] = array(
            'searchColumn' => $searchColumn,
            'name' => $name,
            'caption' => $caption,
            'type' => $fieldType,
            'editorClass' => $editorClass,
            'editorOptions' => $editorOptions
        );
    }

    private function FieldTypeToJSFieldType($fieldType) {
        switch ($fieldType) {
            case FieldType::Number:
                return 'Integer';
            case FieldType::String:
                return 'String';
            case FieldType::Blob:
                return 'Blob';
            case FieldType::DateTime:
                return 'DateTime';
            case FieldType::Date:
                return 'Date';
            case FieldType::Time:
                return 'Time';
            case FieldType::Boolean:
                return 'Boolean';

        }

        return 'String';
    }

    private function GetEditorClassByType($fieldType) {
        switch ($fieldType) {
            case FieldType::Number:
                return 'TextEdit';
            case FieldType::String:
                return 'TextEdit';
            case FieldType::Blob:
                return 'TextEdit';
            case FieldType::DateTime:
                return 'DateTimeEdit';
            case FieldType::Date:
                return 'DateEdit';
            case FieldType::Time:
                return 'TextEdit';
            case FieldType::Boolean:
                return 'BooleanEdit';

        }

        return 'TextEdit';
    }

    public final function GetViewData() {
        $result = array();

        $fieldData = array();
        foreach ($this->fields as $name => $data) {
            $fieldData[] = array(
                'Name' => $name,
                'Caption' => $data['caption'],
                'Type' => $this->FieldTypeToJSFieldType($data['type']),
                'EditorClass' =>
                    $data['editorClass'] ?
                        $data['editorClass'] :
                        $this->GetEditorClassByType($data['type']),
                'EditorOptions' => $data['editorOptions'] ?
                    SystemUtils::ToJSON($data['editorOptions']) : '{}',
            );
        }

        $result['Fields'] = $fieldData;
        $result['IsEnabled'] = $this->isEnabled();

        return $result;
    }

    public final function ProcessMessages() {
        $storageProperty = $this->GetStorageProperty();
        if ($this->superGlobals->IsPostValueSet($storageProperty) || $this->superGlobals->IsSessionVariableSet(
                $this->parentGrid->GetId().$storageProperty
            )
        ) {

            if ($this->superGlobals->IsPostValueSet($storageProperty)) {
                $filterJson = $this->superGlobals->GetPostValue($storageProperty);
            } else {
                $filterJson = $this->superGlobals->GetSessionVariable($this->parentGrid->GetId().$storageProperty);
            }

            $this->filter->LoadFromJson($filterJson);

            $this->updateEnabled();
            if (!$this->filter->IsEmpty() && $this->isEnabled()) {
                $this->parentGrid->GetDataset()->AddCustomCondition(
                    $this->generator->Generate(
                        $this->parentGrid->GetDataset(),
                        $this->filter->GetRoot()
                    )
                );
            }
            $this->superGlobals->SetSessionVariable($this->parentGrid->GetId().$storageProperty, $filterJson);
        }

        return $this->superGlobals->IsPostValueSet($storageProperty);
    }

    private function updateEnabled() {
        $key = $this->GetStorageProperty() . '_enabled';
        $sessionKey = $this->parentGrid->GetId() . $key;
        if ($this->superGlobals->IsPostValueSet($key)) {
            $value = $this->superGlobals->GetPostValue($key);
            $this->superGlobals->SetSessionVariable($sessionKey, $value);
        }
    }

    public function isEnabled() {
        $sessionKey = $this->parentGrid->GetId() . $this->GetStorageProperty() . '_enabled';

        if (!$this->superGlobals->IsSessionVariableSet($sessionKey)) {
            return true;
        }

        return $this->superGlobals->GetSessionVariable($sessionKey);
    }

    public function IsEmpty() {
        return $this->filter->IsEmpty();
    }
}