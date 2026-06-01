<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/News.php
class News extends Model
{
    protected $fillable = ['title', 'content', 'image', 'is_active'];
}
