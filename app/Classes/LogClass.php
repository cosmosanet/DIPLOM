<?php
namespace App\Classes;

use App\Models\Logs;

class LogClass
{
    public function createLog(string $action, string $logText): void
    {
        Logs::insert(['log_text' => $logText, 'action' => $action, 'id_user'=> session()->get('userId')]);
    }
}