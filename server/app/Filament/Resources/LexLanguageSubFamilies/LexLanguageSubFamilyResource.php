<?php

namespace App\Filament\Resources\LexLanguageSubFamilies;

use App\Filament\Resources\LexLanguageSubFamilies\Pages\CreateLexLanguageSubFamily;
use App\Filament\Resources\LexLanguageSubFamilies\Pages\EditLexLanguageSubFamily;
use App\Filament\Resources\LexLanguageSubFamilies\Pages\ListLexLanguageSubFamilies;
use App\Filament\Resources\LexLanguageSubFamilies\Schemas\LexLanguageSubFamilyForm;
use App\Filament\Resources\LexLanguageSubFamilies\Tables\LexLanguageSubFamiliesTable;
use App\Models\LexLanguageSubFamily;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class LexLanguageSubFamilyResource extends Resource
{
    use Translatable;

    protected static ?string $model = LexLanguageSubFamily::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLanguage;
    protected static string|null|\UnitEnum $navigationGroup = 'Lexicon';
    protected static ?string $navigationLabel = 'Language Sub-Families';
    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $pluralModelLabel = 'Language Sub-Families';
    protected static ?string $label = 'Language Sub-family';

    public static function form(Schema $schema): Schema
    {
        return LexLanguageSubFamilyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LexLanguageSubFamiliesTable::configure($table);
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
            'index' => ListLexLanguageSubFamilies::route('/'),
            'create' => CreateLexLanguageSubFamily::route('/create'),
            'edit' => EditLexLanguageSubFamily::route('/{record}/edit'),
        ];
    }
}
