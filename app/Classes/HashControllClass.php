<?php
namespace App\Classes;

use App\Interfaces\HashControllInterfaces;
use App\Classes\HashClass;

class HashControllClass extends HashClass implements HashControllInterfaces
{
    public function Get3HashSum(string $filePath): HashClass
    {
        $hash = new HashClass;
        $hash->md5 = hash('md5', $filePath);
        $hash->sha1 = hash('sha1', $filePath);
        $hash->sha512 = hash('sha512', $filePath);
        return $hash;
    }
    
    public function GetHMACHashSum(string $filePath): string
    {
        $hash = $this->Get3HashSum($filePath);
        $hmac = hash('sha512', $hash->md5 . $hash->sha1 . $hash->sha512 . config('app.yandex_api_secret_key') . time()) ;
        return $hmac;
    }
}