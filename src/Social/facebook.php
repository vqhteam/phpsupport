<?php
namespace Vqhteam\Support\Social;
use Vqhteam\Support\CURL\POST;
use Vqhteam\Support\CURL\GET;
class facebook {
    public static function getID(string $url) : ?string{
        $url = urldecode($url);
        if (preg_match('/^(\d+)$/',$url)){
            return $url;
        }
        if (preg_match('/fb\.watch/',$url)){
            return self::getIDVideo($url);
        }
        if (preg_match('/videos\/(\d+)/',$url)){
            preg_match('/videos\/(\d+)/',$url,$output);
            if ($output[1]){
                return $output[1];
            }
        }
        if (preg_match('/story_fbid=(\d+)/',$url)){
            preg_match('/story_fbid=(\d+)/',$url,$output);
            if ($output[1]){
                return $output[1];
            }
        }
        if (preg_match('/posts\/(\d+)/',$url)){
            preg_match('/posts\/(\d+)/',$url,$output);
            if ($output[1]){
                return $output[1];
            }
        }
        if (preg_match('/groups\/(\d+)/',$url)){
            preg_match('/groups\/(\d+)/',$url,$output);
            if ($output[1]){
                return $output[1];
            }
        }
        if (preg_match('/fbid=(\d+)/',$url)){
            preg_match('/fbid=(\d+)/',$url,$output);
            if ($output[1]){
                return $output[1];
            }
        }
        $post = new POST();
        $post->setURL('https://findidfb.com/');
        $post->setDATA('url_facebook='.$url);
        $post->setHeaders(['user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.88 Safari/537.36',
            'origin: https://findidfb.com','referer: https://findidfb.com/','content-type: application/x-www-form-urlencoded']);
        if ($post->send()){
            $data = $post->getResponseMessage();
            if (preg_match('/Numeric ID/',$data)){
                preg_match('/Numeric ID\: <b>(\d+)<\/b>/',$data,$output);
                if (isset($output[1])){
                    return $output[1];
                }
            }
            return "can't find id";
        }
        return $post->getErrorMessage();
    }
    private static function getIDVideo(string $url): ?string
    {
        $get = new GET($url,self::headersFacebook(),[CURLOPT_HEADER=>true]);
        if ($get->send()){
            $data = $get->getResponseMessage();
            if (preg_match('/v=(\d+)/',$data)){
                preg_match('/v=(\d+)/',$data,$output);
                if (isset($output[1])){
                    return $output[1];
                }
            }
            return "can't find id";
        }
        return $get->getErrorMessage();
    }
    public static function getVideoDownloadLink(string $url_or_id){
        if (empty($url_or_id)){
            throw new \Exception('URL or ID cannot be empty');
        }
        if (!preg_match('/fb\.watch/',$url_or_id)) {
            $url_or_id = self::getID($url_or_id);
            if (empty($url_or_id)) {
                throw new \Exception('Invalid URL or ID');
            }
            if (!preg_match('/^(\d+)$/', $url_or_id)) {
                return $url_or_id;
            }
            $url_or_id = 'https://www.facebook.com/'.$url_or_id;
        }
        $arr = self::headersFacebook();
         array_push($arr,'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9');
        $get = new GET($url_or_id,
            $arr
            ,
            [CURLOPT_FOLLOWLOCATION=>true]);
        if ($get->send()){
            if (preg_match('/playable_url/',$get->getResponseMessage())){
                preg_match('/playable_url_quality_hd":"(.*?)"/',$get->getResponseMessage(),$hd);
                preg_match('/playable_url":"(.*?)"/',$get->getResponseMessage(),$sd);
                if (isset($sd[1])){
                    return ['sd'=>json_decode('"'.$sd[1].'"'),
                        'hd'=>json_decode('"'.$hd[1].'"')];
                }
            }
            return 'Video not found';
        }
        return $get->getErrorMessage();
    }
    private static function headersFacebook(): array {
        return ['user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.88 Safari/537.36',
            'upgrade-insecure-requests: 1','sec-fetch-user: ?1','sec-fetch-site: none',
            'sec-fetch-mode: navigate','sec-fetch-dest: document','sec-ch-ua-mobile: ?0','sec-ch-ua-platform: "Windows"',
            'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="100", "Google Chrome";v="100"',
            'accept-language: vi-VN,vi;q=0.9,en-US;q=0.8,en;q=0.7,smn-FI;q=0.6,smn;q=0.5,fr-FR;q=0.4,fr;q=0.3'];
    }
}