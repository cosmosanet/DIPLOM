<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = 'file';
    
    protected $fillable = [
       'md5',
       'sha1',
       'sha512',
       'file_name',
       'status',
       'id_user',
       'create_time',
       'updated_at',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user' , 'id');
    }
}
