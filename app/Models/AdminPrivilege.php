<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPrivilege extends Model
{
    use HasFactory;

    protected $table = 'admin_privileges';
    protected $guarded = [];

    public $timestamps = false;

    /**
     * Get the admin user that owns the privilege.
     */
    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class, 'admin_users_id', 'admin_users_id');
    }
}
