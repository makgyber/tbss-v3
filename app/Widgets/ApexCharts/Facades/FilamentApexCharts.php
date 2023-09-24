<?php

namespace App\Widgets\ApexCharts\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Widgets\ApexCharts\FilamentApexCharts
 */
class FilamentApexCharts extends Facade
{
    protected static function getFacadeAccessor()
    {
        return App\Widgets\ApexCharts\FilamentApexCharts::class;
    }
}
