<?php
namespace Vqhteam\Support\RSA;
use Vqhteam\Support\RSA\Keys;
use Vqhteam\Support\RSA\NewKeys;
class Helper {
    private static ?string $public_key = null;
    private static ?string $private_key = null;
    public static function getPublicKey(string $key_null_set_to=null) : string
    {
        if (empty(self::$public_key) && !empty($key_null_set_to)){
            self::$public_key = $key_null_set_to;
        }
        return self::$public_key;
    }
    public static function getPrivateKey(string $key_null_set_to=null) : string
    {
        if (empty(self::$private_key) && !empty($key_null_set_to)){
            self::$private_key = $key_null_set_to;
        }
        return self::$private_key;
    }
    public static function setPublicKey(string $public_key):void {
        if (empty($public_key)){
            throw new \Exception('public key cannot be left blank');
        }
        self::$public_key = $public_key;
    }
    public static function setPrivateKey(string $private_key):void {
        if (empty($private_key)){
            throw new \Exception('private key cannot be left blank');
        }
        self::$private_key = $private_key;
    }
    public static function newKey(int $bist=4096,bool $change_current_keys=true) : NewKeys
    {
        $new = new NewKeys($bist);
        if ($change_current_keys){
            self::$public_key = $new->getPublicKey();
            self::$private_key = $new->getPrivateKey();
        }
        return $new;
    }
    public static function encrypt($data_to_encrypt,bool $to_base64=false) {
        if (empty(self::$public_key)){
            throw new \Exception('Public Key required');
        }
        $key = openssl_get_publickey(self::$public_key);
        if (!$key){
            throw new \Exception('Invalid Public Key');
        }
        openssl_public_encrypt($data_to_encrypt,$output,$key);
        if (!$output || empty($output)){
            throw new \Exception(openssl_error_string());
        }
        $output = $to_base64 ? base64_encode($output) : $output;
        return $output;
    }
    public static function decrypt($data_encrypted,bool $from_base64=false) {
        if (empty(self::$private_key)){
            throw new \Exception('Private Key required');
        }
        $key = openssl_get_privatekey(self::$private_key);
        if (!$key){
            throw new \Exception('Private Public Key');
        }
        openssl_private_decrypt($from_base64 ? base64_decode($data_encrypted) : $data_encrypted,$output,$key);
        if (!$output || empty($output)){
            throw new \Exception(openssl_error_string());
        }
        return $output;
    }
}