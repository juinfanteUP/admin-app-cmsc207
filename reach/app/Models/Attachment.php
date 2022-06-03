<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Attachment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'attachments';
    protected $fillable = [
        'id',
        'filePath',
        'fileName', 
        'fileSize'
    ]; 

    protected $dates = ['created_at'];
}