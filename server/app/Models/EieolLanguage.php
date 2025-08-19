<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use IntlChar;

class EieolLanguage extends Model {

	protected $table = 'eieol_language';

    protected $guarded = ['id'];

    public function getTinyMceCharmapConfig(): array {
        $value = [];

        $items = explode(',', $this->custom_keyboard_layout);
        foreach ($items as $item) {
            $item = trim($item, " '");
            if (str_starts_with($item, '\u')) {
                $unicodeHex = substr($item, 2); // Remove the \u prefix
                $item = mb_chr(hexdec($unicodeHex), 'UTF-8');
            }
            $desc = $item . " ::: (";
            $unicode_names = [];
            foreach (mb_str_split($item) as $char) {
                $unicode_names[] = IntlChar::charName($char);
            }
            $desc .= implode(" + ", $unicode_names);
            $desc .= ")";
            $value[] = [$item, $desc];
        }

        return $value;
    }
}
