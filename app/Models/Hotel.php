<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $table = 'hotels';
    protected $primaryKey = 'hotel_id';
    protected $guarded = [];

    /**
     * Get the restaurants for the hotel.
     */
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'hotel_id', 'hotel_id');
    }

    /**
     * Get all reservations for the hotel through restaurants.
     */
    public function reservations()
    {
        return $this->hasManyThrough(
            Reservation::class, 
            Restaurant::class,
            'hotel_id', // Foreign key on restaurants table
            'restaurant_id', // Foreign key on reservations table
            'hotel_id', // Local key on hotels table
            'restaurants_id' // Local key on restaurants table
        );
    }

    /**
     * Get the admin user who created this hotel
     */
    public function creator()
    {
        return $this->belongsTo(AdminUser::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the admin user who last updated this hotel
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
