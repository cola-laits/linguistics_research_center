
<b>Reflexes</b>:
<ul>
    @foreach ($entry->reflexes as $reflex)
        <li>{{$reflex->langNameEntriesGloss}}</li>
    @endforeach
</ul>
