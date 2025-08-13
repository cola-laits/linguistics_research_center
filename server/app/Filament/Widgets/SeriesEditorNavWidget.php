<?php

namespace App\Filament\Widgets;

use App\Models\EieolSeries;
use Filament\Widgets\Widget;

class SeriesEditorNavWidget extends Widget
{
    protected string $view = 'filament.widgets.series-editor-nav-widget';
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        if (auth()->user()->isAdmin()) {
            $serieses = EieolSeries::all()->sortBy('order');
        } else {
            $serieses = auth()->user()->editableSeries->sortBy('order');
        }
        return [
            'serieses' => $serieses,
        ];
    }
}
