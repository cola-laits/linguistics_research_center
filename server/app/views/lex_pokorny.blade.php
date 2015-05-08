@extends('layout')

@section('title') Early Indo-European Online: Introduction to the Language Lessons @stop

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
{{ HTML::link('lex', 'IE Lexicon', array('title' => 'Indo-European Lexicon (etyma and reflexes)' )) }}

home page): they are subject to change at any time, and might possibly exhibit errors not yet corrected. 
See our 
{{ HTML::link('lex_language', 'Language Index', array('title' => 'Indo-European Lexicon: IE Language Index' )) }}
page for links <i>from</i> reflexes (listed by IE language) <i>to</i> their PIE etyma.</p>


<table summary='Pokorny head-word entries with page numbers, cross-references, and English glosses'>
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
				 <td><span id='P{{$etyma->id}}' name='P{{$etyma->id}}'>{{$etyma->page_number}}</span></td>
				 <td><span class='Unicode' lang='ine'>{{$etyma->entry}}</span></td>
				 <td>
				 	
				 	@if (count($etyma->reflex_count) != 0)
				 		{{ HTML::link('lex_reflex/' . $etyma->id, 'IE', array('title' => 'Pokorny PIE etymon with Indo-European reflexes' )) }}
				 		&nbsp;&nbsp;
				 	@endif
				 	
				 	@foreach($etyma->cross_references as $cross_reference)
				 		<a title='Pokorny cross-reference' href='#P{{$cross_reference->id}}'><span class='Unicode' lang='ine'>
				 			{{explode(":",explode(",",$cross_reference->entry)[0])[0]}}
				 		</span></a>
				 	@endforeach
				 					 	
				 </td>
				 <td>{{$etyma->gloss}}</td>
			 </tr>
	 @endforeach

 	</tbody>
 
 </table>
    
    
<!--    
*******************************************
OFFICE NAVIGATION - RELATED LINKS - CONTACT
******************************************* -->
</div>
</div>
<div class="medium-3 medium-pull-9 columns content-secondary-page-navigation"><!-- Office Navigation -->
<hr class="show-for-small-only"/>

@include('menu_menu')
@include('menu_lex')

</div>
</div>
 

    
@stop