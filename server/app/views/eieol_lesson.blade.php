@extends('layout')

@section('title') {{strip_tags($lesson->title)}}@stop

@section('content')

<script type="text/javascript">
	$(document).ready(function(){

		//if the came from the gloss, dictionary or meaning index, we need to open that gloss
		var anchor = window.location.hash.substring(1);
		if (anchor != '') {
			splits = anchor.split("_");
			id = splits[splits.length - 1];
			var temp_id = '#gloss_pivot_' + id;
			$(temp_id).slideToggle('fast');
			var temp_id = '#pivot_' + id;
			$(temp_id).toggleClass("clicked");
		}
		
		$(".click_gloss").click(function(e){
			//when they click on a word in the glossed_text, this opens the corresponding gloss
			var temp_id = '#gloss_' + $(this).attr('id');
			$(temp_id).toggleClass('gloss');
			$(this).toggleClass("clicked");
		}); //click_gloss

		$(".expand_all, collapse_all").click(function(e){	
			if ($(this).attr('class') == "expand_all"){
				$(this).html("<i class='fa fa-minus-square-o'></i> Collapse All");
				$(this).next('ul').children('li').each(function () {
				    $(this).removeClass('gloss');
				});
				$(this).parent().prev(".glossed_text").children(":first").children('a').each(function () {
				    $(this).addClass("clicked");
				});
			} else {
				$(this).html("<i class='fa fa-plus-square-o'></i> Expand All");
				$(this).next('ul').children('li').each(function () {
				    $(this).addClass('gloss');
				});
				$(this).parent().prev(".glossed_text").children(":first").children('a').each(function () {
				    $(this).removeClass("clicked");
				});
			}
			$(this).toggleClass("expand_all");
			$(this).toggleClass("collapse_all");
		});
		
	});//document ready
</script>

<h1>{{$series->title}}</h1>
{{$lesson->intro_text}}
<div class="skinny">
@foreach ($lesson->glossed_texts as $glossed_text)
	<div class="glossed_text"><span lang='{{$lesson->language->lang_attribute}}' class='{{$lesson->language->class_attribute}}'>{{$glossed_text->clickable_gloss_text()}}</span></div>
	<div class="boxey">
		<a href="#" onclick="return false" class="expand_all"><i class='fa fa-plus-square-o'></i> Expand All</a>
		<ul>
			@foreach ($glossed_text->glosses as $gloss)
		   		<li id='gloss_pivot_{{$gloss->pivot->id}}' class='gloss'>
		   			<a name='glossed_text_gloss_{{$gloss->pivot->id}}'></a>
		    		{{$gloss->getDisplayGloss()}}
		    	</li>
		    @endforeach
	    </ul>
	</div>
	<br/>
@endforeach

@if ($lesson_text != '')
	<h2>Lesson Text</h2>
	<blockquote><span lang='{{$lesson->language->lang_attribute}}' class='{{$lesson->language->class_attribute}}'>{{$lesson_text}}</span></blockquote>
@endif
	      
@if ($lesson->lesson_translation != '')	        
	<h2>Translation</h2>
	{{$lesson->lesson_translation}}
@endif

@if (count($lesson->grammars) != 0)
	<h2>Grammar</h2>
	@foreach ($lesson->grammars as $grammar)
		<a name='grammar_{{$grammar->id}}'></a>
		<h5>{{$grammar->section_number}} {{$grammar->title}}</h5>
		{{$grammar->grammar_text}}
	@endforeach
@endif


<!-- If intro, display the list of lessons -->
@if ($lesson->order == 0) 
	<h5>The {{$series->menu_name}} Lessons</h5>
	<ol>
	@foreach ($lessons as $lesson)
		@if ($lesson->order != 0)
			<li>
				<a href='/eieol_lesson/{{$series->id}}?id={{$lesson->id}}'>{{$lesson->title}}</a>
			</li>
		@endif
	@endforeach
	</ol>
	 
	<h6>Options:</h6>

	<ul>
		<li>Show full {{ HTML::link('eieol_toc/' . $series->id, "Table of Contents")}} with Grammar Points index</li>
		@foreach($languages as $language)
			<li>Open a {{ HTML::link('eieol_master_gloss/' . $series->id . '/' . $language->id, "Master Glossary window")}} for these {{$language->language}} texts</li>
		@endforeach
		@foreach($languages as $language)
			<li>Open a {{ HTML::link('eieol_base_form_dictionary/' . $series->id . '/' . $language->id, "Base Form Dictionary window")}} for these {{$language->language}} texts</li>
		@endforeach
		@foreach($languages as $language)
			<li>Open an {{ HTML::link('eieol_english_meaning_index/' . $series->id . '/' . $language->id, "English Meaning Index window")}} for these {{$language->language}} texts</li>
		@endforeach
	</ul>
	 
@endif

</div>


<!--    
*******************************************
OFFICE NAVIGATION - RELATED LINKS - CONTACT
******************************************* -->
</div>
</div>
<div class="medium-3 medium-pull-9 columns content-secondary-page-navigation"><!-- Office Navigation -->
<hr class="show-for-small-only"/>

@include('menu_menu')
@include('menu_series', array('data'=>'data'))
@include('menu_resources', array('data'=>'data'))
</div>
</div>
 

    
@stop