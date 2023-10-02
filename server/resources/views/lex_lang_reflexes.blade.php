@extends('layout')

@section('title') Indo-European Lexicon: {!! $language->name !!}  Reflex Index @stop

@section('content')


<h1>Indo-European Lexicon</h1>
<h2>{!! $language->name !!}  Reflex Index</h2>

<p>Below we list
{{count($display_reflexes)}}
unique
{!! $language->name !!}
reflex spellings (words and affixes) in an
alphabetic order suitable for the language family. Every spelling is linked to one
or more pages, each showing a Proto-Indo-European etymon from which the reflex is
derived along with other reflexes (in Old Irish or other languages) derived from
the same PIE etymon. A multi-morpheme reflex may, like English <i>werewolf</i>, be
derived from multiple PIE etyma; or a single spelling may, like English <i>bear</i>
or <i>lie</i>, represent multiple reflexes derived from different PIE etyma.</p>


<style>
    .hidden_row {
        display:none;
    }
</style>
<script>
    function search(query) {
        try {
            query = query.trim();
            var re = RegExp(query, 'i');
        } catch (e) {
            return;
        }

        var table = document.getElementById('reflexTable');
        table.remove();
        var table_body = table.getElementsByTagName('tbody')[0];
        table.removeChild(table_body);

        var rows = table_body.getElementsByTagName('tr');
        Array.prototype.forEach.call(rows, function(row) {
            var cells = row.getElementsByTagName('td');
            var reflex_text = cells[0].innerText.trim();
            var passes_filter = query==='' || re.test(reflex_text);
            if (passes_filter) {
                row.classList.remove('hidden_row');
            } else {
                row.classList.add('hidden_row');
            }
        })
        table.appendChild(table_body);

        document.getElementById('reflexTableContainer').appendChild(table);
    }

    function show_help() {
        document.getElementById('help_link').style.display = 'none';
        document.getElementById('help_link_hide').style.display = 'inline';
        document.getElementById('help').style.display = 'block';
    }

    function hide_help() {
        document.getElementById('help_link').style.display = 'inline';
        document.getElementById('help_link_hide').style.display = 'none';
        document.getElementById('help').style.display = 'none';
    }
</script>

<div id="reflexTableContainer" class="reflexTableContainer">
    <div style="border:solid 1px #999;border-radius:10px;padding:10px;margin:10px;">
        <p>
            Use the search bar to filter the reflexes displayed.
        </p>
        Search:
        <input type="text" name="query" value="" autocomplete="off" style="display:inline;float:none;" onkeyup="search(this.value);">
        <a id="help_link" onclick="show_help()">(tips for searching)</a>
        <a id="help_link_hide" onclick="hide_help()" style="display:none;">(hide tips)</a>
        <div id="help" style="display:none;">
            <hr>
            <h3>Search tips</h3>
            <p>
                 You can choose from three types of searches.
            </p>

            <p>
                <b>Default</b>: type in a string (i.e. list of characters) and the table will display only those words
                in which that string occurs — anywhere. For example, if you type in <code>sk</code>, the resulting list will include
                <i>ask, triskelion,</i> and <i>sky</i>, among others.
            </p>

            <p>
                <b>Simple Wildcards</b>: type in a string and use <code>.</code> to substitute for a single unspecified character, or
                <code>.*</code> to substitute for zero or more unspecified characters. Type <code>^</code> at the beginning of your
                string to say you only want words where the string begins the word, or <code>$</code> at the end of your
                string for words that end with your string. For example:

            <ul>
                <li><code>t.p</code> would return words like <i>tip, top, tape, stops,</i> and others.</li>
                <li><code>t.*p</code> would return words like those listed above, but also <i>footpad, tarp, and trample</i>.</li>
                <li><code>^t.p</code> would match words like <i>tip, top, and tops,</i> but not <i>stops</i>.</li>
                <li><code>sk$</code> would match words like <i>ask</i> and <i>task</i>, but not <i>asked</i> or <i>sky</i>.</li>
            </ul>
            </p>

            <p>
                <b>Regular Expressions</b>: type in any <b>regular expression</b> as commonly used in programming languages for string searches.
                See <a href="https://en.wikipedia.org/wiki/Regular_expression" target="_blank">here</a>
                for a basic explanation of the topic.
            </p>
        </div>
    </div>
    <table id="reflexTable" class="reflexTable" style="width:100%;">
        <caption>{!! $language->name !!} reflex index</caption>
      <thead>
        <tr>
            <th scope='col' style="width:25%;">Reflex</th>
            <th scope='col' style="width:75%;">Etyma</th>
        </tr>
      </thead>

      <tbody>
      @foreach($display_reflexes as $reflex)
        <tr class="searchable_reflex_row">
            <td>
                <span lang='{{$reflex['lang_attribute']}}'>{!! $reflex['reflex'] !!}</span>
            </td>
            <td>
                @foreach($reflex['etymas'] as $index => $temp_etyma)
                    <a title="{!! strip_tags($temp_etyma['gloss']) !!}" href='/lex/master/{{$temp_etyma['id']}}#{{$language['abbr']}}'>
                        <span class='Unicode' lang='ine'>{!! explode(":",explode(",",$temp_etyma['entry'])[0])[0] !!}</span>
                    </a>@if ($index+1 != count($reflex['etymas'])),@endif
                @endforeach

            </td>
        </tr>
      @endforeach
      </tbody>
    </table>
</div>
@stop


@section('menu')
    @include('menu_menu')
@stop
