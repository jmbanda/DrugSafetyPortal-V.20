<?php

include_once dirname(__FILE__) . '/common_page_viewdata.php';
include_once dirname(__FILE__) . '/captions.php';

/**
 * Page with common view data
 */
abstract class CommonPage
{
    private $header;
    private $footer;
    private $title;
    private $contentEncoding;
    private $localizerCaptions;

    /**
     * CommonPage constructor.
     * @param $caption
     * @param $contentEncoding
     */
    public function __construct($title, $contentEncoding)
    {
        $this->title = $title;
        $this->contentEncoding = $contentEncoding;
    }

    /**
     * @return CommonPageViewData
     */
    public function getCommonViewData()
    {
        $viewData = new CommonPageViewData();

        return $viewData
            ->setDirection($this->GetPageDirection())
            ->setContentEncoding($this->GetContentEncoding())
            ->setTitle($this->GetTitle())
            ->setValidationScripts($this->GetValidationScripts())
            ->setHeader($this->GetHeader())
            ->setFooter($this->GetFooter())
            ->setCustomHead($this->GetCustomPageHeader())
            ->setClientSideScript(
                'OnBeforeLoadEvent',
                $this->GetCustomClientScript()
            )
            ->setClientSideScript(
                'OnAfterLoadEvent',
                $this->GetOnPageLoadedClientScript()
            );
    }

    /**
     * @return string
     */
    public function GetPageDirection()
    {
        return null;
    }

    /**
     * @return string
     */
    public function GetContentEncoding()
    {
        return $this->contentEncoding;
    }

    /**
     * @return string
     */
    public function GetCustomClientScript()
    {
        return '';
    }

    /**
     * @return string
     */
    public function GetOnPageLoadedClientScript()
    {
        return '';
    }

    /**
     * @return string
     */
    public function GetValidationScripts()
    {
       return '';
    }

    /**
     * @return string
     */
    public function GetCustomPageHeader()
    {
        return '';
    }

    public function GetTitle()
    {
        return $this->RenderText($this->title);
    }

    public function SetTitle($value)
    {
        $this->title = $value;
    }

    public function GetHeader()
    {
        return $this->RenderText($this->header);
    }

    public function SetHeader($value)
    {
        $this->header = $value;
    }

    public function GetFooter()
    {
        return $this->RenderText($this->footer);
    }

    public function SetFooter($value)
    {
        $this->footer = $value;
    }

    public function RenderText($text)
    {
        return ConvertTextToEncoding($text, GetAnsiEncoding(), $this->GetContentEncoding());
    }

    public function GetLocalizerCaptions()
    {
        if (!isset($this->localizerCaptions)) {
            $this->localizerCaptions = new Captions($this->GetContentEncoding());
        }

        return $this->localizerCaptions;
    }

    public abstract function GetPageFileName();

    public function GetPageName()
    {
        return basename($this->GetPageFileName(), '.php');
    }

}