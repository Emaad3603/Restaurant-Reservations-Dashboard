<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxTranslation extends Model
{
    use HasFactory;

    protected $table = 'taxes_translation';
    protected $guarded = [];

    /**
     * Get the tax that owns the translation.
     */
    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }
}
