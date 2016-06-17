<?php

abstract class GridState
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var array
     */
    private $events;

    /**
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
        $this->events = array(
            'BeforeUpdateRecord' => $this->grid->BeforeUpdateRecord,
            'BeforeInsertRecord' => $this->grid->BeforeInsertRecord,
            'BeforeDeleteRecord' => $this->grid->BeforeDeleteRecord,
            'AfterUpdateRecord' => $this->grid->AfterUpdateRecord,
            'AfterInsertRecord' => $this->grid->AfterInsertRecord,
            'AfterDeleteRecord' => $this->grid->AfterDeleteRecord,
        );

        $this->getDataset()->OnBeforePost
            ->AddListener('OnBeforePostHandler', $this);
    }

    protected function fireEvent($eventName, $args)
    {
        array_unshift($args, $this->grid->GetPage());
        $this->events[$eventName]->Fire($args);
    }

    protected function getDatasetName() {
        return $this->getDataset()->getName();
    }

    protected function getSingleRowValues($method) {
        $primaryKeyValues = array();
        ExtractPrimaryKeyValues($primaryKeyValues, $method);
        $this->getDataset()->SetSingleRecordState($primaryKeyValues);
        $this->getDataset()->Open();

        if (!$this->getDataset()->Next()) {
            return null;
        }

        return $this->getDataset()->GetCurrentFieldValues();
    }

    protected function getDataset() {
        return $this->grid->GetDataset();
    }

    protected function setGridMessage($message)
    {
        $this->grid->SetGridMessage($message);
    }

    protected function getGridMessage()
    {
        return $this->grid->GetGridMessage();
    }

    protected function setGridMessageDisplayTime($messageDisplayTime)
    {
        return $this->grid->setGridMessageDisplayTime($messageDisplayTime);
    }

    protected function setGridErrorMessage($message) {
        $this->grid->SetErrorMessage($message);
    }

    protected function getMessageFromExceptions($exceptions)
    {
        $result = array();

        foreach ($exceptions as $exception) {
            if ($exception instanceOf SMException) {
                $message = $exception->getLocalizedMessage(
                    $this->grid->GetPage()->GetLocalizerCaptions()
                );
            } else {
                $message = $exception->getMessage();
            }

            $result[] = $message;

            if (defined('DEBUG_LEVEL') && DEBUG_LEVEL > 0) {
                $result[] = 'Program trace: <br>' . FormatExceptionTrace($exception);
            }
        }

        return implode('<br><br>', $result);
    }

    // --------------------------


    protected function ChangeState($stateIdentifier) {
        GetApplication()->SetOperation($stateIdentifier);
        $this->grid->SetState($stateIdentifier);
    }

    protected function ApplyState($stateIdentifier) {
        $this->ChangeState($stateIdentifier);
        $this->grid->GetState()->ProcessMessages();
    }

    /**
     * @param array $oldValues associative array (fieldNames => fieldValues) of old values
     * @param array $newValues associative array (fieldNames => fieldValues) of new values
     * @param IDataset|Dataset $dataset dataset where changes between old and new values must be written
     */
    protected function WriteChangesToDataset($oldValues, $newValues, Dataset $dataset) {
        foreach ($newValues as $fieldName => $fieldValue)
            if ($dataset->DoNotRewriteUnchangedValues()) {
                if (!isset($oldValues[$fieldName]) || ($oldValues[$fieldName] != $fieldValue))
                    $dataset->SetFieldValueByName($fieldName, $fieldValue);
            } else {
                $dataset->SetFieldValueByName($fieldName, $fieldValue);
            }
    }

    /**
     * @return CustomEditColumn[]
     */
    protected function getRealEditColumns() {
        return array();
    }

    public function OnBeforePostHandler(Dataset $dataset) {
        foreach ($this->getRealEditColumns() as $column) {
            $fieldName = $column->GetFieldName();
            if ($dataset->GetFieldByName($fieldName)) {
                if ($column->getUseHTMLFilter()) {
                    GetApplication()->getHTMLFilter()->setTags($column->getHTMLFilterString());
                    $dataset->SetFieldValueByName($fieldName,
                        GetApplication()->getHTMLFilter()->filter($dataset->GetFieldValueByName($fieldName)));
                }
            }
        }
    }

    public abstract function ProcessMessages();

    // exits when doesn't have edit permission
    protected function CheckRLSEditGrant()
    {
        $page = $this->grid->getPage();
        if ($page->GetRecordPermission() != null) {
            $page->RaiseSecurityError(
                !$page->GetRecordPermission()->HasEditGrant($this->GetDataset()), OPERATION_EDIT);
        }
    }

    function GetNameSuffix() { return null; }
}
