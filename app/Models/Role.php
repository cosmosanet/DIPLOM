<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';
    
    protected $fillable = [
       'id',
       'name_roles',
       'deception',
       'yandex_cloud_id',
       'yandex_secret_id',
    ];
}
