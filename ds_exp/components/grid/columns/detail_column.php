<?php

class DetailColumn extends AbstractViewColumn
{
    private $masterKeyFields;
    private $separatePageHandlerName;
    private $inlinePageHandlerName;
    private $dataset;
    private $name;
    private $frameRandomNumber;

    public function __construct(
        $masterKeyFields,
        $name,
        $separatePageHandlerName,
        $inlinePageHandlerName,
        Dataset $dataset,
        $caption
    ) {
        parent::__construct($caption);
        $this->masterKeyFields = $masterKeyFields;
        $this->name = $name;
        $this->separatePageHandlerName = $separatePageHandlerName;
        $this->inlinePageHandlerName = $inlinePageHandlerName;
        $this->dataset = $dataset;
        $this->frameRandomNumber = Random::GetIntRandom();
        $this->dataset->OnNextRecord->AddListener('NextRecordHandler', $this);
    }

    public function GetSeparatePageHandlerName()
    {
        return $this->separatePageHandlerName;
    }

    public function NextRecordHandler()
    {
        $this->frameRandomNumber = Random::GetIntRandom();
    }

    public function GetDataset()
    {
        return $this->dataset;
    }

    private function GetDetailsControlSuffix()
    {
        return $this->frameRandomNumber;
    }

    public function GetLink()
    {
        $linkBuilder = $this->GetGrid()->CreateLinkBuilder();
        $linkBuilder->AddParameter('detailrow', 'DetailContent_'.$this->name.'_'.$this->GetDetailsControlSuffix());
        $linkBuilder->AddParameter('hname', $this->inlinePageHandlerName);
        for ($i = 0; $i < count($this->masterKeyFields); $i++) {
            $linkBuilder->AddParameter('fk'.$i, $this->GetDataset()->GetFieldValueByName($this->masterKeyFields[$i]));
        }

        return $linkBuilder->GetLink();
    }


    public function DecorateLinkForPostMasterRecord(LinkBuilder $linkBuilder)
    {
        $linkBuilder->AddParameter('details-redirect', $this->separatePageHandlerName);
    }

    public function GetSeparateViewLink()
    {
        $linkBuilder = $this->GetGrid()->CreateLinkBuilder();
        $linkBuilder->AddParameter('hname', $this->separatePageHandlerName);
        for ($i = 0; $i < count($this->masterKeyFields); $i++) {
            $linkBuilder->AddParameter('fk'.$i, $this->GetDataset()->GetFieldValueByName($this->masterKeyFields[$i]));
        }

        return $linkBuilder->GetLink();
    }

    public function GetViewData()
    {
        $result = array(
            'caption' => $this->GetCaption(),
            'gridLink' => $this->GetLink(),
            'SeperatedPageLink' => $this->GetSeparateViewLink(),
            'detailId' => 'detail-'.$this->GetDetailsControlSuffix()
        );

        return $result;
    }

    public function GetValue() {
    }

    public function Accept($renderer)
    {
    }
}