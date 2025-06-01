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
        'menus_id',
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    protected $guarded = [];

    /**
     * Get the menu items for the category.
     */
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'menus_id', 'menus_id');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(MenuSubcategory::class, 'menu_categories_id', 'menu_categories_id');
    }

    /**
     * Get the admin user who created this menu category
     */
    public function creator()
    {
        return $this->belongsTo(AdminUser::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the admin user who last updated this menu category
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
