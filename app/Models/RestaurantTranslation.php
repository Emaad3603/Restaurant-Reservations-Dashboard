<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantTranslation extends Model
{
    use HasFactory;

    protected $table = 'restaurants_translations';
    protected $primaryKey = 'restaurant_translations_id';
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'restaurants_id' => 'integer',
    ];

    /**
     * Get the restaurant that owns the translation.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurants_id', 'restaurants_id');
    }
}
