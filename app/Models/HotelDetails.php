<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelDetails extends Model
{
    use HasFactory;

    protected $table = 'hotel_details'; // HotelDetails ka table name
    protected $fillable = ['hotel_name', 'total_rooms', 'incharge_name', 'contact_number', 'common_bath','lift', 'generator', 'address','google_maps_link','status',];

    // Relationship with RoomCategory
    public function roomCategories()
    {
        return $this->hasMany(RoomCategory::class, 'hotel_id');
    }   
    public function roomFeatures()
{
    return $this->hasMany(RoomFeatures::class, 'hotel_id');
}

}
