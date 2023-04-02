<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $connection = "mongodb";
    public $collection = "carts";

    public $fillable = [
        '_id',
        'product_id',
        'qty'
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'product_id', '_id');
    }
}
