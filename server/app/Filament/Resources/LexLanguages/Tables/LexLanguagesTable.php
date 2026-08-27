<?php

namespace App\Filament\Resources\LexLanguages\Tables;

use App\Models\LexLanguageFamily;
use App\Models\LexLanguageSubFamily;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LexLanguagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('language_sub_family.familySubFamily')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        $language = $query->getModel();
                        $subFamily = new LexLanguageSubFamily;
                        $family = new LexLanguageFamily;

                        return $query
                            ->orderBy(
                                LexLanguageFamily::query()
                                    ->select($family->qualifyColumn('name'))
                                    ->join(
                                        $subFamily->getTable(),
                                        $family->qualifyColumn('id'),
                                        '=',
                                        $subFamily->qualifyColumn('family_id'),
                                    )
                                    ->whereColumn(
                                        $subFamily->qualifyColumn('id'),
                                        $language->qualifyColumn('sub_family_id'),
                                    )
                                    ->limit(1),
                                $direction,
                            )
                            ->orderBy(
                                LexLanguageSubFamily::query()
                                    ->select($subFamily->qualifyColumn('name'))
                                    ->whereColumn(
                                        $subFamily->qualifyColumn('id'),
                                        $language->qualifyColumn('sub_family_id'),
                                    )
                                    ->limit(1),
                                $direction,
                            );
                    }),
            ])
            ->filters([
                //
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
