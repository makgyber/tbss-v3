<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ServiceMapTableWidget;
use App\Filament\Widgets\ServiceTrackingMap;
use Carbon\Carbon;
use Cheesegrits\FilamentGoogleMaps\Widgets\MapTableWidget;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;

class CommandCenter extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-cloud';

    protected static string $view = 'filament.pages.command-center';

    protected static bool $shouldRegisterNavigation = true;

    public static function shouldRegisterNavigation(): bool
    {
        return !auth()->user()->hasRole('technician');
    }

    public function mount(): void
    {
        // $this->form->fill();
        if (auth()->user()->hasRole('technician')) {
            redirect(route('filament.admin.pages.service-map'));
        }
    }

    protected function getFormSchema(): array
    {
        return [
            // DatePicker::make('target_date')->default(Carbon::now())
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ServiceTrackingMap::class,
        ];
    }
}
