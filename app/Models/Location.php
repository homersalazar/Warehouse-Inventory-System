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

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public function pendingFrom()
    {
        return $this->hasMany(Pending::class, 'tran_from');
    }

    public function pendingTo()
    {
        return $this->hasMany(Pending::class, 'location_id');
    }
}
