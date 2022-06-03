<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Client extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'clients';
    protected $fillable = [
        'clientId',
        'ipaddress', 
        'country',
        'timezone',
        'isActive',
        'domain'
    ]; 

    protected $dates = ['created_at', 'updated_at', 'datetime'];
}