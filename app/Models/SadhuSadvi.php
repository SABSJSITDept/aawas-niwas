<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SadhuSadvi extends Model
{
    protected $fillable = [
        'name',
        'address',
        'link',
        'thana',
    ];

    public function thanaSants()
    {
        return $this->hasMany(ThanaSant::class, 'thana_id');
    }
}
