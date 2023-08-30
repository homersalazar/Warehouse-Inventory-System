<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'manufacturer_name',
        'manufacturer_status'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
