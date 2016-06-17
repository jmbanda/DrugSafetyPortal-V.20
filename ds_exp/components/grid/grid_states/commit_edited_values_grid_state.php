<?php

class CommitEditedValuesGridState extends AbstractCommitValuesGridState
{
    private $isInline = false;

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

    protected function handleError($errorMessage)
    {
        $this->ChangeState(OPERATION_EDIT);
        parent::handleError($errorMessage);
    }

    protected function getRealEditColumns()
    {
        return $this->grid->GetEditColumns();
    }

    public function ProcessMessages()
    {
        $rowValues = $this->getSingleRowValues(METHOD_POST);
        $this->CheckRLSEditGrant();

        if (!$rowValues) {
            return;
        }

        $this->getDataset()->Edit();
        if ($this->doProcessMessages($rowValues)) {
            $this->redirectIfNeeded($this->getGridMessage());
        }
    }

    private function redirectIfNeeded($message)
    {
        $action = '';
        if (GetApplication()->GetSuperGlobals()->IsPostValueSet('submit1')) {
            $action = GetApplication()->GetSuperGlobals()->GetPostValue('submit1');
        }

        $redirect = null;
        $detailToRedirect = null;
        if (GetApplication()->GetSuperGlobals()->IsGetValueSet('details-redirect')) {
            $detailToRedirect = GetApplication()->GetSuperGlobals()->GetGetValue('details-redirect');
        }

        if ($detailToRedirect) {
            $detail = $this->grid->FindDetail($detailToRedirect);
            $redirect = $detail->GetSeparateViewLink();
        }

        $redirectUrl = null;

        if ($action === 'saveinsert') {

            $redirectUrl = $this->grid->GetAddRecordLink();

        } elseif ($action != 'saveedit' && !$this->isInline) {

            $redirectUrl = $redirect ? $redirect : $this->grid->getReturnUrl();

        } elseif ($action == 'saveedit' && !$this->isInline) {

            $newPrimaryKeyValues = $this->getDataset()->GetPrimaryKeyValuesAfterEdit();
            $redirectUrl = $this->grid->GetEditCurrentRecordLink($newPrimaryKeyValues);
            $this->getDataset()->Close();

        }

        if (!is_null($redirectUrl)) {
            $this->grid->setFlashMessage($message);
            header('Location: ' . $redirectUrl);
            exit;
        }
    }

    public function SetInternalStateSwitch($primaryKeys)
    {
        $this->grid->SetInternalStateSwitch($primaryKeys);
    }

    public function SetIsInlineOperation($value)
    {
        $this->isInline = $value;
    }
}
