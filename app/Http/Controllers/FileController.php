<?php

namespace App\Http\Controllers;

use App\Classes\HashClass;
use App\Classes\S3ClientClass;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

require '..\..\..\vendor\autoload.php';


class FileController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    //скачивание файла
    public function fileDowload($id)
    {
        $massage = $this->fileService->fileDowload(config('app.basket'), $id , 'asd');
      
        return (isset($massage['errors'])) ? redirect('/')->with('errors_action', $massage['errors']) :   redirect($massage);
    }
    //загрузка файла
    public function fileUpload(Request $request)
    {   $messages = [
        'file.required' => 'Выбирите файл.',
        'max' => 'Файл слишком велик'
        ];
        $request->validate([
            'file' => 'required|file|max:1048576', // Проверка на обязательность, формат, размер
        ], $messages);
        $fileContent = file_get_contents($request->file('file'));
        $inputFileName = $request->file('file')->getClientOriginalName();
        $massage = $this->fileService->registerFile($inputFileName, $fileContent);
      
        return (isset($massage['errors'])) ? redirect('/')->with('errors_action', $massage['errors']) :  redirect('/')->with('success_action', $massage['success']);
    }
    //изменение файла
    public function editFile(Request $request)
    {
        $hash = new HashClass;
        $hash->putMD5($request->input('md5'));
        $hash->putSHA1($request->input('sha1'));
        $hash->putSHA512($request->input('sha512'));
        $fileContent = file_get_contents($request->file('file'));
        $inputFileName = $request->file('file')->getClientOriginalName();
        $massage = $this->fileService->editFile($hash, $inputFileName, $fileContent);
        return (isset($massage['errors'])) ? redirect('/')->with('errors_action', $massage['errors']) :  redirect('/')->with('success_action', $massage['success']);
    }

    public function fileDelete(Request $request)
    {
        $hash = new HashClass;
        $hash->putMD5($request->input('md5'));
        $hash->putSHA1($request->input('sha1'));
        $hash->putSHA512($request->input('sha512'));
        $massage = $this->fileService->deleteFile($hash);
        return (isset($massage['errors'])) ? redirect('/')->with('errors_action', $massage['errors']) :  redirect('/')->with('success_action', $massage['success']);
    }

    
}


