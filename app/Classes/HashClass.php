<?php
namespace App\Classes;

use App\Interfaces\HashInterfaces;

class HashClass
{
    protected string $md5;

    protected string $sha1;

    protected string $sha512;

    public function getMD5()
    {
        return $this->md5;
    }
    public function getSHA1()
    {
        return $this->sha1;
    }
    public function getSHA512()
    {
        return $this->sha512;
    }

    public function putMD5(string $md5)
    {
        $this->md5 = $md5;
    }
    public function putSHA1(string $sha1)
    {
        $this->sha1 = $sha1;
    }
    public function putSHA512(string $sha512)
    {
        $this->sha512 = $sha512;
    }


}