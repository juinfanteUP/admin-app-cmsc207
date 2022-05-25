<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $connection = 'mongodb';
    protected $collection = 'users';
    protected $fillable = [
        'email',
        'password',
        'firstname',
        'lastname',
        'nickname'
    ]; 

    protected $dates = ['created_at', 'updated_at', 'datetime'];
}
