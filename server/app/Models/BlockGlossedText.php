<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockGlossedText extends Model
{
    use HasFactory;

    public function block()
    {
        return $this->morphOne(Block::class, 'blockable');
    }
}
