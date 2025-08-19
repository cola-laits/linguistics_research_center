{{-- This file is used for menu items by any Backpack v6 theme --}}
<x-backpack::menu-item title="Dashboard" icon="la la-home" :link="backpack_url('dashboard')" />

@can('manage_lexicon')
    <x-backpack::menu-dropdown title="Lexicon">
        <x-backpack::menu-dropdown-item title="Lexicons" icon="la la-book-open" :link="backpack_url('lex-lexicon')" />
        <x-backpack::menu-dropdown-item title="Language Families" icon="la la-language" :link="backpack_url('lex_language_family')" />
        <x-backpack::menu-dropdown-item title="Language Sub Families" icon="la la-language" :link="backpack_url('lex_language_sub_family')" />
        <x-backpack::menu-dropdown-item title="Languages" icon="la la-language" :link="backpack_url('lex_language')" />

        <x-backpack::menu-dropdown-item title="Etyma" icon="la la-globe-europe" :link="backpack_url('lex_etyma')" />
        <x-backpack::menu-dropdown-item title="Reflexes" icon="la la-globe-europe" :link="backpack_url('lex_reflex')" />
        <x-backpack::menu-dropdown-item title="Sources" icon="la la-book" :link="backpack_url('lex_source')" />
        <x-backpack::menu-dropdown-item title="Semantic Categories" icon="la la-book" :link="backpack_url('lex_semantic_category')" />
        <x-backpack::menu-dropdown-item title="Semantic Fields" icon="la la-book" :link="backpack_url('lex_semantic_field')" />
        <x-backpack::menu-dropdown-item title="Parts of speech" icon="la la-book" :link="backpack_url('lex_part_of_speech')" />
    </x-backpack::menu-dropdown>
@endcan
