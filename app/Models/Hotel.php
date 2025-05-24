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
}
