<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-title">General</li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('user') }}'><i class='nav-icon la la-users'></i> Users</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('setting') }}'><i class='nav-icon la la-cog'></i> <span>Settings</span></a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('page') }}'><i class='nav-icon la la-file-alt'></i> Pages</a></li>

<li class="nav-title">Lexicon (<a href="help_lex"><i class='nav-icon la la-question-circle'></i>  help</a>)</li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('lex_language_family') }}'><i class='nav-icon la la-language'></i> Language Families</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('lex_language_sub_family') }}'><i class='nav-icon la la-language'></i> Language Sub Families</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('lex_language') }}'><i class='nav-icon la la-language'></i> Languages</a></li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('lex_etyma') }}'><i class='nav-icon la la-globe-europe'></i> Etyma</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('lex_reflex') }}'><i class='nav-icon la la-globe-europe'></i> Reflexes</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('lex_reflex_part_of_speech') }}'><i class='nav-icon la la-globe-europe'></i> Reflex > Part of Speech</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('lex_source') }}'><i class='nav-icon la la-book'></i> Sources</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('lex_semantic_category') }}'><i class='nav-icon la la-book'></i> Semantic Categories</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('lex_semantic_field') }}'><i class='nav-icon la la-book'></i> Semantic Fields</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('lex_part_of_speech') }}'><i class='nav-icon la la-book'></i> Parts of speech</a></li>

<li class="nav-title">EIEOL</li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('eieol_language') }}'><i class='nav-icon la la-question'></i> Languages</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('eieol_series') }}'><i class='nav-icon la la-question'></i> Series</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('eieol-lesson') }}'><i class='nav-icon la la-question'></i> Lessons</a></li>

<li class="nav-title">Books</li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('book') }}'><i class='nav-icon la la-book'></i> Books</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('book-section') }}'><i class='nav-icon la la-book-open'></i> Book sections</a></li>
