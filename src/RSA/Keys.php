<?php
namespace Vqhteam\Support\RSA;
class Keys {
    public static function checkPublicKey(string $public_key): bool
    {
        if (empty($public_key)){
            return false;
        }
        try {
            $check = openssl_get_publickey($public_key);
            if ($check){
                return true;
            }
        }catch (\Exception $e){
            return false;
        }
        return false;
    }
    public static function checkPrivateKey(string $private_key): bool
    {
        if (empty($private_key)){
            return false;
        }
        try {
            $check = openssl_get_privatekey($private_key);
            if ($check){
                return true;
            }
        }catch (\Exception $e){
            return false;
        }
        return false;
    }
}