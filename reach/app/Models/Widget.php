<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Widget extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'widgets';
    protected $fillable = [
        'name',
        'isActive',
        'color',
        'timezone',
        'hasSchedule',
        'starttime',
        'endtime',
        'domainBanList',
        'ipBanList',
        'countryBanList'
    ]; 

    protected $dates = ['created_at', 'updated_at', 'datetime'];
}