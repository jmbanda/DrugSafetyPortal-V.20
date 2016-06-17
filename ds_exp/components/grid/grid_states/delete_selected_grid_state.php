<?php

class DeleteSelectedGridState extends AbstractCommitValuesGridState
{
    protected function getOperation()
    {
        return 'Delete';
    }

    protected function commitValues($rowValues, $newRowValues)
    {
        $this->getDataset()->Delete();
    }

    protected function handleError($message)
    {
        $this->getDataset()->SetAllRecordsState();
        $this->ChangeState(OPERATION_VIEWALL);
        $this->setGridErrorMessage($message);
    }

    public function ProcessMessages()
    {
        $primaryKeys = $this->getPrimaryKeys();

        foreach ($primaryKeys as $primaryKeyValues) {
            $this->getDataset()->SetSingleRecordState($primaryKeyValues);
            $this->getDataset()->Open();

            if ($this->getDataset()->Next()) {
                $this->doProcessMessages($this->getDataset()->GetCurrentFieldValues());
            }

            $this->getDataset()->Close();
        }

        $this->ApplyState(OPERATION_VIEWALL);
    }

    private function getPrimaryKeys()
    {
        $primaryKeysArray = array();

        for ($i = 0; $i < GetApplication()->GetPOSTValue('recordCount'); $i++) {
            if (GetApplication()->IsPOSTValueSet('rec' . $i)) {
                // TODO : move GetPrimaryKeyFieldNames function to private
                $primaryKeys = array();
                $primaryKeyNames = $this->getDataset()->GetPrimaryKeyFieldNames();
                for ($j = 0; $j < count($primaryKeyNames); $j++)
                    $primaryKeys[] = GetApplication()->GetPOSTValue('rec' . $i . '_pk' . $j);
                $primaryKeysArray[] = $primaryKeys;
            }
        }

        $inlineInsertedRecordPrimaryKeyNames = GetApplication()->GetSuperGlobals()->GetPostVariablesIf(
            create_function('$str', 'return StringUtils::StartsWith($str, \'inline_inserted_rec_\') && !StringUtils::Contains($str, \'pk\');')
        );

        foreach (array_keys($inlineInsertedRecordPrimaryKeyNames) as $name) {
            $primaryKeys = array();
            $primaryKeyNames = $this->getDataset()->GetPrimaryKeyFieldNames();
            for ($i = 0; $i < count($primaryKeyNames); $i++) {
                $primaryKeys[] = GetApplication()->GetSuperGlobals()->GetPostValue($name . '_pk' . $i);
            }
            $primaryKeysArray[] = $primaryKeys;
        }

        return $primaryKeysArray;
    }
}
