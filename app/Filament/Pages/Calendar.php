<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Guava\Calendar\Widgets\CalendarWidget;

class Calendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Calendar';
    // protected static ?string $navigationGroup = 'Tools'; // optional group
    protected static string $view = 'filament.pages.calendar';

    public function getWidgets(): array
    {
        return [
            CalendarWidget::class,
        ];
    }
}
