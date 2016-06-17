<?php

ob_start();

include_once(dirname(__FILE__) . "/languages/default_lang.php");

if (file_exists(dirname(__FILE__) . "/languages/lang.php")) {
    include_once(dirname(__FILE__) . "/languages/lang.php");
}

$lang = getCustomLanguage();

if ($lang && file_exists(dirname(__FILE__) . "/languages/lang.$lang.php")) {
    include_once(dirname(__FILE__) . "/languages/lang.$lang.php");
}

ob_end_clean();

include_once dirname(__FILE__) . '/' . '../phpgen_settings.php';
include_once dirname(__FILE__) . '/' . 'utils/string_utils.php';

class Captions
{
    private $pageEncoding;
    
    public function __construct($pageEncoding)
    { 
        if ($pageEncoding == null || $pageEncoding == '')
            $this->pageEncoding = GetAnsiEncoding();
        else
        $this->pageEncoding = $pageEncoding;
    }

    public function RenderText($text) {
        return ConvertTextToEncoding($text, GetAnsiEncoding(), $this->GetEncoding());
    }

    public function GetEncoding() { return $this->pageEncoding; }
    
    private function GetCaptionByName($name)
    {
        $result = eval('global $c'.$name.'; return $c'.$name.';');
        return StringUtils::ConvertTextToEncoding($result, 'UTF-8', $this->pageEncoding);
    }

    public function GetMessageString($name) { return $this->GetCaptionByName($name); }
}

$captions = new Captions('UTF-8');
$captionsMap = array($captions->GetEncoding() => $captions);

function getCustomLanguage() {
    $result = '';
    if (isset($_GET['resetlang'])) {
        $_COOKIE['lang'] = '';
        setcookie("lang", '', time() - 3600);
    } else {
        if (isset($_GET['lang'])) {
            $result = substr($_GET['lang'], 0, 2);
            $_COOKIE['lang'] = $result;
            setcookie("lang", $result, time() + 3600);
        } elseif (isset($_COOKIE['lang'])) {
            $result = substr($_COOKIE['lang'], 0, 2);
        }
    }
    return $result;
}

/**
 * @param string $encoding
 * @return Captions
 */
function GetCaptions($encoding = null)
{
    if ($encoding == null || $encoding == '')
    {
        return GetCaptions(GetAnsiEncoding());
    }
    else 
    {
        global $captionsMap;
        if (!array_key_exists($encoding, $captionsMap))
            $captionsMap[$encoding] = new Captions($encoding);
        return $captionsMap[$encoding];
    }
}
