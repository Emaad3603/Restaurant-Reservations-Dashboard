<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';
    protected $primaryKey = 'currencies_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'currency_code',
        'symbol',
        'exchange_rate'
    ];

    public function companies()
    {
        return $this->hasMany(Company::class, 'currency_id', 'currencies_id');
    }
} 