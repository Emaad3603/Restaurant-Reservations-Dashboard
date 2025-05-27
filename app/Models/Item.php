<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'items_id';
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(\App\Models\MenuCategory::class, 'menu_categories_id', 'menu_categories_id');
    }
} 