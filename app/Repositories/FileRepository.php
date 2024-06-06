<?php
namespace App\Repositories;

use App\Classes\HashClass;
use App\Classes\S3ClientClass;
use App\Models\File;
use App\Models\Logs;
use Aws\S3\Exception\S3Exception;
use GuzzleHttp\Exception\ClientException;

class FileRepository
{
    //Скачивание файлов в Object storage
    public function fileDowload(string $bucket, string $objectKey, string $filePath): string
    {
        $S3ClientClass = new S3ClientClass();
        $s3 = $S3ClientClass->GetS3ClientClient('s3', 'https://storage.yandexcloud.net/');
        
        $command =  $s3->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key'    => $objectKey,
        ]);

        $request =  $s3->createPresignedRequest($command, '+15 minutes');
        $downloadUrl = (string)$request->getUri();
        return $downloadUrl;
        
    }

    //Загрузка файлов в Object storage
    public function fileUpload(string $bucket, string $objectKey, string $fileContent)
    {
        try 
        {
        $S3ClientClass = new S3ClientClass();
        $s3 = $S3ClientClass->GetS3ClientClient('s3', 'https://storage.yandexcloud.net/');
       
            $result = $s3->putObject([
                'Bucket' => $bucket,
                'Key'    => $objectKey,
                'Body' => $fileContent,
            ]);
        }
        catch (S3Exception $e)
        {
            return $e->getAwsErrorMessage();
        }
        
    }
    public function getListOfFile(?string $name)
    {
        return ($name == null) ? File::orderBy('updated_at', 'DESC')->orderBy('status', 'ASC')->get() : File::where('file_name', 'LIKE', '%'.$name.'%')->orderBy('file.updated_at', 'DESC')->orderBy('status', 'ASC')->get();
    }
    //удаление файла из object storage
    public function deleteFile(string $bucket, string $objectKey)
    {
        $S3ClientClass = new S3ClientClass();
        $s3 = $S3ClientClass->GetS3ClientClient('s3', 'https://storage.yandexcloud.net/');
        
        $result = $s3->deleteObject([
            'Bucket' => $bucket,
            'Key'    => $objectKey,      
        ]);
       return $result->get('@metadata')['statusCode'];
    }
    ///Изменение статуса файла
    public function changeFileStatus(HashClass $hashsum, string $status)
    {
       
        $md5 = $hashsum->getMD5();
        $sha1 = $hashsum->getSHA1();
        $sha512 =  $hashsum->getSHA512();
        File::where('md5', '=', $md5, 'and', 'sha1', '=', $sha1, 'and', 'sha512', '=', $sha512)->update(['status' => $status]);
    }
    
    ///Создание записи о файле в бд
    public function createFileRecord(HashClass $hashsum, string $filemane): void
    {
        $md5 = $hashsum->getMD5();
        $sha1 = $hashsum->getSHA1();
        $sha512 =  $hashsum->getSHA512();
        File::insert(['md5' => $md5, 'sha1' => $sha1, 'sha512' => $sha512, 'file_name'=> $filemane , 'id_user'=> session()->get('userId')]);
    }
}
