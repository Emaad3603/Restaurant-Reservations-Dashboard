<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItem extends Model
{
    use HasFactory;

    protected $table = 'menus_items';
    protected $primaryKey = 'menus_items_id';
    public $timestamps = false;

    protected $fillable = [
        'items_id',
        'price',
        'currencies_id',
        'created_by',
        'updated_by',
        'menus_id'
    ];

    // Removed category() and restaurant() relationships
}
