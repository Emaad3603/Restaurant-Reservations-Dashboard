<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $guarded = [];

    /**
     * Get the hotels created by this user
     */
    public function createdHotels()
    {
        return $this->hasMany(Hotel::class, 'created_by', 'id');
    }

    /**
     * Get the hotels updated by this user
     */
    public function updatedHotels()
    {
        return $this->hasMany(Hotel::class, 'updated_by', 'id');
    }

    /**
     * Get the restaurants created by this user
     */
    public function createdRestaurants()
    {
        return $this->hasMany(Restaurant::class, 'created_by', 'id');
    }

    /**
     * Get the restaurants updated by this user
     */
    public function updatedRestaurants()
    {
        return $this->hasMany(Restaurant::class, 'updated_by', 'id');
    }

    /**
     * Get the meal types created by this user
     */
    public function createdMealTypes()
    {
        return $this->hasMany(MealType::class, 'created_by', 'id');
    }

    /**
     * Get the meal types updated by this user
     */
    public function updatedMealTypes()
    {
        return $this->hasMany(MealType::class, 'updated_by', 'id');
    }

    /**
     * Get the menus created by this user
     */
    public function createdMenus()
    {
        return $this->hasMany(Menu::class, 'created_by', 'id');
    }

    /**
     * Get the menus updated by this user
     */
    public function updatedMenus()
    {
        return $this->hasMany(Menu::class, 'updated_by', 'id');
    }

    /**
     * Get the menu items created by this user
     */
    public function createdMenuItems()
    {
        return $this->hasMany(MenuItem::class, 'created_by', 'id');
    }

    /**
     * Get the menu items updated by this user
     */
    public function updatedMenuItems()
    {
        return $this->hasMany(MenuItem::class, 'updated_by', 'id');
    }

    /**
     * Get the menu categories created by this user
     */
    public function createdMenuCategories()
    {
        return $this->hasMany(MenuCategory::class, 'created_by', 'id');
    }

    /**
     * Get the menu categories updated by this user
     */
    public function updatedMenuCategories()
    {
        return $this->hasMany(MenuCategory::class, 'updated_by', 'id');
    }

    /**
     * Get the menu subcategories created by this user
     */
    public function createdMenuSubcategories()
    {
        return $this->hasMany(MenuSubcategory::class, 'created_by', 'id');
    }

    /**
     * Get the menu subcategories updated by this user
     */
    public function updatedMenuSubcategories()
    {
        return $this->hasMany(MenuSubcategory::class, 'updated_by', 'id');
    }

    /**
     * Get the pricing times created by this user
     */
    public function createdPricingTimes()
    {
        return $this->hasMany(RestaurantPricingTime::class, 'created_by', 'id');
    }

    /**
     * Get the pricing times updated by this user
     */
    public function updatedPricingTimes()
    {
        return $this->hasMany(RestaurantPricingTime::class, 'updated_by', 'id');
    }

    /**
     * Get the reservations created by this user
     */
    public function createdReservations()
    {
        return $this->hasMany(Reservation::class, 'created_by', 'id');
    }

    /**
     * Get the reservations canceled by this user
     */
    public function canceledReservations()
    {
        return $this->hasMany(Reservation::class, 'canceled_by', 'id');
    }
}
