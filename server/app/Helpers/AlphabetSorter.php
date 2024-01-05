<?php

namespace App\Helpers;

class AlphabetSorter {
    protected $alphabet = [];
    protected $subs = [];
    public function __construct($substitutions, $custom_sort) {
        $this->arrayify_customsort($custom_sort);
        $this->arrayify_substitutions($substitutions);
    }

    protected function length_compare($a, $b){
        return mb_strlen($b,'UTF-8') <=> mb_strlen($a,'UTF-8');
    }

    protected function arrayify_customsort($custom_sort) {
        //Converts the custom sort into an array where each key is a character and each value is it's sort order.
        //It gets sorted by character length because each entry can be more than one character long, and we want longest first.
        //That way when we replace them in the sorter, we get the longest ones first, so Ž is not equal to Z and ll is not l.
        //create an array where each letter has a value equal to its comma separated position in the string

        $this->alphabet[' '] = 0;
        $this->alphabet[','] = 0;
        $this->alphabet['>'] = 0;
        $alphabet_groups = explode(',',$custom_sort);
        foreach ($alphabet_groups as $key => $group) {
            $values = explode('=',$group);
            foreach ($values as $value) {
                $this->alphabet[$value] = $key+1;
            }
        }

        //Now sort the array by length.
        uksort($this->alphabet, [$this, 'length_compare']);
    }

    protected function arrayify_substitutions($substitutions) {
        //convert substitutions into an array
        if ($substitutions == '') {
            return;
        }

        $substitutions_groups = explode(',',$substitutions);
        foreach ($substitutions_groups as $key => $group) {
            $values = explode('>',$group);
            $this->subs[$values[0]] = $values[1];
        }

        //Now sort the array by length.
        uksort($this->subs, [$this, 'length_compare']);
    }

    protected function sub_it($string,$substitutions) {
        //substitue any chars they may have defined.
        foreach ($substitutions as $key => $value) {
            $string = str_replace($key, $value, $string);

        }
        return $string;
    }

    protected function get_first_character_value($string) {
        //Used by alphabet_sort to get first character/sort value and remainder of a string.
        //It uses mb functions

        foreach($this->alphabet as $letter => $value) {
            if (mb_strpos($string, $letter,0,'UTF-8') === 0) {
                $letter_length = mb_strlen($letter,'UTF-8');
                return ['first' => $value, 'remainder' => mb_substr($string,$letter_length,Null,'UTF-8')];
            }
        }

        return [
            'first' => mb_substr($string,0,1,'UTF-8'),
            'remainder' => mb_substr($string,1,Null,'UTF-8')
        ];
    }

    public function alphabet_sorter($a, $b): int {
        //key_compare_func for uasort of gloss and dictionary.
        //because we expect unicode, we use multibyte string functions

        //since this is recursive, the first time we're comparing arrays.  Subsequent times is text.
        $a = $a['sortable_key'];
        $b = $b['sortable_key'];
        if (count($this->subs) > 0){
            $a = $this->sub_it($a,$this->subs);
            $b = $this->sub_it($b,$this->subs);
        }

        return $this->actual_sorter($a,$b);
    }

    protected function actual_sorter($a, $b): int {
        //key_compare_func for uasort of gloss and dictionary.
        //because we expect unicode, we use multibyte string functions

        if ($b == '') {
            return 1;
        }
        if ($a == '') {
            return -1;
        }

        $a_split = $this->get_first_character_value($a);
        $b_split = $this->get_first_character_value($b);

        if ($a_split['first'] > $b_split['first']) {
            return 1;
        }
        if ($b_split['first'] > $a_split['first']) {
            return -1;
        }
        //if you get here, they are equal, recurse
        return $this->actual_sorter($a_split['remainder'],$b_split['remainder']);
    }
}
