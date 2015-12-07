<ul class="side-nav">
	<li class="office"><label>Lesson Resources</label></li>
	<li>{{ HTML::link('eieol_printable/' . $series->id, "Printable Version")}}</li>
	<li>{{ HTML::link('eieol_toc/' . $series->id, "Contents")}}</li>
	@if ($bibliography_id != '')
		<li><a href='/eieol_lesson/{{$series->id}}?id={{$bibliography_id}}'>Bibliography</a></li>
	@endif
	@foreach($languages as $language)
		<li>{{ HTML::link('eieol_master_gloss/' . $series->id . '/' . $language->id, $language->language . " Glossary")}}</li>
	@endforeach
	@foreach($languages as $language)
		<li>{{ HTML::link('eieol_base_form_dictionary/' . $series->id . '/' . $language->id, $language->language . " Dictionary")}}</li>
	@endforeach
	@foreach($languages as $language)
		<li>{{ HTML::link('eieol_english_meaning_index/' . $series->id . '/' . $language->id, $language->language . " Meanings")}}</li>
	@endforeach		
</ul>