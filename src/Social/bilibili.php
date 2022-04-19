<?php
namespace Vqhteam\Support\Social;
class bilibili {
    public static function getAllVideos(string $id_channel,int $limit=999999): array{
        $current_time = time();
        $url = "https://api.bilibili.tv/intl/gateway/web/v2/user/archives?csrf=&mid=$id_channel&platform=web&pn=0&ps=$limit&s_locale=vi_VN";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($resp,true);
        if ($json['data']['total'] == 0){
            return ['total'=>0,'videos'=>[],'timeout'=>(time()-$current_time)];
        }
        $videos = [];
        $i=0;
        foreach ($json['data']['cards'] as $video){
            $videos[$i]['title'] = $video['title'];
            $videos[$i]['thumbnail'] = $video['cover'];
            $videos[$i]['video_id'] = $video['aid'];
            $videos[$i]['view'] = trim(explode(' ',$video['view'])[0]);
            $videos[$i]['duration'] = $video['duration'];
            $i++;
        }
        $videos['timeout'] = (time()-$current_time);
        return $videos;
    }

    public static function getVideoDownloadLinks(string $video_id)
    {
        $url = "https://api.bilibili.tv/intl/gateway/web/playurl?aid=$video_id&csrf=&device=wap&platform=web&qn=64&s_locale=vi_VN&tf=0&type=0";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($resp,true);
        if ($json['data'] == null){
            return [];
        }
        $videos = [];
        $i=0;
        foreach ($json['data']['playurl']['video'] as $video)
        {
            $videos[$i]['video_link'] = $video['video_resource']['url'];
            $videos[$i]['type'] = $video['video_resource']['mime_type'];
            $videos[$i]['size'] = $video['video_resource']['size'];
            $videos[$i]['quality'] = $video['stream_info']['desc_words'];
            $audio_q = $video['audio_quality'];
            foreach ($json['data']['playurl']['audio_resource'] as $audio){
                if ($audio['quality'] == $audio_q){
                    $videos[$i]['audio_link'] = $audio['url'];
                    $videos[$i]['audio_size'] = $audio['size'];
                    $videos[$i]['audio_type'] = $audio['mime_type'];
                    break;
                }
            }
            $i++;
        }
        return $videos;
    }
}