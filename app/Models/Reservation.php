<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    protected $primaryKey = 'reservations_id';
    protected $guarded = [];
    public $timestamps = false;

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_NO_SHOW = 'no_show';

    /**
     * Get the status of the reservation.
     *
     * @return string
     */
    public function getStatusAttribute()
    {
        if ($this->canceled == 1) {
            return self::STATUS_CANCELLED;
        } elseif ($this->ended == 1) {
            return self::STATUS_COMPLETED;
        } else {
            return self::STATUS_PENDING;
        }
    }

    /**
     * Get the restaurant that owns the reservation.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'restaurants_id');
    }

    /**
     * Get the meal type for this reservation.
     */
    public function mealType()
    {
        return $this->belongsTo(MealType::class, 'meal_types_id', 'meal_types_id');
    }

    /**
     * Get the hotel for this reservation.
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'guest_hotel_id', 'hotel_id');
    }

    /**
     * Get the guest details for this reservation.
     */
    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_reservations_id', 'guest_details_id');
    }
}
