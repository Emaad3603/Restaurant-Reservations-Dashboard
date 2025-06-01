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

    protected $guarded = [];

    /**
     * Get the admin user who created this menu item
     */
    public function creator()
    {
        return $this->belongsTo(AdminUser::class, 'created_by', 'admin_users_id');
    }

    /**
     * Get the admin user who last updated this menu item
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

    // Removed category() and restaurant() relationships
}
