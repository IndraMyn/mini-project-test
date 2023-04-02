<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $connection = "mongodb";
    public $collection = "orders";

    protected $fillable = [
        'products',
        'payment_method',
        'shipment_type',
        'phone_number',
        'address'
    ];

}
