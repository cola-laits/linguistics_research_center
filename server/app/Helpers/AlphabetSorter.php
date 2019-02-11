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
        //key_compare_func for uksort of arrayify_customsort.  We want longer strings first
        if (mb_strlen($a,'UTF-8') >= mb_strlen($b,'UTF-8')) {
            return -1;
        } else {
            return 1;
        }
    }

    protected function arrayify_customsort($custom_sort) {
        //Converts the custom sort into an array where each key is a character and each value is it's sort order.
        //It gets sorted by character length because each entry can be more than one character long, and we want longest first.
        //That way when we replace them in the sorter, we get the longest ones first, so Ž is not equal to Z and ll is not l.
        //create an array where each letter has a value equal to its comma separated position in the string
        $alphabet_groups = explode(',',$custom_sort);
        foreach ($alphabet_groups as $key => $group) {
            //print $key . ' ' . $group . ' ' . mb_strlen($group,'UTF-8') . '<br/>';
            $values = explode('=',$group);
            foreach ($values as $value) {
                $this->alphabet[$value] = str_pad(($key+1), 3, '0', STR_PAD_LEFT); //pad with zeros
            }
        }

        //Now sort the array by length.
        uksort($this->alphabet, [$this, 'length_compare']);
    } //arrayify_customsort

    protected function arrayify_substitutions($substitutions) {
        //convert substitutions into an array
        if ($substitutions == '') {
            return;
        }

        $substitutions_groups = explode(',',$substitutions);
        foreach ($substitutions_groups as $key => $group) {
            //print $key . ' ' . $group . ' ' . mb_strlen($group,'UTF-8') . '<br/>';
            $values = explode('>',$group);
            $this->subs[$values[0]] = $values[1];
        }

        //Now sort the array by length.
        uksort($this->subs, [$this, 'length_compare']);
    } //arrayify_substitutions

    protected function sub_it($string,$substitutions) {
        //substitue any chars they may have defined.
        foreach ($substitutions as $key => $value) {
            $string = str_replace($key, $value, $string);

        }
        return $string;
    } //sub_it

    protected function get_first_character_value($string) {
        //Used by alphabet_sort to get first character/sort value and remainder of a string.
        //It uses mb functions
        //because this function is passed by uasort, we pass the alphabet in a global
        //check for blank, comma or >
        $treat_as_blank = array(' ', ',', '>'); //the > is at the end of head words
        foreach($treat_as_blank as $letter) {
            if (mb_strpos($string, $letter,0,'UTF-8') === 0) {
                $letter_length = mb_strlen($letter,'UTF-8');
                return array('first' => 0, 'remainder' => mb_substr($string,$letter_length,Null,'UTF-8'));
            }
        }

        foreach($this->alphabet as $letter => $value) {
            //print $letter . '/' . mb_strpos($string, $letter,0,'UTF-8') . ' ';
            if (mb_strpos($string, $letter,0,'UTF-8') === 0) {
                $letter_length = mb_strlen($letter,'UTF-8');
                //print $letter . ' ' . $value . ' ' . $letter_length  . ' ' . mb_substr($string,$letter_length,Null,'UTF-8') . '<br/>';
                return array('first' => $value, 'remainder' => mb_substr($string,$letter_length,Null,'UTF-8'));
            }
        }

        //if you get here, you didn't match
        throw new \Exception("couldn't find a character in " . $string);
    } //get_first_character_value

    public function alphabet_sorter($a, $b) {
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
    } //alphabet_sorter

    protected function actual_sorter($a, $b) {
        //key_compare_func for uasort of gloss and dictionary.
        //because we expect unicode, we use multibyte string functions

        //print '<xmp>' . $a . ' ' . mb_strlen($a,'UTF-8') . '<> ' . $b . ' ' . mb_strlen($b,'UTF-8') . '</xmp>';
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
    } //alphabet_sorter

    public static function test() {
        $sorter = new AlphabetSorter("'>,->,´>,¨>,`>,῀>,᾽>,῾>,῎>,῍>,῞>,῝>,῏>,῟>,΅>,ι>,ᾼ>Α,ᾈ>Α,ᾉ>Α,ᾊ>Α,ᾋ>Α,ᾌ>Α,ᾍ>Α,ᾎ>Α,ᾏ>Α,ᾳ>α,ᾲ>α,ᾴ>α,ᾷ>α,ᾀ>α,ᾁ>α,ᾂ>α,ᾃ>α,ᾄ>α,ᾅ>α,ᾆ>α,ᾇ>α,Ά>Α,Ἀ>Α,Ἁ>Α,Ὰ>Α,Ᾰ>Α,Ᾱ>Α,Ἂ>Α,Ἃ>Α,Ἄ>Α,Ἅ>Α,Ἆ>Α,Ἇ>Α,ά>α,ἀ>α,ἁ>α,ὰ>α,ᾰ>α,ᾱ>α,ᾶ>α,ἂ>α,ἃ>α,ἄ>α,ἅ>α,ἆ>α,ἇ>α,ῌ>Η,ᾘ>Η,ᾙ>Η,ᾚ>Η,ᾛ>Η,ᾜ>Η,ᾝ>Η,ᾞ>Η,ᾟ>Η,ῃ>η,ᾐ>η,ᾑ>η,ῂ>η,ῄ>η,ῇ>η,ᾒ>η,ᾓ>η,ᾔ>η,ᾕ>η,ᾖ>η,ᾗ>η,Ή>Η,Ἠ>Η,Ἡ>Η,Ὴ>Η,Ἢ>Η,Ἣ>Η,Ἤ>Η,Ἥ>Η,Ἦ>Η,Ἧ>Η,ή>η,ἠ>η,ἡ>η,ὴ>η,ῆ>η,ἢ>η,ἣ>η,ἤ>η,ἥ>η,ἦ>η,ἧ>η,ῆ́>η,ῼ>Ω,ᾨ>Ω,ᾩ>Ω,ᾪ>Ω,ᾫ>Ω,ᾬ>Ω,ᾭ>Ω,ᾮ>Ω,ᾯ>Ω,ῳ>ω,ᾠ>ω,ᾡ>ω,ῲ>ω,ῴ>ω,ῷ>ω,ᾢ>ω,ᾣ>ω,ᾤ>ω,ᾥ>ω,ᾦ>ω,ᾧ>ω,Ώ>Ω,Ὠ>Ω,Ὡ>Ω,Ὼ>Ω,Ὢ>Ω,Ὣ>Ω,Ὤ>Ω,Ὥ>Ω,Ὦ>Ω,Ὧ>Ω,ώ>ω,ὠ>ω,ὡ>ω,ὼ>ω,ῶ>ω,ὢ>ω,ὣ>ω,ὤ>ω,ὥ>ω,ὦ>ω,ὧ>ω,Έ>Ε,Ἐ>Ε,Ἑ>Ε,Ὲ>Ε,Ἒ>Ε,Ἓ>Ε,Ἔ>Ε,Ἕ>Ε,έ>ε,ἐ>ε,ἑ>ε,ὲ>ε,ἒ>ε,ἓ>ε,ἔ>ε,ἕ>ε,Ί>Ι,Ἰ>Ι,Ἱ>Ι,Ὶ>Ι,Ῐ>Ι,Ῑ>Ι,Ϊ>Ι,Ἲ>Ι,Ἳ>Ι,Ἴ>Ι,Ἵ>Ι,Ἶ>Ι,Ἷ>Ι,ί>ι,ἰ>ι,ἱ>ι,ὶ>ι,ῐ>ι,ῑ>ι,ῖ>ι,ϊ>ι,ΐ>ι,ἲ>ι,ἳ>ι,ἴ>ι,ἵ>ι,ἶ>ι,ἷ>ι,ῒ>ι,ΐ>ι,Ό>Ο,Ὀ>Ο,Ὁ>Ο,Ὸ>Ο,Ὂ>Ο,Ὃ>Ο,Ὄ>Ο,Ὅ>Ο,ό>ο,ὀ>ο,ὁ>ο,ὸ>ο,ὂ>ο,ὃ>ο,ὄ>ο,ὅ>ο,Ύ>Υ,Ϋ>Υ,Ὑ>Υ,Ὺ>Υ,Ῠ>Υ,Ῡ>Υ,Ὓ>Υ,Ὕ>Υ,Ὗ>Υ,ύ>υ,ὐ>υ,ὑ>υ,ὺ>υ,ῠ>υ,ῡ>υ,ῦ>υ,ϋ>υ,ΰ>υ,ὒ>υ,ὓ>υ,ὔ>υ,ὕ>υ,ὖ>υ,ὗ>υ,ῢ>υ,ΰ>υ,ῧ>υ,Ῥ>Ρ,ῤ>ρ,ῥ>ρ,ϱ>ρ,ϵ>ε,ϑ>θ,ϰ>κ,ϗ>και,ϖ>π,ς>σ,Ϲ>Σ,ϲ>σ,τ́>τ,ϒ>Υ,ϓ>Υ,ϔ>Υ,ϕ>φ",
                    "Α=α,Β=β,Γ=γ,Δ=δ,Ε=ε,Ϝ=ϝ=Ͷ=ͷ=Ϛ=ϛ,Ζ=ζ,Η=η,Θ=θ,Ι=ι,Κ=κ,Λ=λ,Μ=μ,Ν=ν,Ξ=ξ,Ο=ο,Π=π,Ϻ=ϻ,Ϙ=Ϟ=ϙ=ϟ,Ρ=ρ,Σ=σ,Τ=τ,Υ=υ,Φ=φ,Χ=χ,Ψ=ψ,Ω=ω,Ϡ=ϡ=Ͳ=ͳ");
        $words = [
            ['sortable_key'=>"ᾴ"],
            ['sortable_key'=>"β"],
            ['sortable_key'=>"ἅ"],
            ['sortable_key'=>"αἱ"],
            ['sortable_key'=>"αἱ"],
            ['sortable_key'=>"ἃ"],
            ['sortable_key'=>"ἀγαλλιᾶσθε"]
            ];
        uasort($words,[$sorter, 'alphabet_sorter']);

        foreach ($words as $key=>$val) {
            print $val['sortable_key'] . "<br>";
        }
        print json_encode($words);
    }
}
