<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomFeatures extends Model
{
    protected $table = 'room_features';

    protected $fillable = [
    'hotel_id', 'room_number', 'category_id', 'ac', 'attach_bath', 'toilet_type','status',
];


public function hotel()
{
    return $this->belongsTo(\App\Models\HotelDetails::class, 'hotel_id', 'id');
}


}
