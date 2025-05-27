<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuCategory extends Model
{
    use HasFactory;
    
    protected $table = 'menu_categories';
    protected $primaryKey = 'menu_categories_id';

    protected $fillable = [
        'label',
        'company_id',
        'background_url',
        'created_by',
        'updated_by',
        'menu_id',
        'name',
        'description',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    /**
     * Get the menu items for the category.
     */
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'category_id', 'menu_categories_id');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(MenuSubcategory::class, 'category_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'category_id');
    }
}
