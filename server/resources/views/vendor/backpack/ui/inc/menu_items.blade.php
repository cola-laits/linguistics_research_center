{{-- This file is used for menu items by any Backpack v6 theme --}}
<x-backpack::menu-item title="Dashboard" icon="la la-home" :link="backpack_url('dashboard')" />

@canany(['manage_users','manage_settings','manage_pages','manage_menu'])
    <x-backpack::menu-dropdown title="General">
@can('manage_settings')
    <x-backpack::menu-dropdown-item title="Settings" icon="la la-cog" :link="backpack_url('setting')" />
@endcan
@can('manage_pages')
    <x-backpack::menu-dropdown-item title="Pages" icon="la la-file-alt" :link="backpack_url('page')" />
@endcan
    </x-backpack::menu-dropdown>
@endcanany

@can('manage_lexicon')
    <x-backpack::menu-dropdown title="Lexicon">
        <x-backpack::menu-dropdown-item title="help" icon="la la-question-circle" link="help_lex" />
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

@can('manage_eieol')
    <x-backpack::menu-dropdown title="EIEOL">
        <x-backpack::menu-dropdown-item title="Languages" icon="la la-question" :link="backpack_url('eieol_language')" />
        <x-backpack::menu-dropdown-item title="Series" icon="la la-question" :link="backpack_url('eieol_series')" />
        <x-backpack::menu-dropdown-item title="Lessons" icon="la la-question" :link="backpack_url('eieol-lesson')" />
        <x-backpack::menu-dropdown-item title="Full Lesson Editors" icon="la la-external-link-alt" link="/admin2/eieol_series#" />
        <x-backpack::menu-dropdown-item title="EIEOL Issues" icon="la la-external-link-alt" link="/admin2/issues" />
    </x-backpack::menu-dropdown>
@endcan
