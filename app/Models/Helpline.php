<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Helpline extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'representative_name',
        'number',
        'type',
        'is_home_medical',
        'status',
    ];
}
