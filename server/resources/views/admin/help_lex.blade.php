@extends(backpack_view('blank'))

@section('header')
    <section class="container-fluid">
        <h2>
            Lexicon editor help
        </h2>
    </section>
@endsection

@section('content')

    <div>
        <p>Using the 'Lexicon' section of the editor, you can edit the IELEX database.</p>
        <p>Subsections let you edit specific types of content.</p>
        <br>

        <h4><i class='nav-icon la la-language'></i> Languages, Language Sub Families, Language Families</h4>
        <p>Here, you can edit the languages used in the IELEX, and what language groupings they belong to.</p>

        <h4><i class='nav-icon la la-book'></i> Sources, Semantic Categories, Semantic Fields, Parts of Speech</h4>
        <p>IELEX entries are linked to various classes of metadata.  Here, you can edit those.</p>

        <h4><i class='nav-icon la la-globe-europe'></i> Etyma</h4>
        <p>An Etymon is one of the main lexicon entries listed in the IELEX Master Index (<a href="https://lrc.la.utexas.edu/lex/master/0007" target="_blank">example</a>).
        Add or edit etyma here.
        </p>

        <h4><i class='nav-icon la la-globe-europe'></i> Reflex</h4>
        <p>A Reflex is a language-specific expression of an etyma.  Reflexes are the rows in the 'Indo-European Reflexes' table for an Etymon.</p>

        <h4><i class='nav-icon la la-globe-europe'></i> Reflex > Part of Speech</h4>
        <p>Individual reflexes represent one or more parts of speech.  These are represented in the databse as pairs of reflexes and PoS'es.
        Here, you can edit those reflex-PoS pairings.  They appear on the site in the order in which you define here.
        </p>

        <h4><i class='nav-icon la la-globe-europe'></i> Etyma > Reflex</h4>
        <p>Reflexes can be used in multiple etyma.  (e.g. the Old English reflex <i>æppel-ðorn</i> is linked to two separate etyma for
        the two parts of the compound word.)  Here, you can add or remove those etymon<->reflex pairings.
        </p>

    </div>

@endsection


