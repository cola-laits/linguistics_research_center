<?php

namespace App\Models;

use DB;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\EieolGlossedText;
use App\Models\EieolElement;
use App\Models\EieolLanguage;
use Illuminate\Support\Carbon;

/**
 * App\Models\EieolGloss
 *
 * @property int $id
 * @property string|null $surface_form
 * @property string|null $contextual_gloss
 * @property string|null $comments
 * @property string|null $underlying_form
 * @property int $language_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property int|null $glossed_text_id
 * @property int|null $order
 * @property-read Collection|\App\Models\EieolElement[] $elements
 * @property-read int|null $elements_count
 * @property-read \App\Models\EieolGlossedText|null $glossed_text
 * @property-read \App\Models\EieolLanguage $language
 * @method static Builder|EieolGloss newModelQuery()
 * @method static Builder|EieolGloss newQuery()
 * @method static Builder|EieolGloss query()
 * @method static Builder|EieolGloss whereComments($value)
 * @method static Builder|EieolGloss whereContextualGloss($value)
 * @method static Builder|EieolGloss whereCreatedAt($value)
 * @method static Builder|EieolGloss whereCreatedBy($value)
 * @method static Builder|EieolGloss whereGlossedTextId($value)
 * @method static Builder|EieolGloss whereId($value)
 * @method static Builder|EieolGloss whereLanguageId($value)
 * @method static Builder|EieolGloss whereOrder($value)
 * @method static Builder|EieolGloss whereSurfaceForm($value)
 * @method static Builder|EieolGloss whereUnderlyingForm($value)
 * @method static Builder|EieolGloss whereUpdatedAt($value)
 * @method static Builder|EieolGloss whereUpdatedBy($value)
 * @mixin Eloquent
 */
class EieolGloss extends Model {
	protected $table = 'eieol_gloss';

	public function glossed_text()
	{
		return $this->belongsTo(EieolGlossedText::class, 'glossed_text_id');
	}

	public function elements()
	{
		return $this->hasMany(EieolElement::class, 'gloss_id', 'id')->orderBy('order');
	}

	public function language()
	{
		return $this->belongsTo(EieolLanguage::class);
	}

	/** Deep copy a gloss and its elements. */
	public function deepCopy() {
	    // clunky; refactor later
        // Use replicate() for this, as per https://stackoverflow.com/questions/53408613/copy-record-with-all-relations-laravel-5-4 ?
        $gloss = DB::select('SELECT * FROM eieol_gloss WHERE id=?', [$this->id])[0];
        DB::insert('INSERT INTO eieol_gloss '.
            '(surface_form, contextual_gloss,comments,underlying_form,language_id,created_at,updated_at,created_by,updated_by) '.
            ' VALUES (?,?,?,?,?,?,?,?,?)', [
            $gloss->surface_form,
            $gloss->contextual_gloss,
            $gloss->comments,
            $gloss->underlying_form,
            $gloss->language_id,
            $gloss->created_at,$gloss->updated_at,$gloss->created_by,$gloss->updated_by
        ]);
        $new_gloss_id = DB::getPdo()->lastInsertId();

        $elements = DB::select('SELECT * FROM eieol_element WHERE gloss_id=?', [$this->id]);
        foreach ($elements as $element) {
            DB::insert('INSERT INTO eieol_element (gloss_id,part_of_speech,analysis,head_word_id,`order`,created_at,updated_at,created_by,updated_by) '.
                'VALUES (?,?,?,?,?,?,?,?,?)', [
                $new_gloss_id,
                $element->part_of_speech,
                $element->analysis,
                $element->head_word_id,
                $element->order,
                $element->created_at,$element->updated_at,$element->created_by,$element->updated_by
            ]);
        }

        return $new_gloss_id;
    }
}
