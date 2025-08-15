<?php

namespace App\Filament\Resources\EieolSeries;

use App\Filament\Resources\EieolSeries\Pages\CreateEieolSeries;
use App\Filament\Resources\EieolSeries\Pages\EditEieolSeries;
use App\Filament\Resources\EieolSeries\Pages\ListEieolSeries;
use App\Filament\Resources\EieolSeries\Schemas\EieolSeriesForm;
use App\Filament\Resources\EieolSeries\Tables\EieolSeriesTable;
use App\Models\EieolSeries;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EieolSeriesResource extends Resource
{
    protected static ?string $model = EieolSeries::class;

    protected static ?string $navigationLabel = 'Series';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-m-chat-bubble-bottom-center-text';
    protected static string|null|\UnitEnum $navigationGroup = 'EIEOL';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return EieolSeriesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EieolSeriesTable::configure($table);
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
            'index' => ListEieolSeries::route('/'),
            'create' => CreateEieolSeries::route('/create'),
            'edit' => EditEieolSeries::route('/{record}/edit'),
        ];
    }
}
