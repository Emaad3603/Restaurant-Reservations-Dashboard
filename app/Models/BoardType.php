<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardType extends Model
{
    use HasFactory;

    protected $table = 'board_type_rules';
    protected $primaryKey = 'board_type_rules_id';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * Get the hotel that owns this board type
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'hotel_id');
    }

    /**
     * Get the company that owns this board type
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    /**
     * Get the restaurant that owns this board type
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'restaurants_id');
    }

    /**
     * Get the translations for this board type
     */
    public function translations()
    {
        return $this->hasMany(BoardTypeTranslation::class, 'board_types_id', 'board_types_id');
    }

    /**
     * Get the translation for this board type
     */
    public function translation()
    {
        return $this->hasOne(BoardTypeTranslation::class, 'board_types_id', 'board_types_id')
            ->withDefault(function ($translation, $boardType) {
                $translation->name = $boardType->name ?? 'Unnamed Board Type';
                $translation->description = '';
            });
    }
} 