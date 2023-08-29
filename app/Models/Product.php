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
}
