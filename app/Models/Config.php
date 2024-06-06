<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $table = 'config';

    protected $fillable = [
        'id',
        'login',
        'password',
        'id_role',
    ];
    
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'id_role');
    }
}
