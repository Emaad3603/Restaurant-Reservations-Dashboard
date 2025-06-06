<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    protected $table = 'companies';
    protected $primaryKey = 'company_id';
    public $timestamps = false;
    
    protected $fillable = [
        'company_name',
        'currency_id',
        'company_uuid',
        'logo_url'
    ];

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'currencies_id');
    }
} 