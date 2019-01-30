<ul class="side-nav">
    <li class="office"><label>Lesson Resources</label></li>
    <li><a href="/eieol_printable/{{$series->slug}}">Printable Version</a></li>
    <li><a href="/eieol_toc/{{$series->slug}}">Contents</a></li>
    @if ($bibliography_id != '')
    <li><a href='/eieol/{{$series->slug}}/{{$bibliography_order}}'>Bibliography</a></li>
    @endif
    @foreach($languages as $language)
    <li><a href="/eieol_master_gloss/{{$series->slug}}/{{$language->id}}">{{$language->language}} Glossary</a></li>
    @endforeach
    @foreach($languages as $language)
    <li><a href="/eieol_base_form_dictionary/{{$series->slug}}/{{$language->id}}">{{$language->language}} Dictionary</a></li>
    @endforeach
    @foreach($languages as $language)
    <li><a href="/eieol_english_meaning_index/{{$series->slug}}/{{$language->id}}">{{$language->language}} Meanings</a></li>
    @endforeach
</ul>
