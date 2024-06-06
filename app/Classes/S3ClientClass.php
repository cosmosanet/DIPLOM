<?php
namespace App\Classes;

use Aws\S3\S3Client;
use App\Interfaces\S3ClientInterface;

class S3ClientClass implements S3ClientInterface
{
    public function GetS3ClientClient(string $serviceName, string $endpointUrl)
    {
        $s3 = new S3Client([
            'credentials' => [
                'key'      => session()->get('apiKey'),
                'secret'   => session()->get('secretApiKey'),
            ],
            'version' => 'latest',
            'endpoint' => $endpointUrl,
            'region' => 'default-ru-central1-a',
            'service_name' => $serviceName,
        ]);

        return $s3;
        
    }

    private function getApiKeys(string $secretId, string $iAmKey)
    {
        $curl = curl_init();

        $url = "https://payload.lockbox.api.cloud.yandex.net/lockbox/v1/secrets/" . $secretId . "/payload";
        $headers = array(
            'Authorization: Bearer ' . $iAmKey,
        );

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $response = json_decode(curl_exec($curl));
 
        if(isset($response->code)){
            $massage = $response ;
            return $massage;
        }

        return $response->entries[0];
    }

}
