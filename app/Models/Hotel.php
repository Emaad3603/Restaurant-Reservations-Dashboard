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
     * Set the hotel's logo URL.
     *
     * @param  string  $value
     * @return void
     */
    public function setLogoUrlAttribute($value)
    {
        if ($value && !str_starts_with($value, 'hotels/')) {
            $this->attributes['logo_url'] = $value;
        } else {
            $this->attributes['logo_url'] = $value;
        }
    }

    /**
     * Get the hotel's logo URL.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getLogoUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }

        // If it's already a full URL, return it
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // If it's a full path, return it
        if (str_starts_with($value, '/')) {
            return $value;
        }

        // If it's a relative path, prepend storage path
        if (str_starts_with($value, 'hotels/')) {
            return asset('storage/' . $value);
        }

        // If it's a temporary path, return null
        if (str_contains($value, '\\') || str_contains($value, 'C:')) {
            return null;
        }

        return asset('storage/' . $value);
    }

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

    /**
     * Get the admin user who created this hotel
     */
    public function creator()
    {
        return $this->belongsTo(AdminUser::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the admin user who last updated this hotel
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
