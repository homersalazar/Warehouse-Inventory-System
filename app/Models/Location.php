<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'loc_name',
        'loc_address',
        'loc_city',
        'loc_state',
        'loc_zip',
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'location_id');
    }
}
