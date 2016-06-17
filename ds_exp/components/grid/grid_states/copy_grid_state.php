<?php

class CopyGridState extends GridState
{
    public function ProcessMessages()
    {
        $this->grid->GetPage()->UpdateValuesFromUrl();
        $primaryKeyValues = $this->grid->GetPrimaryKeyValuesFromGet();
        $this->getDataset()->SetSingleRecordState($primaryKeyValues);
        $this->getDataset()->Open();

        if ($this->getDataset()->Next()) {
            foreach ($this->grid->GetInsertColumns() as $column) {
                $column->ProcessMessages();
            }
        }
    }
}
