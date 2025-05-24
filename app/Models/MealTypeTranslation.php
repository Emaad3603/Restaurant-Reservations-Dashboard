<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealTypeTranslation extends Model
{
    use HasFactory;

    protected $table = 'meal_types_translation';
    protected $primaryKey = 'meal_types_translation_id';
    protected $guarded = [];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'meal_type_id' => 'integer',
        'meal_types_id' => 'integer',
    ];

    /**
     * Get the meal type that owns the translation.
     */
    public function mealType()
    {
        return $this->belongsTo(MealType::class, 'meal_types_id', 'meal_types_id');
    }
}
