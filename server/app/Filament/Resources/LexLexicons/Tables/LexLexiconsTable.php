<?php

namespace App\Filament\Resources\LexLexicons\Tables;

use App\Filament\Resources\LexLexicons\LexLexiconResource;
use App\Models\LexLexicon;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class LexLexiconsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
            ])
            ->filters([
                //
            ], layout: FiltersLayout::AboveContent)
            ->persistFiltersInSession()
            ->recordActions([
                Action::make('dataCacheStatus')
                    ->label('Data Cache Status')
                    ->icon('heroicon-m-chart-bar')
                    ->url(fn (LexLexicon $record) => LexLexiconResource::getUrl('data-cache-status', ['record' => $record])),
                EditAction::make(),
            ])
            ->toolbarActions([
                //BulkActionGroup::make([
                //    DeleteBulkAction::make(),
                //]),
            ]);
    }
}
