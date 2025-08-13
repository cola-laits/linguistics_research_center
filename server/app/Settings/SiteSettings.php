<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public bool $show_donation_popup;
    public string $donation_popup_text;

    public static function group(): string
    {
        return 'site';
    }
}
