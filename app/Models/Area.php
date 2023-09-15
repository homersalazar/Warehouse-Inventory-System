<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_name',
        'area_status'
    ];
    
    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
    
}
