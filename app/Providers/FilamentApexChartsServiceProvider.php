<?php

namespace App\Providers;

use Filament\FilamentServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Widgets\ApexCharts\Commands\FilamentApexChartsCommand;
use Spatie\LaravelPackageTools\Package;

class FilamentApexChartsServiceProvider extends FilamentServiceProvider
{
    protected array $beforeCoreScripts = [
        'apex-charts-scripts' => __DIR__ . '/../../resources/js/apexcharts.min.js',
    ];

    protected array $styles = [
        'apex-charts-styles' => __DIR__ . '/../../resources/css/apexcharts.css',
    ];

    /**
     * Configures the given package with the name 'filament-apex-charts'
     * as a Package Service Provider.
     *
     * @param  Package  $package the package to be configured
     *
     * @throws void
     */
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('filament-apex-charts')
            // ->hasConfigFile()
            ->hasViews()
            ->hasCommand(FilamentApexChartsCommand::class);
    }

    /**
     * Boots the package and registers the Filament Apex Charts component namespace with Blade.
     *
     * @throws \Exception If the component namespace is not valid.
     */
    public function bootingPackage()
    {
        Blade::componentNamespace('App\\Widgets\\ApexCharts\\Components', 'filament-apex-charts');
    }
}
