<?php

abstract class AbstractDatasetFieldViewColumn extends AbstractViewColumn
{
    /** @var string */
    private $fieldName;

    /** @var Dataset */
    private $dataset;

    /** @var bool */
    private $orderable;

    /** @var Dataset|null */
    private $lookupDataset;
    private $lookupHandlerName;

    #region Events
    public $BeforeColumnRender;

    private $bold = false;
    private $italic = false;
    private $align;
    private $customAttributes;
    private $inlineStyles;
    private $hrefTemplate;
    private $target = '_self';

    #endregion

    public function __construct($fieldName, $caption, $dataset, $orderable = true)
    {
        parent::__construct($caption);
        $this->BeforeColumnRender = new Event();
        $this->fieldName = $fieldName;
        $this->dataset = $dataset;
        $this->orderable = $orderable;
    }

    public function SetLookupDataset(Dataset $dataset)
    {
        $this->lookupDataset = $dataset;
    }

    public function GetLookupDataset()
    {
        return $this->lookupDataset;
    }

    public function GetLookupHandlerName()
    {
        return $this->lookupHandlerName;
    }

    public function RegisterLookupHTTPHandler($parentPageName, $idField, $valueField)
    {
        $this->lookupHandlerName = $parentPageName.'_'.$this->fieldName.'_search';
        GetApplication()->RegisterHTTPHandler(
            new DynamicSearchHandler(
                $this->lookupDataset,
                null,
                $this->lookupHandlerName,
                $idField,
                $valueField,
                null
            )
        );
    }

    public function SetOrderable($value)
    {
        $this->orderable = $value;
    }

    public function GetOrderable()
    {
        return $this->orderable;
    }

    protected function GetFieldName()
    {
        return $this->fieldName;
    }

    public function GetName()
    {
        return $this->fieldName;
    }

    /**
     * @return Dataset
     */
    public function GetDataset()
    {
        return $this->dataset;
    }

    public function GetValue()
    {
        return $this->GetDataset()->GetFieldValueByName($this->GetFieldName());
    }

    public function ShowOrderingControl()
    {
        if ($this->GetGrid() != null) {
            return $this->GetOrderable() && $this->GetGrid()->GetAllowOrdering();
        } else {
            return $this->GetOrderable();
        }
    }

    protected function CreateHeaderControl()
    {
        if ($this->ShowOrderingControl()) {
            $result = new HintedTextBox('HeaderControl', $this->GetCaption());
            $result->SetHint($this->GetDescription());

            return $result;
        } else {
            return parent::CreateHeaderControl();
        }
    }

    protected function GetActualKeys()
    {
        $keys = array(
            'Primary' => false,
            'Foreign' => false
        );

        if ($this->GetGrid()->GetShowKeyColumnsImagesInHeader()) {
            if ($this->dataset->IsFieldPrimaryKey($this->fieldName)) {
                $keys['Primary'] = true;
            }
            if ($this->dataset->IsLookupField($this->fieldName)) {
                $keys['Foreign'] = true;

                if ($this->dataset->IsLookupFieldNameByDisplayFieldName($this->fieldName)) {
                    if ($this->dataset->IsFieldPrimaryKey(
                        $this->dataset->IsLookupFieldNameByDisplayFieldName($this->fieldName)
                    )
                    ) {
                        $keys['Primary'] = true;
                    }
                }
            }
        }

        return $keys;
    }

    protected function getSortIndex()
    {
        return $this->GetGrid()->getSortIndexByFieldName($this->fieldName);
    }

    protected function getSortOrderType()
    {
        return $this->GetGrid()->getSortOrderTypeByFieldName($this->fieldName);
    }

    public function IsDataColumn()
    {
        return true;
    }

    public function setBold($bold)
    {
        $this->bold = $bold;
    }

    public function getBold()
    {
        return $this->bold;
    }

    public function setItalic($italic)
    {
        $this->italic = $italic;

        return $this;
    }

    public function getItalic()
    {
        return $this->italic;
    }

    public function setAlign($align)
    {
        $this->align = $align;

        return $this;
    }

    public function getAlign()
    {
        return $this->align;
    }

    public function setCustomAttributes($customAttributes)
    {
        $this->customAttributes = $customAttributes;

        return $this;
    }

    public function getCustomAttributes()
    {
        return $this->customAttributes;
    }

    public function setInlineStyles($inlineStyles) {
        $this->inlineStyles = $inlineStyles;
    }

    public function getInlineStyles() {
        return $this->inlineStyles;
    }

    public function setHrefTemplate($hrefTemplate)
    {
        $this->hrefTemplate = $hrefTemplate;
    }

    public function setTarget($target)
    {
        $this->target = $target;
    }

    public function getHrefTemplate()
    {
        return $this->hrefTemplate;
    }

    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param Renderer $renderer
     * @return void
     */
    public function Accept($renderer)
    {
        $renderer->RenderDatasetFieldViewColumn($this);
    }
}
