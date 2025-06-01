<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantPricingTime extends Model
{
    protected $table = 'restaurant_pricing_times';
    protected $primaryKey = 'restaurant_pricing_times_id';
    public $timestamps = false;

    protected $fillable = [
        'restaurant_id',
        'menus_id',
        'start_time',
        'end_time',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the restaurant that owns this pricing time
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'restaurants_id');
    }

    /**
     * Get the menu associated with this pricing time
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menus_id', 'menus_id');
    }

    /**
     * Get the hotel that owns this pricing time through the restaurant
     */
    public function hotel()
    {
        return $this->restaurant->hotel();
    }

    /**
     * Get the hotel_id from the associated restaurant
     */
    public function getHotelIdAttribute()
    {
        return $this->restaurant ? $this->restaurant->hotel_id : null;
    }

    /**
     * Get the admin user who created this pricing time
     */
    public function creator()
    {
        return $this->belongsTo(AdminUser::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the admin user who last updated this pricing time
     */
    public function updater()
    {
        return $this->belongsTo(AdminUser::class, 'updated_by', 'admin_users_id');
    }

    /**
     * Get the creator's name
     */
    public function getCreatorNameAttribute()
    {
        return $this->creator ? $this->creator->display_name : 'System';
    }

    /**
     * Get the updater's name
     */
    public function getUpdaterNameAttribute()
    {
        return $this->updater ? $this->updater->display_name : 'System';
    }
} 