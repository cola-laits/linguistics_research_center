<?php

namespace App\Filament\Resources\LexLexicons\Pages;

use App\Filament\Resources\LexLexicons\LexLexiconResource;
use App\Models\LexReflex;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DataCacheStatus extends Page
{
    use InteractsWithRecord;

    protected static string $resource = LexLexiconResource::class;

    protected string $view = 'filament.resources.lex-lexicons.pages.data-cache-status';

    public int $reflexCount = 0;

    public int $dataCacheCount = 0;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('regenerate-cache')
                ->label('Regenerate Cache')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->modalHeading('Regenerate Data Cache')
                ->modalDescription('This will rebuild the data cache for this lexicon. Depending on size, it may take a while.')
                ->action(function () {
                    try {
                        // Prefer queued execution if available; fall back to synchronous call
                        if (method_exists(Artisan::class, 'queue')) {
                            Artisan::queue('app:generate-lexicon-data-cache', [
                                'lexicon_id' => $this->record->id,
                            ]);
                            Notification::make()
                                ->title('Regeneration started')
                                ->body('The cache regeneration job has been queued. You can continue working; refresh later to see updated counts.')
                                ->success()
                                ->send();
                        } else {
                            Artisan::call('app:generate-lexicon-data-cache', [
                                'lexicon_id' => $this->record->id,
                            ]);
                            // After synchronous run, refresh the counts
                            $this->mount($this->record->id);
                            Notification::make()
                                ->title('Regenerated successfully')
                                ->body('The data cache has been rebuilt for this lexicon.')
                                ->success()
                                ->send();
                        }
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Failed to regenerate')
                            ->body('An error occurred while attempting to regenerate the cache: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

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
