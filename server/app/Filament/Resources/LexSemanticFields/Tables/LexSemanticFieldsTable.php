<?php

namespace App\Filament\Resources\LexSemanticFields\Tables;

use App\Models\LexSemanticCategory;
use App\Models\LexSemanticField;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LexSemanticFieldsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('semantic_category.lexicon.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('semantic_category.abbr')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('abbr')
                    ->searchable(),
                TextColumn::make('number')
                    ->searchable(),
                TextColumn::make('text')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('lexicon')
                    ->relationship('semantic_category.lexicon', 'name')
                    ->preload()
                    ->searchable(),
                SelectFilter::make('category')
                    ->relationship('semantic_category', 'abbr')
                    ->getOptionLabelFromRecordUsing(fn (LexSemanticCategory $category) => "{$category->lexicon->name}: {$category->abbr}")
                    ->preload()
                    ->searchable(),
            ], layout: FiltersLayout::AboveContent)
            ->persistFiltersInSession()
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                //BulkActionGroup::make([
                //    DeleteBulkAction::make(),
                //]),
            ]);
    }
}
