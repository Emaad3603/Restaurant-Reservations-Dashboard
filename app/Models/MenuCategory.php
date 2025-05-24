<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuCategory extends Model
{
    use HasFactory;
    
    protected $table = 'menu_categories';
    protected $primaryKey = 'menu_categories_id';

    protected $fillable = [
        'label',
        'company_id',
        'restaurant_id',
        'background_url',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the restaurant that owns the menu category.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'restaurants_id');
    }

    /**
     * Get the menu items for the category.
     */
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'category_id', 'menu_categories_id');
    }
}
