<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;

    protected $table = 'logs';
    
    protected $fillable = [
       'id',
       'log_text',
       'action',
       'id_user',
       'log_create_time',
       
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user' , 'id');
    }
}
