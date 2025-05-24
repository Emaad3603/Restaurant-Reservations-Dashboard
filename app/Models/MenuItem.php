<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItem extends Model
{
    use HasFactory;

    protected $table = 'menus_items';
    protected $primaryKey = 'menus_items_id';

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id',
        'active'
    ];

    /**
     * Get the category that owns the menu item.
     */
    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'category_id', 'menu_categories_id');
    }

    /**
     * Get the restaurant through the category.
     */
    public function restaurant()
    {
        return $this->hasOneThrough(
            Restaurant::class,
            MenuCategory::class,
            'menu_categories_id', // Foreign key on the categories table...
            'restaurants_id', // Foreign key on the restaurants table...
            'category_id', // Local key on the menu_items table...
            'restaurant_id' // Local key on the categories table...
        );
    }
}
