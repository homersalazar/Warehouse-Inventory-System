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
        'prod_upc',
        'prod_summary',
        'label_id',
        'area_id',
        'manufacturer_id',
        'pref_id'
    ];

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
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
        return $this->hasMany(Pending::class);
    }

    public function preference()
    {
        return $this->belongsTo(Preference::class, 'pref_id');
    }
}
