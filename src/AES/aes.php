<?php
namespace Vqhteam\Support\AES;
class aes {
    private string $iv;
    private string $hash;
    private $encrypted;
    private ?string $password=null;
    function __construct(string $iv,string $hash,$encrypted,string $password=null)
    {
        $this->iv = $iv;
        $this->hash = $hash;
        $this->encrypted = $encrypted;
        $this->password = $password;
    }
    public function getEncryptedData(){
        return $this->encrypted;
    }
    public function getEncryptedDataBase64():string{
        return base64_encode($this->encrypted);
    }
    public function getIV():string{
        return $this->iv;
    }
    public function getSalt():string{
        return $this->hash;
    }
    public function getPassword():string{
        return $this->password;
    }
    public function toString():string{
        return base64_encode(json_encode(['iv'=>$this->iv,'ct'=>$this->getEncryptedDataBase64(),
            's'=>$this->getSalt()]));
    }
}