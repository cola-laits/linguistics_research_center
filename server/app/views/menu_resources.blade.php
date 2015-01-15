<ul class="menu">
	<li class="first">Lesson Resources</li>	
	<li>{{ HTML::link('toc/' . $series_id, "Contents")}}</li>
	@foreach($languages as $language)
		<li>{{ HTML::link('master_gloss/' . $series_id . '/' . $language->id, $language->language . " Glossary")}}</li>
	@endforeach
	@foreach($languages as $language)
		<li>{{ HTML::link('base_form_dictionary/' . $series_id . '/' . $language->id, $language->language . " Dictionary")}}</li>
	@endforeach
	@foreach($languages as $language)
		<li>{{ HTML::link('english_meaning_index/' . $series_id . '/' . $language->id, $language->language . " Meanings")}}</li>
	@endforeach
		
</ul>
<br />