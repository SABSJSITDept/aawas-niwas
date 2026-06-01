<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThanaSant extends Model
{
    protected $table = 'thana_sant';

    protected $fillable = [
        'thana_id',
        'sant_name',
    ];

    public function sadhuSadvi()
    {
        return $this->belongsTo(SadhuSadvi::class, 'thana_id');
    }
}
