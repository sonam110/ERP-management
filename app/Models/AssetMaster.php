<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMaster extends Model
{
    protected $fillable = [
        'name',
        'unique_id',
        'price',
        'available_quantity',
        'purchase_date',
        'supported_date',
        'description',
        'created_by',
    ];
}
