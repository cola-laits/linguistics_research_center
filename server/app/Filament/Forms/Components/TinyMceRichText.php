<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class TinyMceRichText extends Field
{
    protected string $view = 'filament.forms.components.tiny-mce-rich-text';

    protected int $maxHeight = 1000;
    protected int $minHeight = 200;
    protected string $profile = 'default';
    // TinyMCE var: external_plugins
    protected object $externalPlugins;

    public function getMaxHeight(): int
    {
        return $this->maxHeight;
    }

    public function getMinHeight(): int
    {
        return $this->minHeight;
    }

    public function profile(string $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    public function isSimple(): bool
    {
        return false; //return (bool) $this->evaluate($this->isSimple);
    }

    public function getPlugins(): array
    {
        return explode(' ', $this->getPluginsSpaceSeparated());
    }

    public function getPluginsSpaceSeparated(): string
    {
        if ($this->isSimple()) {
            return 'autoresize directionality emoticons link wordcount';
        }

        if (config('filament-forms-tinyeditor.profiles.'.$this->profile.'.plugins')) {
            return config('filament-forms-tinyeditor.profiles.'.$this->profile.'.plugins');
        }

        return 'advlist codesample directionality emoticons fullscreen hr image imagetools link lists media table toc wordcount';
    }

    public function getExternalPlugins(): object
    {
        return $this->externalPlugins ?? new \stdClass();
    }

    public function getToolbar(): string
    {
        if ($this->isSimple()) {
            return 'removeformat | bold italic | rtl ltr | link emoticons';
        }

        if (config('filament-forms-tinyeditor.profiles.'.$this->profile.'.toolbar')) {
            return config('filament-forms-tinyeditor.profiles.'.$this->profile.'.toolbar');
        }

        return 'undo redo removeformat | formatselect fontsizeselect | bold italic | rtl ltr | alignjustify alignright aligncenter alignleft | numlist bullist | forecolor backcolor | blockquote table toc hr | image link media codesample emoticons | wordcount fullscreen';
    }

    public function getCustomConfigs(): string
    {
        if (config('filament-forms-tinyeditor.profiles.'.$this->profile.'.custom_configs')) {
            return '...'.json_encode(config('filament-forms-tinyeditor.profiles.'.$this->profile.'.custom_configs'));
        }

        return '';
    }
}
