<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    protected $table = 'forms';
    protected $fillable = [
        'name',  'phone', 'mid',  'aadhar_number', 'city', 'state', 
        'aanchal', 'department', 'post', 'is_coming', 'travel_type', 
        'check_in_date', 'check_out_date', 'check_in_time', 'check_out_time','stay_arrangement','status', 'extra_fields'
    ];

    protected $casts = [
        'extra_fields' => 'array',
    ];
    // public function bookedRoom()
    // {
    //     return $this->hasOne(BookedRoom::class, 'form_id', 'booking_id', 'id');
    // }
    public function bookedRooms()
{  
    return $this->hasMany(BookedRoom::class, 'booking_id', 'id')
                ->where('booking_type', 'vip');
}
// App\Models\Form.php
public function hotel()
    {
        return $this->hasOneThrough(HotelDetails::class, BookedRoom::class, 'booking_id', 'id', 'id', 'hotel_id');
    }
   // App\Models\Form.php

public function cityName()
{
    return $this->belongsTo(City::class, 'city'); // 'city' should match column name in forms table
}

public function stateName()
{
    return $this->belongsTo(State::class, 'state'); // 'state' should match column name in forms table
}

public function aanchalName()
{
    return $this->belongsTo(Aanchal::class, 'aanchal'); // 'aanchal' should match column name in forms table
}

}



