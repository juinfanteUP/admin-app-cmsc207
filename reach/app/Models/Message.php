<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Message extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'messages';
    protected $fillable = [
        'clientId',
        'senderId', 
        'body',
        'isAgent',
        'isWhisper',
        'attachmentId',
        'created_at'
    ]; 

    protected $dates = ['created_at', 'updated_at', 'datetime'];
}