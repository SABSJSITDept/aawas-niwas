<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'travel_type',
        'check_in_date',
        'check_out_date',
        'check_in_time',
        'check_out_time',
    ];
}
