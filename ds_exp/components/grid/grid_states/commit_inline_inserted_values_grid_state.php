<?php

class CommitInlineInsertedValuesGridState extends AbstractCommitValuesGridState
{
    protected function getOperation()
    {
        return 'Insert';
    }

    protected function getNewRowValues($rowValues)
    {
        return $this->GetDataset()->GetCurrentFieldValues();
    }

    protected function refreshRowValues($rowValues)
    {
        return array_merge(
            $rowValues,
            $this->getDataset()->getInsertFieldValues()
        );
    }

    public function ProcessMessages()
    {
        $nameSuffix = ExtractInputValue('namesuffix', METHOD_POST);
        $columns = $this->grid->GetViewColumns();
        foreach ($columns as $column) {
            $inlineEditColumn = $column->GetInsertOperationColumn();
            if (isset($inlineEditColumn)) {
                $editControl = $inlineEditColumn->GetEditControl();
                $editControl->SetName($editControl->GetName() . $nameSuffix);
            }
        }

        $this->getDataset()->Insert();

        $this->doProcessMessages(array());

        $primaryKeyValues = $this->GetDataset()->GetPrimaryKeyValues();
        $this->GetDataset()->SetSingleRecordState($primaryKeyValues);
        $this->GetDataset()->Open();
        $this->GetDataset()->Next();
    }

    protected function getRealEditColumns()
    {
        $result = array();

        foreach ($this->grid->GetViewColumns() as $column) {
            $editColumn = $column->GetInsertOperationColumn();
            if (isset($editColumn)) {
                $result[] = $editColumn;
            }
        }
        return $result;
    }
}
