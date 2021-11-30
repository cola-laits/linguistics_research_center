<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\EieolGloss
 *
 * @property int $id
 * @property string|null $surface_form
 * @property string|null $contextual_gloss
 * @property string|null $comments
 * @property string|null $underlying_form
 * @property int $language_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property int|null $glossed_text_id
 * @property int|null $order
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\EieolElement[] $elements
 * @property-read \App\EieolGlossedText|null $glossed_text
 * @property-read \App\EieolLanguage $language
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereContextualGloss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereGlossedTextId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereSurfaceForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereUnderlyingForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EieolGloss whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class EieolGloss extends Model {
	protected $table = 'eieol_gloss';

	public function glossed_text()
	{
		return $this->belongsTo('\App\EieolGlossedText', 'glossed_text_id');
	}

	public function elements()
	{
		return $this->hasMany('\App\EieolElement', 'gloss_id', 'id')->orderBy('order');
	}

	public function language()
	{
		return $this->belongsTo('\App\EieolLanguage');
	}

	/**
	 * Format the gloss in the way they are accustomed
	 *
	 * @return string
	 */
	public function getDisplayGloss()
	{
		//this should return something in the form
		//<span lang='cu' class='Cyrillic'>Ñ”Ñ�Ñ‚ÑŠ</span> <span style="white-space: nowrap">--</span> verb; 3rd person singular present of <nobr>&lt;<span lang='cu' class='Cyrillic'>Ñ¥Ñ�-, Ñ¥Ñ�Ð¼ÑŒ, Ñ¥Ñ�Ð¸</span>&gt;</nobr> be <nobr>--</nobr> <strong>is</strong>
		//part of which is handled by the getDisplayHeadWord function in the HeadWord model

		$string = "<span lang='" . $this->language->lang_attribute . "'>" .
				 $this->surface_form . '</span> <span style="white-space: nowrap">--</span> ';
		$i=0;
		foreach($this->elements as $element){
			$i++;
			if ($i != 1) {
				$string .= ' + ';
			}

			$string .= $element->part_of_speech . '; ' .
						$element->analysis . ' ' .
						$element->head_word->getDisplayHeadWord();
		}
		$string .= ' <span style="white-space: nowrap">--</span> <strong>' . $this->contextual_gloss . '</strong>';

		if ($this->comments) {
			$string .= ' # ' . $this->comments;
		}

		if ($this->underlying_form) {
			$string .= ' <br/><span lang="' . $this->language->lang_attribute . '" style="margin-left:10px;">(' . $this->underlying_form . ')</span>';
		}

		return $string;
	}

	/** Deep copy a gloss and its elements. */
	public function deepCopy() {
	    // clunky; refactor later
        // Use replicate() for this, as per https://stackoverflow.com/questions/53408613/copy-record-with-all-relations-laravel-5-4 ?
        $gloss = \DB::select('SELECT * FROM eieol_gloss WHERE id=?', [$this->id])[0];
        \DB::insert('INSERT INTO eieol_gloss '.
            '(surface_form, contextual_gloss,comments,underlying_form,language_id,created_at,updated_at,created_by,updated_by) '.
            ' VALUES (?,?,?,?,?,?,?,?,?)', [
            $gloss->surface_form,
            $gloss->contextual_gloss,
            $gloss->comments,
            $gloss->underlying_form,
            $gloss->language_id,
            $gloss->created_at,$gloss->updated_at,$gloss->created_by,$gloss->updated_by
        ]);
        $new_gloss_id = \DB::getPdo()->lastInsertId();

        $elements = \DB::select('SELECT * FROM eieol_element WHERE gloss_id=?', [$this->id]);
        foreach ($elements as $element) {
            \DB::insert('INSERT INTO eieol_element (gloss_id,part_of_speech,analysis,head_word_id,`order`,created_at,updated_at,created_by,updated_by) '.
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
