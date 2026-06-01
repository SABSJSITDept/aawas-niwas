<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;

    // Specify the table name if necessary
    protected $table = 'group_members';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'name',
        'mobile_number',
        'group_booking_id', // Foreign key
    ];

    // Define the relationship with the GroupBooking model
    public function groupBooking()
    {
        return $this->belongsTo(GroupBooking::class);
    }
}
