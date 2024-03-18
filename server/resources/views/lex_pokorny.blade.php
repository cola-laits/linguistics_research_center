@extends('layout')

@section('title') Indo-European Lexicon: Pokorny Master PIE Etyma @stop

@section('content')


<h1>Indo-European Lexicon</h1>
<h2>Pokorny Master PIE Etyma</h2>

<p>The table below lists Proto-Indo-European (PIE) etyma adapted from Julius Pokorny's book,
<i lang='de'>Indogermanisches etymologisches W&ouml;rterbuch</i> (Bern: Francke, 1959, 1989).
Entry head-words are listed, with their page numbers and cross-references to other entries
(following Pokorny) plus our own English glosses; for more information, the reader is referred
to the book. Some misprints in Pokorny have been corrected, including repairs to diacritics and
missing, incorrect, or extraneous homograph numbers.</p>

<p>In our table, cross-references under "See also" may include IE links to our own lists
of more modern Indo-European reflexes -- words derived from the ancient Proto-Indo-European
etyma. IE reflex lists are <i>under active construction</i> (see our
<a href="/lex" title="Indo-European Lexicon (etyma and reflexes)">IE Lexicon</a>

home page): they are subject to change at any time, and might possibly exhibit errors not yet corrected.
See our
<a href="/lex/languages" title="Indo-European Lexicon: IE Language Index">Language Index</a>
page for links <i>from</i> reflexes (listed by IE language) <i>to</i> their PIE etyma.</p>


<table>
    <caption>Pokorny head-word entries with page numbers, cross-references, and English glosses</caption>
  <thead>
    <tr>
        <th scope='col'>Page(s)</th>
        <th scope='col'>Pokorny entry</th>
        <th scope='col'>See&nbsp;also</th>
        <th scope='col'>English gloss</th>
    </tr>
  </thead>
  <tbody>

     @foreach($etymas as $etyma)
            <tr>
                 <td><span id='P{{$etyma->id}}'>{{$etyma->page_number}}</span></td>
                 <td class='Unicode' lang='ine'>{{ $etyma->homograph_number }} {!! $etyma->entry !!}</td>
                 <td>

                    @if ($etyma->reflexes_count != 0)
                        <a href="/lex/master/{{$etyma->old_id}}" title="Pokorny PIE etymon with Indo-European reflexes">IE</a>
                        &nbsp;&nbsp;
                    @endif

                    @foreach($etyma->cross_references as $cross_reference)
                        <a title='Pokorny cross-reference' href='#P{{$cross_reference->id}}'><span class='Unicode' lang='ine'>
                                @homograph_number_ielex($cross_reference->homograph_number)
                                {!! explode(":",explode(",",$cross_reference->entry)[0])[0] !!}
                        </span></a>
                    @endforeach

                 </td>
                 <td>{!! $etyma->gloss !!}</td>
             </tr>
     @endforeach

    </tbody>

 </table>
@stop


@section('menu')
    @include('menu_menu')
@stop
