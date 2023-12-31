<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'prod_sku',
        'tran_date',
        'tran_option',
        'tran_quantity',
        'tran_unit',
        'tran_serial',
        'tran_remarks',
        'tran_action',
        'tran_drno',
        'tran_mpr',
        'area_id',
        'equipment_id',
        'location_id',
        'user_id'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'prod_sku');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function pending()
    {
        return $this->hasMany(Pending::class, 'prod_sku');
    }
}
