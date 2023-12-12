<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class LexLexicon extends Model
{
    use CrudTrait;
    use HasTranslations;

    protected $table = 'lex_lexicon';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $translatable = ['protolang_name', 'description'];

    public function etyma() {
        return $this->hasMany(LexEtyma::class, 'lexicon_id')
            ->orderBy('entry');
    }

    public function semantic_categories() {
        return $this->hasMany(LexSemanticCategory::class, 'lexicon_id')
            ->orderBy('number');
    }

    public function language_families() {
        return $this->hasMany(LexLanguageFamily::class, 'lexicon_id')
            ->orderBy('order');
    }

    public function getViewerLangsArray() {
        if ($this->viewer_lang_options == null) {
            return [];
        }
        return str($this->viewer_lang_options)->explode(',')->map(function($lang_code) {
            return trim($lang_code);
        });
    }

    public static function getDisplayTextViewerLang($lang_code) {
        $lang_names = ['en'=>'English','es'=>'Español'];
        return $lang_names[$lang_code] ?? ('Unknown: '.$lang_code);
    }
}
