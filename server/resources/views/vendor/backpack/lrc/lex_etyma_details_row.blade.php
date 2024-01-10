<b>Etyma</b>:
<p>{{$entry->entry}}</p>
<b>Gloss</b>:
<p>{{$entry->gloss}}</p>
<b>Reflexes</b>:
<ul>
    @foreach ($entry->reflexes as $reflex)
        <li>{{$reflex->langNameEntriesGloss}}</li>
    @endforeach
</ul>
