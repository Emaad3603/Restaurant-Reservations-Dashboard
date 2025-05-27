<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GuestReservation;
use App\Models\GuestDetail;

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
     * Get the guest reservation (main guest record).
     */
    public function guestReservation()
    {
        return $this->belongsTo(GuestReservation::class, 'guest_reservations_id', 'guest_reservations_id');
    }

    /**
     * Get all guest details for this reservation.
     */
    public function guestDetails()
    {
        return $this->hasMany(GuestDetail::class, 'guest_reservations_id', 'guest_reservations_id');
    }

    /**
     * Get a comma-separated list of guest names.
     */
    public function getGuestNamesAttribute()
    {
        $names = $this->guestDetails->pluck('guest_name')->filter()->all();
        return $names ? implode(', ', $names) : null;
    }

    /**
     * Get the hotel for this reservation (from guest reservation).
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'guest_hotel_id', 'hotel_id');
    }

    /**
     * Get the reservation date.
     */
    public function getReservationDateAttribute()
    {
        return $this->day;
    }

    /**
     * Get the reservation time.
     */
    public function getReservationTimeAttribute()
    {
        return $this->time;
    }

    /**
     * Get the number of people for this reservation.
     */
    public function getPeopleCountAttribute()
    {
        return $this->pax ?? $this->guestDetails->count() ?: null;
    }
}
