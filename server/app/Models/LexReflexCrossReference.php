<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Translatable\HasTranslations;

class LexReflexCrossReference extends Pivot
{
    use HasTranslations;

    protected $table = 'lex_reflex_cross_reference';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $translatable = ['relationship'];
    public $incrementing = true;

    public function to_reflex() : BelongsTo
    {
        return $this->belongsTo(LexReflex::class, 'to_reflex_id');
    }

    public function from_reflex() : BelongsTo
    {
        return $this->belongsTo(LexReflex::class, 'from_reflex_id');
    }
}
