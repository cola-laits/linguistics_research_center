<?php

namespace App\Filament\Resources\LexSemanticCategories;

use App\Filament\Resources\LexSemanticCategories\Pages\CreateLexSemanticCategory;
use App\Filament\Resources\LexSemanticCategories\Pages\EditLexSemanticCategory;
use App\Filament\Resources\LexSemanticCategories\Pages\ListLexSemanticCategories;
use App\Filament\Resources\LexSemanticCategories\Schemas\LexSemanticCategoryForm;
use App\Filament\Resources\LexSemanticCategories\Tables\LexSemanticCategoriesTable;
use App\Models\LexSemanticCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class LexSemanticCategoryResource extends Resource
{
    use Translatable;

    protected static ?string $model = LexSemanticCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;
    protected static string|null|\UnitEnum $navigationGroup = 'Lexicon';
    protected static ?string $navigationLabel = 'Semantic Categories';
    protected static ?int $navigationSort = 9;

    protected static ?string $recordTitleAttribute = 'abbr';

    protected static ?string $pluralModelLabel = 'Semantic Categories';
    protected static ?string $label = 'Semantic Category';

    public static function form(Schema $schema): Schema
    {
        return LexSemanticCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LexSemanticCategoriesTable::configure($table);
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
            'index' => ListLexSemanticCategories::route('/'),
            'create' => CreateLexSemanticCategory::route('/create'),
            'edit' => EditLexSemanticCategory::route('/{record}/edit'),
        ];
    }
}
