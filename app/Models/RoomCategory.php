<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomCategory extends Model
{
    use HasFactory;

    protected $table = 'room_category'; 
    protected $fillable = [
        'hotel_id', 'category_id', 'floor', 'beds', 'extra_capacity', 'total_capacity', 'room_number'
    ];
    public function hotel()
    {
        return $this->belongsTo(HotelDetails::class, 'hotel_id');
    }
    public function roomFeatures()
{
    return $this->hasMany(RoomFeatures::class, 'category_id', 'category_id');
}
public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}

}



