<div style="display: flex">
    <div style="flex: 0; padding-right:3em;">
        <b>Etyma</b>:
        <p>{{$entry->entry}}</p>
        <b>Gloss</b>:
        <p>{{$entry->gloss}}</p>
    </div>
    <div style="flex: 1">
        <b>Reflexes</b>:
        <ul>
            @foreach ($entry->reflexes as $reflex)
                <li>{{$reflex->langNameEntriesGloss}}</li>
            @endforeach
        </ul>
    </div>
</div>
