<?php

namespace App\Filament\Resources\LexLanguageFamilies;

use App\Filament\Resources\LexLanguageFamilies\Pages\CreateLexLanguageFamily;
use App\Filament\Resources\LexLanguageFamilies\Pages\EditLexLanguageFamily;
use App\Filament\Resources\LexLanguageFamilies\Pages\ListLexLanguageFamilies;
use App\Filament\Resources\LexLanguageFamilies\Schemas\LexLanguageFamilyForm;
use App\Filament\Resources\LexLanguageFamilies\Tables\LexLanguageFamiliesTable;
use App\Models\LexLanguageFamily;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class LexLanguageFamilyResource extends Resource
{
    use Translatable;

    protected static ?string $model = LexLanguageFamily::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLanguage;
    protected static string|null|\UnitEnum $navigationGroup = 'Lexicon';
    protected static ?string $navigationLabel = 'Language Families';
    protected static ?int $navigationSort = 3;

    protected static ?string $pluralModelLabel = 'Language Families';
    protected static ?string $label = 'Language Family';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return LexLanguageFamilyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LexLanguageFamiliesTable::configure($table);
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
            'index' => ListLexLanguageFamilies::route('/'),
            'create' => CreateLexLanguageFamily::route('/create'),
            'edit' => EditLexLanguageFamily::route('/{record}/edit'),
        ];
    }
}
