<?php

namespace App\Filament\Resources\LexLexicons\Pages;

use App\Filament\Resources\LexLexicons\LexLexiconResource;
use App\Models\LexReflex;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class DataCacheStatus extends Page
{
    use InteractsWithRecord;

    protected static string $resource = LexLexiconResource::class;

    protected string $view = 'filament.resources.lex-lexicons.pages.data-cache-status';

    public int $reflexCount = 0;

    public int $dataCacheCount = 0;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        $this->reflexCount = LexReflex::query()
            ->whereHas('language.language_sub_family.language_family', function ($query) {
                $query->where('lexicon_id', $this->record->id);
            })
            ->count();

        $this->dataCacheCount = (int) DB::table('lex_lexicon_data_cache')
            ->where('lexicon_id', $this->record->id)
            ->where('content_lang_code', 'en')
            ->count();
    }
}
