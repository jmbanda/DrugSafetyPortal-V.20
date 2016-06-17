<?php

include_once dirname(__FILE__) . '/array_wrapper.php';

class CookieManager
{
    /**
     * @var ArrayWrapper
     */
    private $cookieWrapper;

    public function __construct(ArrayWrapper $cookieWrapper = null)
    {
        $this->cookieWrapper = is_null($cookieWrapper)
            ? ArrayWrapper::createCookiesWrapper()
            : $cookieWrapper;
    }

    public function getValue($paramName)
    {
        return $this->cookieWrapper->getValue($paramName);
    }

    public function isValueSet($paramName)
    {
        return $this->cookieWrapper->isValueSet($paramName);
    }

    public function setValue($paramName, $value, $lifetime = 0)
    {
        $this->cookieWrapper->setValue($paramName, $value);
        setcookie($paramName, $value, time() + $lifetime);
    }

    public function unsetValue($paramName)
    {
        $this->cookieWrapper->unsetValue($paramName);
        setcookie($paramName, '', time() - 3600);
    }
}
