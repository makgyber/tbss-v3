<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Carbon\Carbon;
use Filament\Widgets\BarChartWidget;
use Filament\Widgets\LineChartWidget;
use Filament\Widgets\PieChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class LeadsBySourceChart extends BarChartWidget
{
    protected static ?string $pollingInterval = null;
    protected static ?string $heading = 'Leads by Source';

    protected function getData(): array
    {

        $datasets = [];

        $backgroundColors = [
            'rgba(255, 99, 132, 0.5)',
            'rgba(255, 159, 64, 0.5)',
            'rgba(255, 205, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(153, 102, 255, 0.5)',
            'rgba(201, 203, 207, 0.5)',
            'rgba(255, 205, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(153, 102, 255, 0.5)',
            'rgba(201, 203, 207, 0.5)'
        ];

        $counter = 0;
        foreach (config('tbss.lead_source') as $type) {
            $data = Trend::query(Lead::where('source', $type))
                ->between(
                    start: now()->startOfYear(),
                    end: now()->endOfYear(),
                )
                ->perMonth()
                ->count();

            array_push($datasets, [
                'label' => $type,
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                'backgroundColor' => $backgroundColors[$counter],
                'borderColor' => $backgroundColors[$counter],
                'borderWidth' => 1,
            ]);
            $counter++;
        }


        return [
            'datasets' => $datasets,
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }
}
