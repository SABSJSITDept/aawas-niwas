<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyBooking extends Model
{
    protected $table = 'family_booking';
    protected $fillable = [
        'name',
        'father_name',
        'age',
        'gender',
        'phone',
        'ms_name',
        'aadhar_number',
        'mid',
        'city',
        'state',
        'aanchal',
        'travel_type',
        'check_in_date',
        'check_out_date',
        'check_in_time',
        'check_out_time',
        'family_coming',
        'no_of_people',
        'total_persons',
        'no_of_children',
        'total_male',
        'total_female',
        'sixty_plus_members',
        'sixty_plus_male',
        'sixty_plus_female',
        'is_veer_parivar',
        'veer_relation',
        'booking_id',
        'remark',
        'extra_fields',
    ];

    protected $casts = [
        'extra_fields' => 'array',
    ];

    public function familyMembers()
{
    return $this->hasMany(FamilyMember::class, 'family_id',);
}

public function booked_rooms()
{
    return $this->hasMany(BookedRoom::class, 'booking_id')->where('booking_type', 'family');
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
    return $this->hasMany(\App\Models\FamilyMember::class, 'family_id', 'id');
}


}




    