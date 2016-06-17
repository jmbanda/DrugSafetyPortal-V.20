<?php

abstract class AbstractCommitValuesGridState extends GridState
{
    /**
     * @return string Insert|Update
     */
    abstract protected function getOperation();

    /**
     * @param array $rowValues
     *
     * @return array
     */
    protected function getNewRowValues($rowValues)
    {
        return $rowValues;
    }

    /**
     * @param array $rowValues
     *
     * @return array
     */
    protected function refreshRowValues($rowValues)
    {
        return $rowValues;
    }

    /**
     * @param array $rowValues
     * @param array $newRowValues
     *
     * @return null
     */
    protected function commitValues($rowValues, $newRowValues)
    {
        $this->WriteChangesToDataset($rowValues, $newRowValues, $this->getDataset());
        $this->GetDataset()->Post();
    }

    /**
     * @param array  &$rowValues
     * @param string &$message
     * @param int    &$messageDisplayTime
     *
     * @return bool
     */
    protected function isCanceledByEvent(&$rowValues, &$message, &$messageDisplayTime)
    {
        $cancel = false;

        $this->fireEvent('Before' . $this->getOperation() . 'Record', array(
            &$rowValues,
            &$cancel,
            &$message,
            &$messageDisplayTime,
            $this->getDatasetName(),
        ));

        return $cancel;
    }

    /**
     * @param string $errorMessage
     *
     * @return null
     */
    protected function handleError($errorMessage)
    {
        $this->setGridErrorMessage($errorMessage);

        foreach ($this->getRealEditColumns() as $column) {
            $column->PrepareEditorControl();
        }

        $this->getDataset()->Close();
    }

    /**
     * @return Exception[]
     */
    protected function processColumns()
    {
        $exceptions = array();

        $columns = $this->getRealEditColumns();
        foreach ($columns as $column) {
            try {
                $column->ProcessMessages();
            } catch (Exception $e) {
                $exceptions[] = $e;
            }

            try {
                $column->AfterSetAllDatasetValues();
            } catch (Exception $e) {
                $exceptions[] = $e;
            }
        }

        return $exceptions;
    }

    public function doProcessMessages($rowValues)
    {
        $exceptions = $this->processColumns();
        if (count($exceptions) > 0) {
            $this->handleError($this->getMessageFromExceptions($exceptions));
            return false;
        }

        $newRowValues = $this->getNewRowValues($rowValues);

        $messageDisplayTime = 0;
        $success = true;
        $message = '';

        if ($this->isCanceledByEvent($newRowValues, $message, $messageDisplayTime)) {
            $this->setGridMessageDisplayTime($messageDisplayTime);
            $this->handleError($message);
            return false;
        } else {
            $message = '';
        }

        try {
            $this->commitValues($rowValues, $newRowValues);
            $newRowValues = $this->refreshRowValues(array_merge($rowValues, $newRowValues));

        } catch (Exception $e) {
            $success = false;
            $message = $this->getMessageFromExceptions(array($e));
        }

        $this->fireEvent('After' . $this->getOperation() . 'Record', array(
            $newRowValues,
            $this->getDatasetName(),
            &$success,
            &$message,
            &$messageDisplayTime,
        ));

        $this->setGridMessageDisplayTime($messageDisplayTime);

        if (!$success) {
            $this->handleError($message);
            return false;
        }

        $this->setGridMessage($message);

        return true;
    }
}
