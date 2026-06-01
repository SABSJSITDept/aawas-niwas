<?php

namespace App\Models;
use App\Models\HotelDetails;


use Illuminate\Database\Eloquent\Model;

class BookedRoom extends Model
{
    protected $table = 'booked_rooms';

   protected $fillable = [
    'booking_id',    
    'hotel_id',
    'room_number',
    'booking_type',
    'total_capacity',
    'mobile_number',
    'check_in_date',
    'check_out_date',
];

    public function hotel() {
        return $this->belongsTo(HotelDetails::class, 'hotel_id');
    }
   // App\Models\BookedRoom.php

public function form()
{
    return $this->belongsTo(\App\Models\Form::class, 'booking_id', 'id');
}

public function familyBooking()
{
    return $this->belongsTo(\App\Models\FamilyBooking::class, 'booking_id', 'booking_id');
}

public function groupBooking()
{
    return $this->belongsTo(\App\Models\GroupBooking::class, 'booking_id', 'booking_id');
}


    
}