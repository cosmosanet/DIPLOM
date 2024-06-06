<?php
namespace App\Interfaces;
use Aws\S3\S3Client;

interface S3ClientInterface {

    public function GetS3ClientClient(string $service_name, string $region);
    
}

