<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->default(null),
                TextInput::make('password')
                    ->password(),
                TextInput::make('name')
                    ->required(),
                CheckboxList::make('roles')
                    ->relationship('roles', 'name'),
                CheckboxList::make('editableSeries')
                    ->relationship('editableSeries', 'title'),
            ])
            ->columns(1);
    }
}
