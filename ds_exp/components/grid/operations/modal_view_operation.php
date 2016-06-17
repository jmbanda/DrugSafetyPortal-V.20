<?php

class ModalViewOperation extends ModalRowOperation
{

    public function GetName()
    {
        return OPERATION_VIEW;
    }

    public function GetLink()
    {
        $result = $this->GetGrid()->CreateLinkBuilder();
        $result->AddParameter(OPERATION_HTTPHANDLER_NAME_PARAMNAME, $this->handlerName);
        $result->AddParameters($this->GetLinkParametersForPrimaryKey());

        return $result->GetLink();
    }

}