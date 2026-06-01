<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupBooking extends Model
{
    use HasFactory;

    protected $table = 'group_bookings';

    protected $fillable = [
        'name',
        'phone',
        'aadhar_number',
        'mid',
        'father_name',
        'relationship_type',
        'city',
        'state',
        'aanchal',
        'travel_type',
        'check_in_date',
        'check_out_date',
        'check_in_time',
        'check_out_time',
        'total_members',
        'total_persons', // ✅ Add this
        'total_male',
        'total_female',
        'child_count',
        'sixty_plus_members',
        'sixty_plus_male',
        'sixty_plus_female',
        'sixty_plus_female',
        'booking_id',
        'remark',
    ];
    public function groupMembers()
    {
        return $this->hasMany(GroupMember::class);
    }
    public function bookedRooms()
    {
        return $this->hasMany(BookedRoom::class, 'booking_id');
    }
    
    public function hotel()
    {
        return $this->hasOneThrough(HotelDetails::class, BookedRoom::class, 'booking_id', 'id', 'id', 'hotel_id');
    }
    

public function cityName()
{
    return $this->belongsTo(City::class, 'city', 'city_id');
}

public function stateName()
{
    return $this->belongsTo(State::class, 'state', 'state_id');
}

public function aanchalName()
{
    return $this->belongsTo(Aanchal::class, 'aanchal', 'anchal_id');
}

public function members()
{
    return $this->hasMany(\App\Models\GroupMember::class, 'group_booking_id', 'id');
}

 
}