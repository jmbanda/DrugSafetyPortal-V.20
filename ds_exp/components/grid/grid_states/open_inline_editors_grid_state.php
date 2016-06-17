<?php

class OpenInlineEditorsGridState extends GridState
{
    private $nameSuffix;

    public function __construct(Grid $grid)
    {
        parent::__construct($grid);
        $this->nameSuffix = '_inline_' . mt_rand();
    }

    public function ProcessMessages()
    {
        $primaryKeyValues = $this->grid->GetPrimaryKeyValuesFromGet();

        $this->getDataset()->SetSingleRecordState($primaryKeyValues);
        $this->getDataset()->Open();

        if ($this->getDataset()->Next()) {
            $columns = $this->grid->GetViewColumns();
            foreach ($columns as $column) {
                $inlineEditColumn = $column->GetEditOperationColumn();
                if (isset($inlineEditColumn)) {
                    $editControl = $inlineEditColumn->GetEditControl();
                    $editControl->SetName($editControl->GetName() . $this->GetNameSuffix());
                }
            }
            array_walk($columns, create_function('$column', '$column->ProcessMessages();'));
        }
    }

    public function GetNameSuffix()
    {
        return $this->nameSuffix;
    }
}