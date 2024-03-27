<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    public function blockable()
    {
        return $this->morphTo();
    }

    public function blockPage()
    {
        return $this->belongsTo(BlockPage::class);
    }
}
