<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'tran_date',
        'tran_option',
        'tran_quantity',
        'tran_unit',
        'tran_serial',
        'tran_comment',
        'tran_action',
        'user_id'
    ];
}
