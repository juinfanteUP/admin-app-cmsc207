<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class ClientBan extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'clientban';
    protected $fillable = [
        'clientId',
        'ipaddress',
        'domain',
        'country',
        'bannedBy',
    ]; 

    protected $dates = ['created_at'];
}