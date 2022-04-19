<?php
namespace Vqhteam\Support\WEB\Types;
class ipinfo {
    private ?string $ip=null;
    private ?string $country_name=null;
    private ?string $country_code=null;
    private ?string $timezone=null;
    private ?string $currencyCode=null;
    private ?string $currencySymbol=null;
    function __construct($ip,$country_name,$country_code,$timezone,$currencyCode,$currencySymbol)
    {
        $this->ip = $ip;
        $this->country_name = $country_name;
        $this->country_code = $country_code;
        $this->timezone = $timezone;
        $this->currencyCode = $currencyCode;
        $this->currencySymbol = $currencySymbol;
    }
    public function getIP():string {
        return $this->ip;
    }
    public function getCountryName():string {
        return $this->country_name;
    }
    public function getCountryCode():string {
        return $this->country_code;
    }
    public function getTimeZone():string {
        return $this->timezone;
    }
    public function getCurrencyCode():string {
        return $this->currencyCode;
    }
    public function getCurrencySymbol():string {
        return $this->currencySymbol;
    }
    public function get() : object {
        return (object)['ip'=>$this->ip,'country_name'=>$this->country_name,
            'country_code'=>$this->country_code,'timezone'=>$this->timezone,
            'currencyCode'=>$this->currencyCode,'currencySymbol'=>$this->currencySymbol];
    }
}