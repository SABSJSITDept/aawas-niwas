<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;

    protected $table = 'family_members';

    protected $fillable = [
        'family_id',  // Foreign key linking to `family_booking` 
        'name',
        'father_name',
        'age',
        'gender',
        'mobile',
        'aadhar_number',
    ];

    /**
     * Relationship with FamilyBooking (Parent Table) 
     */
    public function familyBooking()
    {
        return $this->belongsTo(FamilyBooking::class, 'family_id');
    }
}
