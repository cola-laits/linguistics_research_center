<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EieolSeriesLanguage extends Model
{

    protected $table = 'eieol_series_language';

    public $timestamps = false;

    public function series()
    {
        return $this->belongsTo(EieolSeries::class);
    }

}
