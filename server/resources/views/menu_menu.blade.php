<ul class="side-nav">
    <li><a href="https://liberalarts.utexas.edu/lrc/">LRC Home</a></li>

    @if (Request::is('eieol*') || Request::is('guides/eieol_user'))
    <li class="open"><a href="/eieol">EIEOL Lessons</a>
        <ul class="no-bullet">
            <li
                @if (Request::is('eieol'))
                class="active"
                @endif
            ><a href="/eieol">Overview</a></li>
            <li
                @if (Request::is('guides/eieol_user'))
                class="active"
                @endif
            ><a href="/guides/eieol_user">How-To</a></li>
            <li><a href="/eieol">Lessons</a></li>
            <li><ul class="no-bullet">
                    <?php $lesson_menu = \App\Models\EieolSeries::where('published', '=', True)->get()->sortBy('menu_order'); ?>
                    @foreach($lesson_menu as $lesson_menu_item)
                    <li
                        @if (Request::is('eieol/'.$lesson_menu_item->slug.'*')
                            || Request::is('eieol_master_gloss/'.$lesson_menu_item->slug.'/*')
                            || Request::is('eieol_base_form_dictionary/'.$lesson_menu_item->slug.'/*')
                            || Request::is('eieol_english_meaning_index/'.$lesson_menu_item->slug.'/*'))
                        class="active"
                        @endif
                    ><a href="/eieol/{{$lesson_menu_item->slug}}">{{str_replace(' Online', '', $lesson_menu_item->menu_name)}}</a></li>
                        @endforeach
                </ul></li>
        </ul>
    </li>
    @else
    <li><a href="/eieol">EIEOL Lessons</a></li>
    @endif

    @if (Request::is('lex') || Request::is('lex/*') || Request::is('guides/lex_user'))
    <li class="open"><a href="/lex">IE Lexicon</a>
        <ul class="no-bullet">
            <li
                @if (Request::is('lex'))
                class="active"
                @endif
            ><a href="/lex">Overview</a></li>
            <li
                @if (Request::is('guides/lex_user'))
                class="active"
                @endif
            ><a href="/guides/lex_user">How-To</a></li>
            <li
                @if (Request::is('lex/master*'))
                class="active"
                @endif
            ><a href="/lex/master">Master Index</a></li>
            <li
                @if (Request::is('lex/languages*'))
                class="active"
                @endif
            ><a href="/lex/languages">Language Index</a></li>
            <li
                @if (Request::is('lex/semantic*'))
                class="active"
                @endif
            ><a href="/lex/semantic">Semantic Index</a></li>
        </ul>
    </li>
    @else
    <li><a href="/lex">IE Lexicon</a></li>
    @endif

    @foreach ($menu_items as $menu_item)
        <li><a href="{{$menu_item->link}}">{{$menu_item->name}}</a></li>
    @endforeach

</ul>

