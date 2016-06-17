<?php

class ViewAllGridState extends GridState
{
    public function ProcessMessages()
    {
        $this->getDataset()->setOrderByFields($this->grid->getSortedColumns());

        foreach ($this->grid->GetViewColumns() as $column) {
            $column->ProcessMessages();
        }
    }
}
