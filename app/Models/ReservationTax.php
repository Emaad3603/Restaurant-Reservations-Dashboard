<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationTax extends Model
{
    use HasFactory;

    protected $table = 'reservations_taxes';
    protected $guarded = [];

    /**
     * Get the reservation that owns the tax.
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Get the tax that is applied.
     */
    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }
}
