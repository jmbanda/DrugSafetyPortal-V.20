<?php

class ModalEditOperation extends ModalRowOperation
{

    public function GetName()
    {
        return OPERATION_EDIT;
    }

    public function GetLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->handlerName);
        $result->AddParameter(ModalOperation::Param, ModalOperation::OpenModalEditDialog);
        $result->AddParameters($this->GetLinkParametersForPrimaryKey());

        return $result->GetLink();
    }

}