<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pending extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'tran_date',
        'tran_quantity',
        'tran_serial',
        'tran_comment',
        'tran_action',
        'tran_drno',
        'tran_mpr',
        'tran_from',
        'location_id',
        'user_id'
    ];
    
    public function locationFrom()
    {
        return $this->belongsTo(Location::class, 'tran_from');
    }

    public function locationTo()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(product::class);
    }

    
}
