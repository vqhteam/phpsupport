<?php
namespace Vqhteam\Support\WEB;
use Vqhteam\Support\CURL\GET;
use Vqhteam\Support\WEB\Types\ipinfo;

class IP {
    public static function getIP():string{
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
    }
    public static function ipInfo(string $ip=null): ? ipinfo{
        $ip = empty($ip) ? self::getIP() : $ip;
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