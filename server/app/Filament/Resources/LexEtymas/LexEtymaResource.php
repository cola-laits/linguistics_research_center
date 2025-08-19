<?php

namespace App\Filament\Resources\LexEtymas;

use App\Filament\Resources\LexEtymas\Pages\CreateLexEtyma;
use App\Filament\Resources\LexEtymas\Pages\EditLexEtyma;
use App\Filament\Resources\LexEtymas\Pages\ListLexEtymas;
use App\Filament\Resources\LexEtymas\Schemas\LexEtymaForm;
use App\Filament\Resources\LexEtymas\Tables\LexEtymasTable;
use App\Models\LexEtyma;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class LexEtymaResource extends Resource
{
    use Translatable;

    protected static ?string $model = LexEtyma::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAmericas;
    protected static string|null|\UnitEnum $navigationGroup = 'Lexicon';
    protected static ?string $navigationLabel = 'Etyma';
    protected static ?int $navigationSort = 6;
    protected static ?string $pluralModelLabel = 'Etyma';
    protected static ?string $label = 'Etymon';

    protected static ?string $recordTitleAttribute = 'entry';

    public static function form(Schema $schema): Schema
    {
        return LexEtymaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LexEtymasTable::configure($table);
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
            'index' => ListLexEtymas::route('/'),
            'create' => CreateLexEtyma::route('/create'),
            'edit' => EditLexEtyma::route('/{record}/edit'),
        ];
    }
}
