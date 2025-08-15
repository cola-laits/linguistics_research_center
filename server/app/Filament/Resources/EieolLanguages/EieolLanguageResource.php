<?php

namespace App\Filament\Resources\EieolLanguages;

use App\Filament\Resources\EieolLanguages\Pages\CreateEieolLanguage;
use App\Filament\Resources\EieolLanguages\Pages\EditEieolLanguage;
use App\Filament\Resources\EieolLanguages\Pages\ListEieolLanguages;
use App\Filament\Resources\EieolLanguages\Schemas\EieolLanguageForm;
use App\Filament\Resources\EieolLanguages\Tables\EieolLanguagesTable;
use App\Models\EieolLanguage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EieolLanguageResource extends Resource
{
    protected static ?string $model = EieolLanguage::class;

    protected static ?string $navigationLabel = 'Languages';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-m-chat-bubble-bottom-center-text';
    protected static string|null|\UnitEnum $navigationGroup = 'EIEOL';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'language';

    public static function form(Schema $schema): Schema
    {
        return EieolLanguageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EieolLanguagesTable::configure($table);
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
            'index' => ListEieolLanguages::route('/'),
            'create' => CreateEieolLanguage::route('/create'),
            'edit' => EditEieolLanguage::route('/{record}/edit'),
        ];
    }
}
