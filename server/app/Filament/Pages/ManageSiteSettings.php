<?php

namespace App\Filament\Pages;

use App\Settings\SiteSettings;
use BackedEnum;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageSiteSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;
    protected static string|null|\UnitEnum $navigationGroup = 'General';
    protected static ?string $navigationLabel = 'Settings';
    protected static ?int $navigationSort = 2;

    protected static string $settings = SiteSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Checkbox::make('show_donation_popup')
                    ->label('Show donation popup')
                    ->helperText('Show a "please donate" popup to first-time visitors'),
                Textarea::make('donation_popup_text')
                    ->label('Donation popup text')
                    ->helperText('Text/HTML of donation popup')
                    ->rows(10)
                    ->required(),
            ])
            ->columns(1);
    }

    public static function canAccess(): bool
    {
        return auth()->user()->can('manage_settings');
    }

}
