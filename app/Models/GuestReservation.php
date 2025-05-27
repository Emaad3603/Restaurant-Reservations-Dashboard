<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestReservation extends Model
{
    use HasFactory;

    protected $table = 'guest_reservations';
    protected $primaryKey = 'guest_reservations_id';
    protected $guarded = [];
    public $timestamps = false;

    public function guestDetails()
    {
        return $this->hasMany(GuestDetail::class, 'guest_reservations_id', 'guest_reservations_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'guest_reservations_id', 'guest_reservations_id');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }
} 