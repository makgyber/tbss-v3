<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\TechnicianScheduleMap;
use App\Filament\Widgets\Tracker;
use Filament\Pages\Page;

class ServiceMap extends Page
{
    protected ?string $heading = 'Service Schedule';
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static string $view = 'filament.pages.service-map';

    public function mount(): void
    {
        abort_unless(auth()->user()->hasRole('technician'), 403);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('technician');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TechnicianScheduleMap::class,
            Tracker::class,
        ];
    }
}
