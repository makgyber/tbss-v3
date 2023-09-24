<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\JobOrderCalendarWidget;
use Filament\Pages\Page;

class JobOrderSchedules extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static string $view = 'filament.pages.job-order-schedules';

    protected function getHeaderWidgets(): array
    {
        return [
            JobOrderCalendarWidget::class
        ];
    }
}
