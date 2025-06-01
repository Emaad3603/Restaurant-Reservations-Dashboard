<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuSubcategory extends Model
{
    protected $fillable = [
        'menu_categories_id',
        'label',
        'background_url',
        'company_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    protected $guarded = [];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_categories_id', 'menu_categories_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(\App\Models\Item::class, 'menu_subcategories_id', 'menu_subcategories_id');
    }

    /**
     * Get the admin user who created this menu subcategory
     */
    public function creator()
    {
        return $this->belongsTo(AdminUser::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the admin user who last updated this menu subcategory
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