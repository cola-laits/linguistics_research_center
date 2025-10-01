<?php

namespace App\Filament\Resources\LexLexicons;

use App\Filament\Resources\LexLexicons\Pages\CreateLexLexicon;
use App\Filament\Resources\LexLexicons\Pages\EditLexLexicon;
use App\Filament\Resources\LexLexicons\Pages\DataCacheStatus;
use App\Filament\Resources\LexLexicons\Pages\ListLexLexicons;
use App\Filament\Resources\LexLexicons\Schemas\LexLexiconForm;
use App\Filament\Resources\LexLexicons\Tables\LexLexiconsTable;
use App\Models\LexLexicon;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class LexLexiconResource extends Resource
{
    use Translatable;

    protected static ?string $model = LexLexicon::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;
    protected static string|null|\UnitEnum $navigationGroup = 'Lexicon';
    protected static ?string $navigationLabel = 'Lexicons';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $pluralModelLabel = 'Lexicons';
    protected static ?string $label = 'Lexicon';

    public static function form(Schema $schema): Schema
    {
        return LexLexiconForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LexLexiconsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLexLexicons::route('/'),
            'create' => CreateLexLexicon::route('/create'),
            'edit' => EditLexLexicon::route('/{record}/edit'),
            'data-cache-status' => DataCacheStatus::route('/{record}/data-cache-status'),
        ];
    }
}
