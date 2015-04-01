@extends('layout')

@section('title') Early Indo-European Online: Introduction to the Language Lessons @stop

@section('content')

@include('menu_lex')


    </div> <!-- close menu div -->
</div> <!-- close container for menu -->

<div id="contentmain"> <!-- open div for main content section -->

<!-- end Standard Header for new CoLA-style design -->

<h1>Indo-European Lexicon</h1>
<h2>Old Irish Reflex Index</h2>

<p>Below we list 
{{count($language->reflexes)}}
unique 
{{$language->name}} 
reflex spellings (words and affixes) in an 
alphabetic order suitable for the language family. Every spelling is linked to one 
or more pages, each showing a Proto-Indo-European etymon from which the reflex is 
derived along with other reflexes (in Old Irish or other languages) derived from 
the same PIE etymon. A multi-morpheme reflex may, like English <i>werewolf</i>, be 
derived from multiple PIE etyma; or a single spelling may, like English <i>bear</i> 
or <i>lie</i>, represent multiple reflexes derived from different PIE etyma.</p>

<table border='0' summary="Old Irish reflex index">
  <tr><th scope='col'>Reflex</th><th scope='col'>Etyma</th></tr>
  
  @foreach($language->reflexes as $reflex)
  	<tr>
  		<td>
  			<a id='{{$reflex->id}}' name='{{$reflex->id}}'></a>
  			<span class='{{$reflex->class_attribute}}' lang='{{$reflex->lang_attribute}}'>{{$reflex->reflex}}</span>
  		</td>
  		<td>
  			@foreach($reflex->etymas as $index => $etyma)
	  			<a title="{{$etyma->gloss}}" href='/lex_reflex/{{$etyma->id}}#{{$language->abbr}}'>
	  				<span class='Unicode' lang='ine'>{{explode(",",$etyma->entry)[0]}}</span>
	  			</a>@if ($index+1 != count($reflex->etymas)),@endif
  			@endforeach 
  		</td>
  	</tr>
  @endforeach
</table>
    

@stop