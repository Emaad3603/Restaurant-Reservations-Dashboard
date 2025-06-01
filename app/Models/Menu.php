<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'menus';
    protected $primaryKey = 'menus_id';
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    protected $guarded = [];

    public function menuItems()
    {
        return $this->hasMany(\App\Models\MenuItem::class, 'menus_id', 'menus_id');
    }

    public function items()
    {
        return $this->hasManyThrough(
            \App\Models\Item::class,
            \App\Models\MenuItem::class,
            'menus_id', // Foreign key on menus_items
            'items_id', // Foreign key on items
            'menus_id', // Local key on menus
            'items_id'  // Local key on menus_items
        );
    }

    public function categories(): HasMany
    {
        return $this->hasMany(MenuCategory::class, 'menus_id', 'menus_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the admin user who created this menu
     */
    public function creator()
    {
        return $this->belongsTo(AdminUser::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the admin user who last updated this menu
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