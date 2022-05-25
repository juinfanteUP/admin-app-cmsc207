<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Widget extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'widgets';
    protected $fillable = [
        'widgetId',
        'name',
        'isActive',
        'color',
        'timezone',
        'starttime',
        'domainBanList',
        'ipBanList',
        'countryBanList'
    ]; 

    protected $dates = ['created_at', 'updated_at', 'datetime'];
}