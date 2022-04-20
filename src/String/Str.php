<?php
namespace Vqhteam\Support\String;
class Str {
    public static function random(int $length=30):?string{
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
    }
    public static function randomInt(int $length=30):?string{
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public static function randomSpecialCharacters(int $length=30):?string{
        $characters = '\\|>./?,<\'";:{}[]_-+=()*&^%$#@!~`';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public static function emailValidation(string $email,string $option_email=null)
    {
        if (empty($email)){
            return false;
        }
        if (empty($option_email)){
            $option_email = '[a-zA-Z0-9]+\.[a-zA-Z0-9]{2,5}';
        }
        return preg_match('/^[a-zA-Z0-9_.]+@'.$option_email.'$/',$email);
    }
    public static function usernameValidation(string $username,int $min_length=0,int $max_length=0)
    {
        if (empty($username)){
            return false;
        }
        if ($min_length==0&&$max_length==0){
            $length='+';
        }else{
            $length = sprintf("{%d,%d}", $min_length, $max_length);
        }
        if (!preg_match('/^[a-zA-Z]+$/',substr($username,0,1))){
            return false;
        }
        return preg_match('/^[a-zA-Z0-9_]'.$length.'$/',$username);
    }
}