<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class WidgetIcon extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'widget_icons';
    protected $fillable = [
        'id',
        'name',
        'img_src'
        
    ]; 

    protected $dates = ['created_at', 'updated_at', 'datetime'];
}