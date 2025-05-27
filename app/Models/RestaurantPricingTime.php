<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantPricingTime extends Model
{
    protected $table = 'restaurant_pricing_times';
    protected $primaryKey = 'restaurant_pricing_times_id';
    public $timestamps = false;
} 