<?php

class ModalCopyOperation extends ModalRowOperation
{

    public function GetName()
    {
        return OPERATION_COPY;
    }

    public function GetLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->handlerName);
        $result->AddParameter(ModalOperation::Param, ModalOperation::OpenModalCopyDialog);
        $result->AddParameters($this->GetLinkParametersForPrimaryKey());

        return $result->GetLink();
    }

}