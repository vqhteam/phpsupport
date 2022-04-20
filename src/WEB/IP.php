<?php
namespace Vqhteam\Support\WEB;
use Vqhteam\Support\CURL\GET;
use Vqhteam\Support\WEB\Types\ipinfo;

class IP {
    public static function ipInfo(string $ip=null): ?ipinfo{
        $get = new GET('http://www.geoplugin.net/json.gp?ip='.$ip);
        if ($get->send()){
            $json = json_decode($get->getResponseMessage());
            $info = new ipinfo
            (
                $json->geoplugin_request,
                $json->geoplugin_countryName,
                $json->geoplugin_countryCode,
                $json->geoplugin_timezone,
                $json->geoplugin_currencyCode,
                $json->geoplugin_currencySymbol_UTF8
            );
            return $info;
        }
       return null;
    }
}