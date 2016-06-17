<?php

class InlineEditOperation extends BaseRowOperation
{
    /**
     * @var string
     */
    private $cancelButtonText;

    /**
     * @var string
     */
    private $commitButtonText;

    /**
     * @var string
     */
    private $editButtonText;

    /**
     * @param string $caption
     * @param Dataset $dataset
     * @param string $editButtonText
     * @param string $cancelButtonText
     * @param string $commitButtonText
     */
    function __construct($caption, $dataset, $editButtonText, $cancelButtonText, $commitButtonText)
    {
        parent::__construct($caption, 'InlineEdit', $dataset);
        $this->editButtonText = $editButtonText;
        $this->cancelButtonText = $cancelButtonText;
        $this->commitButtonText = $commitButtonText;
    }

    public function GetValue()
    {
        $showButton = true;
        $this->OnShow->Fire(array(&$showButton));

        $result = false;

        if ($showButton) {
            $result = array(
                'type' => 'inline_edit',
                'useImage' => $this->isUseImage(),
                'editCaption' => $this->editButtonText,
                'commitCaption' => $this->commitButtonText,
                'cancelCaption' => $this->cancelButtonText,
                'keys' => array()
            );

            $keyValues = $this->getDataset()->GetPrimaryKeyValues();
            for ($i = 0; $i < count($keyValues); $i++) {
                $result['keys']['pk'.$i] = $keyValues[$i];
            }
        }

        return $result;
    }

    public function isEditOperation() {
        return true;
    }
}