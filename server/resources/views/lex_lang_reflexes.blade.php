@extends('layout')

@section('title') Indo-European Lexicon: {!! $language->name !!}  Reflex Index @stop

@section('content')
<script type="text/javascript">
    $(document).ready(function(){
        $('.reflexTable').DataTable({
            "bJQueryUI": true,
            "iDisplayLength": 10,
            "sPaginationType": "full_numbers",
            "sDom": '<"H"fl>t<"F"ip>',
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "bSort" : false,
             "fnInitComplete": function(oSettings, json) {
                $('.loading').toggle();
                $('.reflexTable').toggle();
             }
        });
    });//document ready
</script>


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



<div class="loading">
    <img src="/images/ajax_loader_blue_512.gif" alt="Loading">
</div>

<div class = "reflexTableContainer">
    <table class="reflexTable">
        <caption>{!! $language->name !!} reflex index</caption>
      <thead>
        <tr>
            <th scope='col'>Reflex</th>
            <th scope='col'>Etyma</th>
        </tr>
      </thead>

      <tbody>
      @foreach($display_reflexes as $reflex)
        <tr>
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
    @include('menu_lex')
@stop
