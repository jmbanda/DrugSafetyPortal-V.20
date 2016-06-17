<?php

class Filter {

    private $root;
    private $contentEncoding;

    public function __construct($contentEncoding) {
        $this->contentEncoding = $contentEncoding;
        $this->root = new FilterGroup();
    }

    /**
     * @param string $json
     * @return void
     */
    public function LoadFromJson($json) {
        $this->root->LoadFromData(SystemUtils::FromJSON(StringUtils::ConvertTextToEncoding($json, $this->contentEncoding, 'UTF-8')), $this->contentEncoding);
    }

    /**
     * @return FilterGroup
     */
    public function GetRoot() {
        return $this->root;
    }

    public function IsEmpty() {
        return $this->GetRoot()->IsEmpty();
    }

    public function AsJson() {
        return SystemUtils::ToJSON($this->GetRoot()->SaveToArray($this->contentEncoding));
    }

    public function AsString(Captions $captions) {
        return $this->GetRoot()->AsString($captions);
    }
}