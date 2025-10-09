<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class LexLexicon extends Model
{
    use HasTranslations;

    protected $table = 'lex_lexicon';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $translatable = ['protolang_name', 'protolanguage_page_content', 'landing_page_content'];

    public function etyma(): HasMany
    {
        return $this->hasMany(LexEtyma::class, 'lexicon_id')
            ->orderBy('entry');
    }

    public function semantic_categories(): HasMany
    {
        return $this->hasMany(LexSemanticCategory::class, 'lexicon_id')
            ->orderBy('number');
    }

    public function language_families(): HasMany
    {
        return $this->hasMany(LexLanguageFamily::class, 'lexicon_id')
            ->orderBy('order');
    }

    public function getViewerLangsArray()
    {
        if ($this->viewer_lang_options == null) {
            return [];
        }
        return str($this->viewer_lang_options)->explode(',')->map(function ($lang_code) {
            return trim($lang_code);
        });
    }

    public static function getDisplayTextViewerLang($lang_code)
    {
        $lang_names = ['en' => 'English', 'es' => 'Español'];
        return $lang_names[$lang_code] ?? ('Unknown: ' . $lang_code);
    }

    public function getDataColumns()
    {
        $column_descs = [
            (object)['display_name' => 'Meaning', 'name' => 'meaning'],
            (object)['display_name' => 'Semantic Tag', 'name' => 'semantic_tag'],
            (object)['display_name' => 'Etymon', 'name' => 'etymon'],
            (object)['display_name' => 'Language', 'name' => 'language'],
            (object)['display_name' => 'Part of Speech', 'name' => 'part_of_speech'],
        ];

        // FIXME make this database-driven at some point
        if ($this->slug === 'semitilex') {
            $column_descs [] = (object)['display_name' => 'pS Root', 'name' => 'root'];
            $column_descs [] = (object)['display_name' => 'Verb Root', 'name' => 'verb_root'];
            $column_descs [] = (object)['display_name' => 'Verb Root Script', 'name' => 'verb_root_script'];
            $column_descs [] = (object)['display_name' => 'Script', 'name' => 'script'];
            $column_descs [] = (object)['display_name' => 'Transliteration', 'name' => 'transliteration'];
            $column_descs [] = (object)['display_name' => 'Sem Normalization', 'name' => 'sem_normalization'];
            $column_descs [] = (object)['display_name' => 'IPA Singular', 'name' => 'ipa_singular'];
            $column_descs [] = (object)['display_name' => 'Gender', 'name' => 'gender'];
            $column_descs [] = (object)['display_name' => 'Tag', 'name' => 'tag'];
            $column_descs [] = (object)['display_name' => 'Donor Language', 'name' => 'donor_language'];
            $column_descs [] = (object)['display_name' => 'Donor Word', 'name' => 'donor_word'];
            $column_descs [] = (object)['display_name' => 'Data Source', 'name' => 'data_source'];
            $column_descs [] = (object)['display_name' => 'Notes', 'name' => 'notes'];
            $column_descs [] = (object)['display_name' => 'f Markedness', 'name' => 'f_markedness'];
            $column_descs [] = (object)['display_name' => 'pS Pattern', 'name' => 'ps_pattern'];
            $column_descs [] = (object)['display_name' => 'Sem Normalization Pl', 'name' => 'sem_normalization_pl'];
            $column_descs [] = (object)['display_name' => 'IPA Plural', 'name' => 'ipa_plural'];
            $column_descs [] = (object)['display_name' => 'pS Plural Pattern', 'name' => 'ps_plural_pattern'];
            $column_descs [] = (object)['display_name' => 'pS Plural Suffix', 'name' => 'ps_plural_suffix'];
            $column_descs [] = (object)['display_name' => 'Deptotic', 'name' => 'deptotic'];
            $column_descs [] = (object)['display_name' => 'Prefix Conj 1', 'name' => 'prefix_conj_1'];
            $column_descs [] = (object)['display_name' => 'Prefix Conj 1 IPA', 'name' => 'prefix_conj_1_ipa'];
            $column_descs [] = (object)['display_name' => 'Prefix Conj 2', 'name' => 'prefix_conj_2'];
            $column_descs [] = (object)['display_name' => 'Prefix Conj 2 IPA', 'name' => 'prefix_conj_2_ipa'];
            $column_descs [] = (object)['display_name' => 'Suffix Conj', 'name' => 'suffix_conj'];
            $column_descs [] = (object)['display_name' => 'Suffix Conj IPA', 'name' => 'suffix_conj_ipa'];
            $column_descs [] = (object)['display_name' => 'Infinitive', 'name' => 'infinitive'];
            $column_descs [] = (object)['display_name' => 'Infinitive IPA', 'name' => 'infinitive_ipa'];
            $column_descs [] = (object)['display_name' => 'Participle', 'name' => 'participle'];
            $column_descs [] = (object)['display_name' => 'Participle IPA', 'name' => 'participle_ipa'];
            $column_descs [] = (object)['display_name' => 'PC Thematic Vowel', 'name' => 'pc_thematic_vowel'];
            $column_descs [] = (object)['display_name' => 'SC Thematic Vowel', 'name' => 'sc_thematic_vowel'];
            $column_descs [] = (object)['display_name' => 'Stem', 'name' => 'stem'];
            $column_descs [] = (object)['display_name' => 'Complement', 'name' => 'complement'];
        }
        if ($this->slug === 'mayalex' || str_starts_with($this->slug, 'mayalex_')) {
            $column_descs [] = (object)['display_name' => 'Headword (Kaufman spelling)', 'name' => 'kaufman_spelling'];
            $column_descs [] = (object)['display_name' => 'Headword (practical orthography)', 'name' => 'practical_orthography'];
            $column_descs [] = (object)['display_name' => 'Headword (IPA)', 'name' => 'ipa_spelling'];
            $column_descs [] = (object)['display_name' => 'Meaning (English)', 'name' => 'english_definition'];
            $column_descs [] = (object)['display_name' => 'Meaning (Spanish)', 'name' => 'spanish_definition'];
            $column_descs [] = (object)['display_name' => 'Full Original Entry', 'name' => 'full_original_entry'];
            $column_descs [] = (object)['display_name' => 'Alternate forms/spellings', 'name' => 'alternate_forms'];
            $column_descs [] = (object)['display_name' => 'Manuscript Page Number', 'name' => 'page_number'];
            $column_descs [] = (object)['display_name' => 'Source', 'name' => 'source'];
            $column_descs [] = (object)['display_name' => 'Other', 'name' => 'other'];
            $column_descs [] = (object)['display_name' => 'Editors', 'name' => 'editors'];
        }

        return $column_descs;
    }
}
