<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    public $collection = "products";

    protected $fillable = [
        'name',
        'price',
        'description',
        'stock'
    ];
}
