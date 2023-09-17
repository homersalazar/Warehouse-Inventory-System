<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'prod_name',
        'prod_sku',
        'prod_partno',
        'label_id',
        'manufacturer_id',
        'unit_id',
        'pref_id'
    ];

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function label()
    {
        return $this->belongsTo(Label::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function pending()
    {
        return $this->hasMany(Pending::class, 'prod_sku');
    }

    public function preference()
    {
        return $this->belongsTo(Preference::class, 'pref_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
