<?php
namespace App\Interfaces;

use App\Classes\HashClass;

interface HashControllInterfaces {
    public function Get3HashSum(string $filePath): HashClass;
    
    public function GetHMACHashSum(string $filePath): string;
}
