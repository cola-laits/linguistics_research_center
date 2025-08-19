<?php

namespace App\Filament\Resources\LexEtymas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class LexEtymasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lexicon.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('entry')
                    ->label('Etyma')
                    ->formatStateUsing(function (string $state): string {
                        $newState = str($state)->explode(',')->join('<br>');
                        return new HtmlString($newState);
                    })
                    ->html()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gloss')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reflexes.langNameEntriesGloss')
                    ->bulleted()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('lexicon')
                    ->relationship('lexicon', 'name'),
                Filter::make('entry')
                    ->schema([
                        TextInput::make('entry')
                            ->label('Etyma')
                    ])
                    ->indicateUsing(fn (array $data): ?string => $data['entry'] ? "Entry: " . $data['entry'] : null)
                    ->query(fn(Builder $query, array $data): Builder => $query->when(
                        $data['entry'],
                        fn (Builder $query, $search): Builder => $query->whereLike('entry', '%'.$search.'%'),
                    )),
                Filter::make('gloss')
                    ->schema([
                        TextInput::make('gloss')
                            ->label('Gloss')
                    ])
                    ->indicateUsing(fn (array $data): ?string => $data['gloss'] ? "Gloss: " . $data['gloss'] : null)
                    ->query(fn(Builder $query, array $data): Builder => $query->when(
                        $data['gloss'],
                        fn (Builder $query, $search): Builder => $query->whereLike('gloss', '%'.$search.'%'),
                    )),
                Filter::make('reflexes')
                    ->schema([
                        TextInput::make('reflexes')
                            ->label('Reflexes')
                    ])
                    ->indicateUsing(fn (array $data): ?string => $data['reflexes'] ? "Reflexes: " . $data['reflexes'] : null)
                    ->query(fn(Builder $query, array $data): Builder => $query->when(
                        $data['reflexes'],
                        fn (Builder $query, $search): Builder => $query->
                            whereHas('reflexes', function (Builder $subQuery) use ($search) {
                                $subQuery->where('gloss', 'like', '%'.$search.'%')
                                    ->orWhere('entries', 'like', '%'.$search.'%');
                            }),
                        ))
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
