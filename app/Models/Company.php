<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';
    protected $primaryKey = 'company_id';
    public $timestamps = false;
    
    protected $fillable = [
        'company_name',
        'currency_id',
        'logo_url',
        'company_uuid'
    ];

    /**
     * Get the company's logo URL.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getLogoUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }

        // If it's already a full URL, return it
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // If it's a full path, return it
        if (str_starts_with($value, '/')) {
            return $value;
        }

        // If it's a relative path, prepend storage path
        if (str_starts_with($value, 'company-logos/')) {
            return asset('storage/' . $value);
        }

        // If it's a temporary path, return null
        if (str_contains($value, '\\') || str_contains($value, 'C:')) {
            return null;
        }

        return asset('storage/' . $value);
    }
} 