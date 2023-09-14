<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    use HasFactory;

    protected $fillable = ['pref_name' , 'pref_value'];

    public function products()
    {
        return $this->hasMany(Product::class, 'pref_id');
    }
}
