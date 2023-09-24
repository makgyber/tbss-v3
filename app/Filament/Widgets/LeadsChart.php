<?php

namespace App\Filament\Widgets;

use App\Widgets\ApexCharts\Widgets\ApexChartWidget;

class LeadsChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static string $chartId = 'leadsChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Current Leads';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            'chart' => [
                'type' => 'bar',
                'height' => 350,
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => "true",
                    'isFunnel' => "true",
                ],
            ],
            'series' => [
                [
                    'name' => "Funnel Series",
                    'data' => [
                        [
                            'x' => "Sourced",
                            'y' => 138
                        ],
                        [
                            'x' => "Assessed",
                            'y' => 99
                        ],
                        [
                            'x' => "Technical",
                            'y' => 75
                        ],
                        [
                            'x' => "Offered",
                            'y' => 30
                        ],
                    ],
                ],
            ]
        ];
    }

    private function getData()
    {
    }
}
