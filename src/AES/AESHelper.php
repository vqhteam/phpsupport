<?php
namespace Vqhteam\Support\AES;
use Vqhteam\Support\AES\aes;
use Vqhteam\Support\String\Str;
class AESHelper {
 public static function encrypt(string $data,string $password=null,int $random_password_length=30):aes{
     if (empty($password)){
         $password = Str::random($random_password_length);
     }
     $method = "AES-256-CBC";
     $salt = openssl_random_pseudo_bytes(256);
     $iterations = 999;
     $key = hash_pbkdf2("sha512", $password, $salt, $iterations, 64);
     $iv = openssl_random_pseudo_bytes(16);
     $encrypted_data = openssl_encrypt($data, $method,
         hex2bin($key), OPENSSL_RAW_DATA, $iv);
     return new aes(bin2hex($iv),bin2hex($salt),$encrypted_data,$password);
 }
 public static function decrypt(string $encrypted_data_string,string $password):string{
        if (empty($encrypted_data_string) || empty($password)){
            throw new \Exception('Encrypted data and password cannot be empty');
        }
        try {
            $bs = base64_decode($encrypted_data_string);
            $js = json_decode($bs);
            $iv = hex2bin($js->iv);
            $salt = hex2bin($js->s);
            $message = base64_decode($js->ct);
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
        $method = "AES-256-CBC";
        $iterations = 999;
        $key = hash_pbkdf2("sha512", $password, $salt, $iterations, 64);
        $decrypted = openssl_decrypt($message, $method,
            hex2bin($key), OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }
}