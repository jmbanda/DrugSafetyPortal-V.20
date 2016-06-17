<?php

class OpenInlineInsertEditorsGridState extends GridState
{
    private $nameSuffix;

    public function __construct(Grid $grid)
    {
        parent::__construct($grid);
        $this->nameSuffix = '_inline_' . mt_rand();
    }

    public function ProcessMessages()
    {
        $columns = $this->grid->GetViewColumns();

        foreach ($columns as $column) {
            $inlineEditColumn = $column->GetInsertOperationColumn();
            if (isset($inlineEditColumn)) {
                $editControl = $inlineEditColumn->GetEditControl();
                $editControl->SetName($editControl->GetName() . $this->GetNameSuffix());
                $inlineEditColumn->ProcessMessages();
            }
        }
    }

    public function GetNameSuffix()
    {
        return $this->nameSuffix;
    }
}
