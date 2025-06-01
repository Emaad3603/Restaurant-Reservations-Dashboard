<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $table = 'restaurants';
    protected $primaryKey = 'restaurants_id';
    protected $guarded = [];

    /**
     * Get the hotel that owns the restaurant.
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }

    /**
     * Get the reservations for the restaurant.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'restaurant_id', 'restaurants_id');
    }

    /**
     * Get the translations for this restaurant
     */
    public function translations()
    {
        return $this->hasMany(RestaurantTranslation::class, 'restaurants_id', 'restaurants_id');
    }

    /**
     * Get the translation in the current locale
     */
    public function translation()
    {
        return $this->hasOne(RestaurantTranslation::class, 'restaurants_id', 'restaurants_id')
            ->withDefault([
                'name' => $this->name ?? 'Unnamed Restaurant',
                'cuisine' => ''
            ]);
    }

    /**
     * Get the admin user who created this restaurant
     */
    public function creator()
    {
        return $this->belongsTo(AdminUser::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the admin user who last updated this restaurant
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
