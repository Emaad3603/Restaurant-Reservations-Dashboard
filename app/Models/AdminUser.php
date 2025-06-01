<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admin_users';
    protected $primaryKey = 'admin_users_id';
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the admin privilege for this user.
     */
    public function privilege()
    {
        return $this->hasOne(AdminPrivilege::class, 'admin_users_id', 'admin_users_id');
    }

    /**
     * Get the hotels created by this admin
     */
    public function createdHotels()
    {
        return $this->hasMany(Hotel::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the hotels updated by this admin
     */
    public function updatedHotels()
    {
        return $this->hasMany(Hotel::class, 'updated_by', 'admin_users_id');
    }

    /**
     * Get the restaurants created by this admin
     */
    public function createdRestaurants()
    {
        return $this->hasMany(Restaurant::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the restaurants updated by this admin
     */
    public function updatedRestaurants()
    {
        return $this->hasMany(Restaurant::class, 'updated_by', 'admin_users_id');
    }

    /**
     * Get the meal types created by this admin
     */
    public function createdMealTypes()
    {
        return $this->hasMany(MealType::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the meal types updated by this admin
     */
    public function updatedMealTypes()
    {
        return $this->hasMany(MealType::class, 'updated_by', 'admin_users_id');
    }

    /**
     * Get the menus created by this admin
     */
    public function createdMenus()
    {
        return $this->hasMany(Menu::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the menus updated by this admin
     */
    public function updatedMenus()
    {
        return $this->hasMany(Menu::class, 'updated_by', 'admin_users_id');
    }

    /**
     * Get the menu items created by this admin
     */
    public function createdMenuItems()
    {
        return $this->hasMany(MenuItem::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the menu items updated by this admin
     */
    public function updatedMenuItems()
    {
        return $this->hasMany(MenuItem::class, 'updated_by', 'admin_users_id');
    }

    /**
     * Get the menu categories created by this admin
     */
    public function createdMenuCategories()
    {
        return $this->hasMany(MenuCategory::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the menu categories updated by this admin
     */
    public function updatedMenuCategories()
    {
        return $this->hasMany(MenuCategory::class, 'updated_by', 'admin_users_id');
    }

    /**
     * Get the menu subcategories created by this admin
     */
    public function createdMenuSubcategories()
    {
        return $this->hasMany(MenuSubcategory::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the menu subcategories updated by this admin
     */
    public function updatedMenuSubcategories()
    {
        return $this->hasMany(MenuSubcategory::class, 'updated_by', 'admin_users_id');
    }

    /**
     * Get the pricing times created by this admin
     */
    public function createdPricingTimes()
    {
        return $this->hasMany(RestaurantPricingTime::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the pricing times updated by this admin
     */
    public function updatedPricingTimes()
    {
        return $this->hasMany(RestaurantPricingTime::class, 'updated_by', 'admin_users_id');
    }

    /**
     * Get the reservations created by this admin
     */
    public function createdReservations()
    {
        return $this->hasMany(Reservation::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the reservations canceled by this admin
     */
    public function canceledReservations()
    {
        return $this->hasMany(Reservation::class, 'canceled_by', 'admin_users_id');
    }
}
