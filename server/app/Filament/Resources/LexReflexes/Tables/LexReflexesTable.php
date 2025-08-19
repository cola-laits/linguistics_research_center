<?php

namespace App\Filament\Resources\LexReflexes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LexReflexesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('language.name'),
                TextColumn::make('gloss')
                    ->searchable(),
                TextColumn::make('entries')
                    ->label('Reflexes')
                    ->getStateUsing(fn($record) => $record->getEntriesCSV())
                    ->searchable(),
            ])
            ->filters([
                Filter::make('entries')
                    ->schema([
                        TextInput::make('entries')
                            ->label('Reflexes')
                    ])
                    ->indicateUsing(fn (array $data): ?string => $data['entries'] ? "Reflexes: " . $data['entries'] : null)
                    ->query(fn(Builder $query, array $data): Builder => $query->when(
                        $data['entries'],
                        fn (Builder $query, $search): Builder => $query->whereLike('entries', '%'.$search.'%'),
                    )),
                Filter::make('gloss')
                    ->schema([
                        TextInput::make('gloss')
                    ])
                    ->indicateUsing(fn (array $data): ?string => $data['gloss'] ? "Gloss: " . $data['gloss'] : null)
                    ->query(fn(Builder $query, array $data): Builder => $query->when(
                        $data['gloss'],
                        fn (Builder $query, $search): Builder => $query->whereLike('gloss', '%'.$search.'%'),
                    )),
                Selectfilter::make('language')
                    ->relationship('language', 'name'),
                Filter::make('part_of_speech')
                    ->label('Part of Speech')
                    ->schema([
                        TextInput::make('part_of_speech')
                            ->label('Part of Speech')
                    ])
                    ->indicateUsing(fn (array $data): ?string => $data['part_of_speech'] ? "Part of Speech: " . $data['part_of_speech'] : null)
                    ->query(fn(Builder $query, array $data): Builder => $query->when(
                        $data['part_of_speech'],
                        fn (Builder $query, $search): Builder => $query->whereHas('parts_of_speech', function (Builder $subQuery) use ($search) {
                            $subQuery->where('text', 'like', '%'.$search.'%');
                        }),
                    )),

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
