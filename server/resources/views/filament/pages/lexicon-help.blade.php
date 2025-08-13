<x-filament-panels::page>
    <div>
        <p>Using the 'Lexicon' section of the editor, you can edit the IELEX database.</p>
        <p>Subsections let you edit specific types of content.</p>
        <br>

        <div>
        <h4 class="mt-4 text-2xl font-bold flex items-center"><x-filament::icon icon="heroicon-m-language" class="w-[1em] h-[1em] shrink-0 me-2"/> Languages, Language Sub Families, Language Families</h4>
        <p>Here, you can edit the languages used in the IELEX, and what language groupings they belong to.</p>
        </div>

        <div>
        <h4 class="mt-4 text-2xl font-bold flex items-center"><x-filament::icon icon="heroicon-m-book-open" class="w-[1em] h-[1em] shrink-0 me-2"/> Sources, Semantic Categories, Semantic Fields, Parts of Speech</h4>
        <p>IELEX entries are linked to various classes of metadata.  Here, you can edit those.</p>
        </div>

        <div>
        <h4 class="mt-4 text-2xl font-bold flex items-center"><x-filament::icon icon="heroicon-m-globe-americas" class="w-[1em] h-[1em] shrink-0 me-2"/> Etyma</h4>
        <p>An Etymon is one of the main lexicon entries listed in the IELEX Master Index
            (<a class="underline" href="https://lrc.la.utexas.edu/lex/master/0007" target="_blank">example</a>).
            Add or edit etyma here.</p>
        <p>You can also link an Etymon to one more Reflexes here.</p>
        </div>

        <div>
        <h4 class="mt-4 text-2xl font-bold flex items-center"><x-filament::icon icon="heroicon-m-chat-bubble-left" class="w-[1em] h-[1em] shrink-0 me-2"/> Reflex</h4>
        <p>A Reflex is a language-specific expression of an etyma.  Reflexes are the rows in the 'Indo-European Reflexes' table for an Etymon.</p>
        </div>

        <div>
        <h4 class="mt-4 text-2xl font-bold flex items-center"><x-filament::icon icon="heroicon-m-chat-bubble-left" class="w-[1em] h-[1em] shrink-0 me-2"/> Reflex > Part of Speech</h4>
        <p>Individual reflexes represent one or more parts of speech.  These are represented in the databse as pairs of reflexes and PoS'es.
            Here, you can edit those reflex-PoS pairings.  They appear on the site in the order in which you define here.
        </p>
        </div>

    </div>
</x-filament-panels::page>
