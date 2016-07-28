<?php


namespace Me\Http;

require_once ab_path().'/vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php';

class Mobile extends \Mobile_Detect
{
    public static $self;

    public static function getInstance()
    {
        if (self::$self) return self::$self;
        return self::$self = new self();
    }
}