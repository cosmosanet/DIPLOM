<?php

namespace App\Http\Controllers;


use App\Services\FileService;
use AsyncAws\Sqs\SqsClient;
use Illuminate\Http\Request;

require '..\vendor\autoload.php';

class MainController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        
        $this->fileService = $fileService;
    }

    public function index(Request $request)
    {
        $name = null;
        if(isset($request->name))
        {
            // dd($request->input('user'));
            $name = $request->input('name');
        }
        $listOfFile = $this->fileService->getListOfFile($name);
        return view('welcome', ['list' => $listOfFile, 'user' => $name]);   
    }
}
