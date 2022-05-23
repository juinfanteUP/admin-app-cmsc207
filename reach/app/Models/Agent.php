<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Agent extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'agents';
    protected $fillable = [
        'agentId',
        'email',
        'firstname',
        'lastname',
        'nickname',
        'createddate'
    ]; 

    protected $dates = ['created_at', 'updated_at', 'datetime'];
}
