<?php
namespace Vqhteam\Support\Social;
class youtube {
    public static function getAllVideos(string $channel_id): array
    {
        $url = "https://www.youtube.com/feeds/videos.xml?channel_id=$channel_id";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);
        if (preg_match('/Error 404/',$resp)){
            return [];
        }
        $rss = preg_replace('/media:/','',$resp);
        $rss = preg_replace('/yt:/','',$rss);
        $loadrss = simplexml_load_string($rss);
        $videos = [];
        $videos['channelTitle'] = (string)$loadrss->children()->author->name;
        $i=0;
        foreach ($loadrss->children() as $childen){
            if ($childen->getName() == 'entry'){
                $videos['videos'][$i]['videoId'] = (string)$childen->videoId;
                $videos['videos'][$i]['title'] = (string)$childen->title;
                $videos['videos'][$i]['link'] = (string)$childen->link->attributes()->href;
                $videos['videos'][$i]['thumbnail'] =  (string)$childen->group->thumbnail->attributes()->url;
                $videos['videos'][$i]['description'] = (string)$childen->group->description;
                $videos['videos'][$i]['rating'] = (string)$childen->group->community->starRating->attributes()->count;
                $videos['videos'][$i]['views'] = (string)$childen->group->community->statistics->attributes()->views;
                $pub = str_replace('T',' ',(string)$childen->published);
                $pub = explode('+',$pub)[0];
                $videos['videos'][$i]['published'] = $pub;
                $upt = str_replace('T',' ',(string)$childen->updated);
                $upt = explode('+',$upt)[0];
                $videos['videos'][$i]['updated'] = $upt;
                $i++;
            }
        }
       return $videos;
    }
}