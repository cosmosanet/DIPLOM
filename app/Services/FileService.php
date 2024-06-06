<?php
namespace App\Services;

use App\Classes\HashClass;
use App\Classes\HashControllClass;
use App\Classes\LogClass;
use App\Models\File;
use App\Repositories\FileRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class FileService
{
    protected $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }
    //скачивание файла
    public function fileDowload(string $bucket, string $id, string $filePath): array|string
    {
        $fileSplit = explode('.', $id);
        $fileType = end($fileSplit);
        $objectKey = hash('sha512', $id) . '.' . $fileType;
        $fileUrl = $this->fileRepository->fileDowload($bucket, $objectKey, $filePath);
        $logs = new LogClass();
        $client = new Client();
        try
        {
            $response = $client->get($fileUrl);
            $logs->createLog('fileDowload', 'Ссылка выдана пользователю');
            return $fileUrl;
        }
        catch (ClientException $e)
        {
            preg_match('/<Code>(.*?)<\/Code>/',  $e->getMessage(), $massageArry);
            preg_match('/FILE_NAME(.*)/',  $id, $fimeNameArry);
         
            $logs->createLog('fileDowload', 'Неудачная попытка скачивания файла ' . $fimeNameArry[1] . ' Ошибка: ' . $massageArry[1]);
            return ['errors' => 'Ошибка: '. $massageArry[1] . ' обратитесь а администратору'];
            // FILE_NAME
        }
        
        
        return $fileUrl;
    }
    //регистрация файла
    public function registerFile(string $file_name, string $fileContent): array
    {
        $hashControllClass = new HashControllClass();
        $hash = $hashControllClass->Get3HashSum($fileContent);
        $md5 = $hash->getMD5();
        $sha1 = $hash->getSHA1();
        $sha512 =  $hash->getSHA512();
        $checkResult = $this->checkFile($hash);
        $fileSplit = explode('.', $file_name);
        $fileType = end($fileSplit);
        $objectKey = hash('sha512', 'HASHSUM' . 'MD5' . $md5 . 'SHA1' . $sha1 . 'SHA512' .  $sha512 .  'FILE_NAME' . $file_name) . '.' .  $fileType;
        $logs = new LogClass();

        if( $checkResult ){
            $massage = $this->fileRepository->fileUpload(config('app.basket'), $objectKey, $fileContent);
            if($massage !== null)
            {
                return ['errors' => $massage];
            }
            $this->fileRepository->createFileRecord($hash ,$file_name);
            $logs->createLog('registerFile', 'Фаил: ' . $file_name . ' успешно загружен');
            return ['success' => 'Файл успешно загружен'];
        } else {
            return ['errors' => 'Такой фаил уже существует'];
        } 
    }
    //получение списка всех файлов
    public function getListOfFile(?string $name)
    {
        $list = $this->fileRepository->getListOfFile($name);
        return $list;
    }
    //удаление фалйа
    public function deleteFile(HashClass $hash)
    {
        $record = $this->getFileRecord($hash);
        $md5 = $record->md5;
        $sha1 = $record->sha1;
        $sha512 =  $record->sha512;
        $file_name = $record->file_name;
        $fileSplit = explode('.', $file_name);
        $fileType = end($fileSplit);
        $objectKey = hash('sha512', 'HASHSUM' . 'MD5' . $md5 . 'SHA1' . $sha1 . 'SHA512' .  $sha512 .  'FILE_NAME' . $file_name) . '.' .  $fileType;
        
        $result = $this->fileRepository->deleteFile(config('app.basket'), $objectKey);
        $this->fileRepository->changeFileStatus($hash, "удалён");
        $logs = new LogClass();

        switch ($result) {
            case 204:
                $logs->createLog('deleteFile', 'Фаил: ' . $file_name . ' успешно удален');
                return ['success' => 'Файл успешно удален'];
                break;
            }
    }
    //изменение файла
    public function editFile(HashClass $deleteHash, string $file_name, string $fileContent)
    {
        $hashControllClass = new HashControllClass();
        $hash = $hashControllClass->Get3HashSum($fileContent);
        $md5 = $hash->getMD5();
        $sha1 = $hash->getSHA1();
        $sha512 =  $hash->getSHA512();
        $checkResult = $this->checkFile($hash);
        $fileSplit = explode('.', $file_name);
        $fileType = end($fileSplit);
        $objectKey = hash('sha512', 'HASHSUM' . 'MD5' . $md5 . 'SHA1' . $sha1 . 'SHA512' .  $sha512 .  'FILE_NAME' . $file_name) . '.' .  $fileType;
        $logs = new LogClass();

        if( $checkResult ){
            $record = $this->getFileRecord($deleteHash);
            $deletemd5 = $record->md5;
            $deletesha1 = $record->sha1;
            $deletesha512 =  $record->sha512;
            $deletefile_name = $record->file_name;
            $deletefileSplit = explode('.', $file_name);
            $deletefileType = end($deletefileSplit);
            $deleteobjectKey = hash('sha512', 'HASHSUM' . 'MD5' . $deletemd5 . 'SHA1' . $deletesha1 . 'SHA512' .  $deletesha512 .  'FILE_NAME' . $deletefile_name) . '.' .  $deletefileType;
            $this->fileRepository->changeFileStatus($deleteHash, "заменён");
            $result = $this->fileRepository->deleteFile(config('app.basket'), $deleteobjectKey);
            if ($result == 204)
            {
                $this->fileRepository->fileUpload(config('app.basket'), $objectKey, $fileContent);
                $this->fileRepository->createFileRecord($hash ,$file_name);
                $logs->createLog('editFile', 'Фаил: ' . $file_name . ' заменён на ' . 'Фаил: ' . $deletefile_name);
                return ['success' => 'Фаил успешно заменён'];
            } else {
                return ['errors' => 'Ошибка удаления ' . $result];
            }
        } else {
            return ['errors' => 'Такой файл уже существует'];
        } 
    }
    //получение записи о файле из бд
    private function getFileRecord(HashClass $hash)
    {
        $md5 = $hash->getMD5();
        $sha1 = $hash->getSHA1();
        $sha512 =  $hash->getSHA512();
        $reqest = File::where('md5', '=' , $md5, 'or', 'sha1' , '=', $sha1, 'or' , 'sha512', '=', $sha512)->first();
        return $reqest;
    }
    //проверка на наличе файла в бд
    private function checkFile(HashClass $hash)
    {
        $md5 = $hash->getMD5();
        $sha1 = $hash->getSHA1();
        $sha512 =  $hash->getSHA512();

        $reqest = File::where('md5', '=' , $md5, 'or', 'sha1' , '=', $sha1, 'or' , 'sha512', '=', $sha512)->count();
        return ($reqest >= 1)? false : true;
    }
}
