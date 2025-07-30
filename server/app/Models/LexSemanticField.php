<?php

namespace App\Models;

use App\Models\LexEtyma;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\LexSemanticCategory;

class LexSemanticField extends Model {

    use CrudTrait;
    use HasTranslations;

	protected $table = 'lex_semantic_field';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $translatable = ['text'];

	public function semantic_category()
	{
		return $this->belongsTo(LexSemanticCategory::class);
	}

    public function lexicon()
    {
        return $this->hasOneThrough(LexLexicon::class, LexSemanticCategory::class, 'id', 'id', 'semantic_category_id', 'lexicon_id');
    }

    public function etyma()
    {
        return $this->belongsToMany(LexEtyma::class, 'lex_etyma_semantic_field', 'semantic_field_id', 'etyma_id')
            ->orderBy('order');
    }

    /** @deprecated use etyma() instead */
	public function etymas()
	{
		return $this->belongsToMany(LexEtyma::class, 'lex_etyma_semantic_field', 'semantic_field_id', 'etyma_id')
            ->orderBy('order');
	}

    public function getLexiconNameTextAttribute() {
        return $this->lexicon->name . ': ' . $this->text;
    }
}
