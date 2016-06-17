<?php

abstract class ModalRowOperation extends BaseRowOperation
{
    /**
     * @var string
     */
    protected $handlerName;

    /**
     * @var string
     */
    protected $dialogTitle;

    /**
     * @param string $caption
     * @param Dataset $dataset
     * @param string $dialogTitle
     * @param string $handlerName
     */
    function __construct($caption, $dialogTitle, $dataset, $handlerName, $grid = null)
    {
        parent::__construct($caption, $handlerName, $dataset, $grid);
        $this->dialogTitle = $dialogTitle;
        $this->handlerName = $handlerName;
    }

    public function GetValue()
    {
        $showButton = true;
        $this->OnShow->Fire(array(&$showButton));

        $result = false;

        if ($showButton) {
            $result = array(
                'type' => 'modal',
                'name' => $this->GetName(),
                'useImage' => $this->isUseImage(),
                'link' => HtmlUtils::EscapeUrl($this->GetLink()),
                'caption' => $this->GetCaption(),
                'dialogTitle' => htmlspecialchars($this->dialogTitle)
            );
        }

        return $result;

    }
}