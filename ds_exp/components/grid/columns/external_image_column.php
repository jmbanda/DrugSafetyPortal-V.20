<?php

class ExternalImageColumn extends AbstractWrappedDatasetFieldViewColumn
{
    private $hintTemplate;
    private $sourcePrefix;
    private $sourceSuffix;

    public function __construct($fieldName, $caption, $dataset, $hintTemplate)
    {
        parent::__construct($fieldName, $caption, $dataset);
        $this->hintTemplate = $hintTemplate;
        $this->sourcePrefix = '';
        $this->sourceSuffix = '';
    }

    public function GetHintTemplate()
    {
        return $this->hintTemplate;
    }

    public function Accept($renderer)
    {
        $renderer->RenderExternalImageViewColumn($this);
    }
}