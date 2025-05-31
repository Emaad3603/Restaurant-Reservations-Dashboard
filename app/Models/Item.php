<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'items_id';
    public $timestamps = false;

    protected $fillable = [
        'label',
        'company_id',
        'menu_categories_id',
        'menu_subcategories_id',
        'created_by',
        'created_at',
    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\MenuCategory::class, 'menu_categories_id', 'menu_categories_id');
    }
} 