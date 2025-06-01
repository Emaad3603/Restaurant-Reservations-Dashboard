<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealType extends Model
{
    use HasFactory;

    protected $table = 'meal_types';
    protected $primaryKey = 'meal_types_id';
    protected $guarded = [];

    /**
     * Get the hotel that owns this meal type
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }

    /**
     * Get the restaurant that owns this meal type
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'restaurants_id');
    }

    /**
     * Get the translations for this meal type
     */
    public function translations()
    {
        return $this->hasMany(MealTypeTranslation::class, 'meal_types_id', 'meal_types_id');
    }

    /**
     * Get the translation for this meal type
     */
    public function translation()
    {
        // Use the correct foreign key name
        return $this->hasOne(MealTypeTranslation::class, 'meal_types_id', 'meal_types_id')
            ->withDefault(function ($translation, $mealType) {
                $translation->name = $mealType->name ?? 'Unnamed Meal Type';
                $translation->description = '';
            });
    }

    /**
     * Get the reservations for this meal type
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'meal_types_id', 'meal_types_id');
    }

    /**
     * Get the admin user who created this meal type
     */
    public function creator()
    {
        return $this->belongsTo(AdminUser::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the admin user who last updated this meal type
     */
    public function updater()
    {
        return $this->belongsTo(AdminUser::class, 'updated_by', 'admin_users_id');
    }

    /**
     * Get the creator's name
     */
    public function getCreatorNameAttribute()
    {
        return $this->creator ? $this->creator->display_name : 'System';
    }

    /**
     * Get the updater's name
     */
    public function getUpdaterNameAttribute()
    {
        return $this->updater ? $this->updater->display_name : 'System';
    }
}
