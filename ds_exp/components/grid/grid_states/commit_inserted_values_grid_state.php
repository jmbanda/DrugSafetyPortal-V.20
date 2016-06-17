<?php

class CommitInsertedValuesGridState extends AbstractCommitValuesGridState
{
    private $isInline = false;

    protected function getOperation()
    {
        return 'Insert';
    }

    protected function handleError($message)
    {
        $this->ChangeState(OPERATION_INSERT);
        parent::handleError($message);
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
        $this->getDataset()->Insert();

        if ($this->doProcessMessages(array())) {
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

        $redirectUrl = $redirect ? $redirect : null;

        if (!$this->isInline && is_null($redirectUrl)) {
            if ($action == 'saveinsert') {
                $this->ApplyState(OPERATION_INSERT);
                $redirectUrl = $this->grid->GetAddRecordLink();
            } else if ($action == 'saveedit') {
                $primaryKeyValues = $this->getDataset()->getPrimaryKeyValues();
                $redirectUrl = $this->grid->GetEditCurrentRecordLink($primaryKeyValues);
            } else {
                $redirectUrl = $this->grid->GetReturnUrl();
            }
        }

        if (!is_null($redirectUrl)) {
            $this->grid->setFlashMessage($message);
            header('Location: ' . $redirectUrl);
            exit;
        }
    }

    protected function getRealEditColumns()
    {
        return $this->grid->GetInsertColumns();
    }

    public function SetIsInlineOperation($value)
    {
        $this->isInline = $value;
    }

    public function SetInternalStateSwitch($primaryKeys)
    {
        $this->grid->SetInternalStateSwitch($primaryKeys);
    }
}
