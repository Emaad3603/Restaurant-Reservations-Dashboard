<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardTypeTranslation extends Model
{
    use HasFactory;

    protected $table = 'board_types_translation';
    protected $primaryKey = 'board_types_translation_id';
    protected $guarded = [];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'board_types_id' => 'integer',
    ];

    /**
     * Get the board type that owns the translation.
     */
    public function boardType()
    {
        return $this->belongsTo(BoardType::class, 'board_types_id', 'board_types_id');
    }
} 