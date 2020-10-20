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
        var table = document.getElementById('reflexTable');
        table.remove();

        query = query.trim();
        var rows = table.getElementsByClassName('searchable_reflex_row');
        Array.prototype.forEach.call(rows, function(row) {
            var cells = row.getElementsByTagName('td');
            var reflex_text = cells[0].innerText.trim();
            var etyma_text = cells[1].innerText.trim();
            if (passes_filter(query, reflex_text, etyma_text)) {
                row.classList.remove('hidden_row');
            } else {
                row.classList.add('hidden_row');
            }
        })

        document.getElementById('reflexTableContainer').appendChild(table);
    }

    function passes_filter(query, reflex, etyma) {
        if (query==="") {
            return true;
        }
        return reflex.indexOf(query) !== -1;
    }
</script>

<div id="reflexTableContainer" class="reflexTableContainer">
    <div style="border:solid 1px #999;border-radius:10px;padding:10px;margin:10px;">
        Search:
        <input type="text" name="query" value="" autocomplete="off" style="display:inline;float:none;" onkeyup="search(this.value);">
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
                <span class='{{$reflex['class_attribute']}}' lang='{{$reflex['lang_attribute']}}'>{!! $reflex['reflex'] !!}</span>
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
