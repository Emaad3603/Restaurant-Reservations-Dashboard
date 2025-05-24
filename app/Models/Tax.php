<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $table = 'taxes';
    protected $guarded = [];

    /**
     * Get the translations for this tax
     */
    public function translations()
    {
        return $this->hasMany(TaxTranslation::class, 'tax_id');
    }

    /**
     * Get the translation in the current locale
     */
    public function translation()
    {
        return $this->hasOne(TaxTranslation::class, 'tax_id')
            ->where('locale', app()->getLocale())
            ->withDefault();
    }

    /**
     * Get the reservation taxes that include this tax.
     */
    public function reservationTaxes()
    {
        return $this->hasMany(ReservationTax::class);
    }
}
