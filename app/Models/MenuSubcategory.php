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

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_categories_id', 'menu_categories_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(\App\Models\Item::class, 'menu_subcategories_id', 'menu_subcategories_id');
    }
} 