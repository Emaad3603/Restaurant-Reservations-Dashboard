<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $table = 'guest_details';
    protected $primaryKey = 'guest_details_id';
    protected $guarded = [];

    /**
     * Get the reservations for the guest.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'guest_reservations_id', 'guest_details_id');
    }
}
