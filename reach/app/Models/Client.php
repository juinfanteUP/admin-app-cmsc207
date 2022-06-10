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
        'isMute',
        'domain',
        'source',
        'notes',
        'label',
        'latestConversationId'
    ]; 

    protected $dates = ['created_at', 'updated_at', 'datetime'];
}