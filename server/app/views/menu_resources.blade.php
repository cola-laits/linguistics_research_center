<ul class="side-nav">
	<li class="office"><label>Lesson Resources</label></li>
	<li>{{ HTML::link('eieol_printable/' . $series->slug, "Printable Version")}}</li>
	<li>{{ HTML::link('eieol_toc/' . $series->slug, "Contents")}}</li>
	@if ($bibliography_id != '')
		<li><a href='/eieol/{{$series->slug}}/{{$bibliography_order}}'>Bibliography</a></li>
	@endif
	@foreach($languages as $language)
		<li>{{ HTML::link('eieol_master_gloss/' . $series->slug . '/' . $language->id, $language->language . " Glossary")}}</li>
	@endforeach
	@foreach($languages as $language)
		<li>{{ HTML::link('eieol_base_form_dictionary/' . $series->slug . '/' . $language->id, $language->language . " Dictionary")}}</li>
	@endforeach
	@foreach($languages as $language)
		<li>{{ HTML::link('eieol_english_meaning_index/' . $series->slug . '/' . $language->id, $language->language . " Meanings")}}</li>
	@endforeach		
</ul>