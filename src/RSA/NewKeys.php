<?php
namespace Vqhteam\Support\RSA;
class NewKeys {
    private ?string $public_key=null;
    private ?string $private_key=null;
    private int $bits=4096;
    function __construct(int $bits=4096){
        $config = array(
            "digest_alg" => "sha512",
            "private_key_bits" => $bits,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privKey);
        $pubKey = openssl_pkey_get_details($res);
        $this->public_key = $pubKey["key"];
        $this->private_key = $privKey;
        $this->bits = $bits;
    }
    public function get(): array
    {
        return ['public_key'=>$this->public_key,'private_key'=>$this->private_key,'bits'=>$this->bits];
    }
    public function getPublicKey(){
        return $this->public_key;
    }
    public function getPrivateKey(){
        return $this->private_key;
    }
}