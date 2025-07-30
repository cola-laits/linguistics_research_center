<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LexSemanticCategory extends Model
{

    use CrudTrait;
    use HasTranslations;

    protected $table = 'lex_semantic_category';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $translatable = ['text'];

    public function semantic_fields()
    {
        return $this->hasMany(LexSemanticField::class, 'semantic_category_id', 'id')->orderBy('number');
    }

    public function lexicon()
    {
        return $this->belongsTo(LexLexicon::class, 'lexicon_id');
    }

    public function getLexTextAttribute()
    {
        return $this->lexicon->name . ' - ' . $this->text;
    }
}
