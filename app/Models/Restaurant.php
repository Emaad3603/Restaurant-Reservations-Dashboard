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
     * Get the menu categories for the restaurant.
     */
    public function menuCategories()
    {
        return $this->hasMany(MenuCategory::class, 'restaurant_id', 'restaurants_id');
    }
}
