<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Aws\S3\S3Client;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
