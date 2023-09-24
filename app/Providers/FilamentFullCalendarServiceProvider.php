<?php

namespace App\Providers;

use Filament\FilamentServiceProvider;
use App\Commands\UpgradeFilamentFullCalendarCommand;
use Spatie\LaravelPackageTools\Package;

class FilamentFullCalendarServiceProvider extends FilamentServiceProvider
{
    protected array $beforeCoreScripts = [
        'filament-fullcalendar-scripts' => __DIR__ . '/../../resources/js/filament-fullcalendar.js',
    ];

    protected array $styles = [
        'filament-fullcalendar-styles' => __DIR__ . '/../../resources/css/filament-fullcalendar.css',
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-fullcalendar')
            ->hasViews();
    }
}
