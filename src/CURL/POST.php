<?php
namespace Vqhteam\Support\CURL;
class POST {
    private ?string $url;
    private ?string $data;
    private array $header;
    private array $CURL_OPT;
    private ?string $error_message=null;
    private ?string $success_message=null;
    function __construct(string $url=null,
                         string $data=null,
                         array $header=["Content-Type: application/x-www-form-urlencoded"],
                         array $CURL_OPT=[]){
            if (!empty($url)){
                if (!filter_var($url, FILTER_VALIDATE_URL)){
                    throw new \Exception($url.' is not a valid URL');
                }
            }
            $this->url = $url;
            $this->data = $data;
            $this->header = $header;
            $this->CURL_OPT = $CURL_OPT;
    }
    public function getURL() : ?string
    {
        return $this->url;
    }
    public function setURL(string $url) : void
    {
        if (!empty($url)){
            if (!filter_var($url, FILTER_VALIDATE_URL)){
                throw new \Exception($url.' is not a valid URL');
            }
        }else{
            throw new \Exception('URL is empty');
        }
        $this->url = $url;
    }
    public function getDATA() : ?string
    {
        return $this->data;
    }
    public function setDATA(string $data) : void
    {
    if (empty($data)){
            throw new \Exception('DATA is empty');
        }
        $this->data = $data;
    }
    public function getHeaders() : array
    {
        return $this->header;
    }
    public function setHeaders(array $headers) : void
    {
        $this->header = $headers;
    }
    public function getCURLOPT() : array
    {
        return $this->CURL_OPT;
    }
    public function setCURLOPT(array $CURL_OPT) : void
    {
        $this->CURL_OPT = $CURL_OPT;
    }
    public function getErrorMessage(): ?string
    {
        return $this->error_message;
    }
    public function getResponseMessage(): ?string
    {
        return $this->success_message;
    }
    public function send() : bool
    {
        $url = $this->url;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        foreach ($this->CURL_OPT as $key => $opt)
        {
            curl_setopt($curl, $key, $opt);
        }
        $resp = curl_exec($curl);
        if (curl_errno($curl)){
            $this->error_message = curl_error($curl);
            return false;
        }
        curl_close($curl);
        $this->success_message = $resp;
        return true;
    }
}