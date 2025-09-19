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
        // FIXME Use replicate() for this, as per https://stackoverflow.com/questions/53408613/copy-record-with-all-relations-laravel-5-4 ?
        $gloss = DB::select('SELECT * FROM eieol_gloss WHERE id=?', [$this->id])[0];
        DB::insert('INSERT INTO eieol_gloss '.
            '(surface_form, contextual_gloss,comments,underlying_form,language_id,created_at,updated_at) '.
            ' VALUES (?,?,?,?,?,?,?)', [
            $gloss->surface_form,
            $gloss->contextual_gloss,
            $gloss->comments,
            $gloss->underlying_form,
            $gloss->language_id,
            $gloss->created_at,$gloss->updated_at
        ]);
        $new_gloss_id = DB::getPdo()->lastInsertId();

        $elements = DB::select('SELECT * FROM eieol_element WHERE gloss_id=?', [$this->id]);
        foreach ($elements as $element) {
            DB::insert('INSERT INTO eieol_element (gloss_id,part_of_speech,analysis,head_word_id,`order`,created_at,updated_at) '.
                'VALUES (?,?,?,?,?,?,?)', [
                $new_gloss_id,
                $element->part_of_speech,
                $element->analysis,
                $element->head_word_id,
                $element->order,
                $element->created_at,$element->updated_at
            ]);
        }

        return $new_gloss_id;
    }
}
