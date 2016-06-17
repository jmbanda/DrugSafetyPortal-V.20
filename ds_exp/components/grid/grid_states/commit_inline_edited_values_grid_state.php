<?php

class CommitInlineEditedValuesGridState extends AbstractCommitValuesGridState
{
    protected function getOperation()
    {
        return 'Update';
    }

    protected function getNewRowValues($rowValues)
    {
        return array_merge(
            $rowValues,
            $this->getSingleRowValues(METHOD_POST)
        );
    }

    public function ProcessMessages()
    {
        $nameSuffix = ExtractInputValue('namesuffix', METHOD_POST);
        $columns = $this->grid->GetViewColumns();
        foreach ($columns as $column) {
            $inlineEditColumn = $column->GetEditOperationColumn();
            if (isset($inlineEditColumn)) {
                $editControl = $inlineEditColumn->GetEditControl();
                $editControl->SetName($editControl->GetName() . $nameSuffix);
            }
        }

        $this->CheckRLSEditGrant();
        $rowValues = $this->getSingleRowValues(METHOD_POST);

        if (!$rowValues) {
            return;
        }

        $this->getDataset()->Edit();
        $this->doProcessMessages($rowValues);

        $primaryKeyValues = $this->GetDataset()->GetPrimaryKeyValues();
        $this->getDataset()->Close();
        $this->getDataset()->SetSingleRecordState($primaryKeyValues);
    }

    /*
     * @return CustomEditColumn[]
     */
    protected function getRealEditColumns()
    {
        $result = array();

        foreach ($this->grid->GetViewColumns() as $column) {
            $editColumn = $column->GetEditOperationColumn();
            if (isset($editColumn)) {
                $result[] = $editColumn;
            }
        }
        return $result;
    }
}
