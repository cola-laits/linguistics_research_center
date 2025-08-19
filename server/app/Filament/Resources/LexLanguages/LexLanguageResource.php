<?php

namespace App\Filament\Resources\LexLanguages;

use App\Filament\Resources\LexLanguages\Pages\CreateLexLanguage;
use App\Filament\Resources\LexLanguages\Pages\EditLexLanguage;
use App\Filament\Resources\LexLanguages\Pages\ListLexLanguages;
use App\Filament\Resources\LexLanguages\Schemas\LexLanguageForm;
use App\Filament\Resources\LexLanguages\Tables\LexLanguagesTable;
use App\Models\LexLanguage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class LexLanguageResource extends Resource
{
    use Translatable;

    protected static ?string $model = LexLanguage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLanguage;
    protected static string|null|\UnitEnum $navigationGroup = 'Lexicon';
    protected static ?string $navigationLabel = 'Languages';
    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $pluralModelLabel = 'Languages';
    protected static ?string $label = 'Language';

    public static function form(Schema $schema): Schema
    {
        return LexLanguageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LexLanguagesTable::configure($table);
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
            'index' => ListLexLanguages::route('/'),
            'create' => CreateLexLanguage::route('/create'),
            'edit' => EditLexLanguage::route('/{record}/edit'),
        ];
    }
}
