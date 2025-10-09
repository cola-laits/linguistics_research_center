<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EieolSeriesLanguage extends Model
{

    protected $table = 'eieol_series_language';

    public $timestamps = false;

    public function series(): BelongsTo
    {
        return $this->belongsTo(EieolSeries::class);
    }

}
