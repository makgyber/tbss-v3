<?php

namespace App\Filament\Widgets;

use App\Filament\Tables\Columns\JobOrderColumns;
use App\Models\JobOrder;
use App\Models\Schedule;
use App\Models\Tracker;
use Carbon\Carbon;
use Cheesegrits\FilamentGoogleMaps\Helpers\MapsHelper;
use Cheesegrits\FilamentGoogleMaps\Widgets\MapTableWidget;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Arr;

class ServiceMapTableWidget extends MapTableWidget
{
    protected static ?string $pollingInterval = '5s';

    protected array $mapConfig = [
        'draggable' => false,
        'center'    => [
            'lat' => 14.3419776,
            'lng' => 121.2171392,
        ],
        'zoom'       => 8,
        'fit'        => true,
        'gmaps'      => '',
        'clustering' => true,
    ];


    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Scheduled Job Orders';

    protected static ?bool $clustering = true;

    protected static ?int $zoom = 12;

    protected $selectedDate;

    public function getMapConfig(): string
    {
        return json_encode(
            array_merge($this->mapConfig, [
                'clustering' => self::getClustering(),
                'layers'     => $this->getLayers(),
                'zoom'       => $this->getZoom(),
                'controls'   => $this->controls,
                'fit'        => $this->getFitToBounds(),
                'gmaps'      => MapsHelper::mapsUrl(),
            ])
        );
    }


    protected function getData(): array
    {
        $data = [];


        $jobOrders = $this->getRecords();
        foreach ($jobOrders as $jo) {
            if ($jo->address->latitude && $jo->address->longitude) {
                $name = ($jo->client) ? $jo->client->name : $jo->jobable->client->name;
                $data[] = [
                    'location'  => [
                        'lat' => floatVal($jo->address->latitude),
                        'lng' => floatVal($jo->address->longitude),
                    ],
                    'label' => $jo->teams . $jo->code . ' ' . $name . ' ' . $jo->address->street,
                    'icon' => [
                        'url' => asset('/storage/images/home-selected-state-svgrepo-com.svg'), //url('images/home-selected-state-svgrepo-com.svg'),
                        'type' => 'svg',
                        'scale' => [35, 35],
                    ],
                ];
            }
        }
        // $trackers = $this->getRecords();
        // foreach ($trackers as $tracker) {
        //     $data[] = [
        //         'location'  => [
        //             'lat' => floatVal($tracker->lat),
        //             'lng' => floatVal($tracker->lng),
        //         ],


        //         'label' => $tracker->team->code,

        //         'icon' => [
        //             'url' => asset('/storage/images/car-svgrepo-com.svg'),
        //             'type' => 'svg',
        //             'scale' => [35, 35],
        //         ],
        //     ];
        // }

        return array_merge($data, $this->getTrackers());
    }

    protected function getTableQuery(): Builder
    {
        return JobOrder::query();
        // return Tracker::query()->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('target_date')->date('Y-m-d H:i'),
            TextColumn::make('teams.0.code')->label('team'),
            TextColumn::make('address.client.name')->wrap(),
            TextColumn::make('job_order_type'),
            TextColumn::make('code'),
            TextColumn::make('status'),
            // TextColumn::make('team.code'),
            // TextColumn::make('lat'),
            // TextColumn::make('lng'),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [50, 100, 200, 500];
    }

    protected function getTableFilters(): array
    {

        return [
            Filter::make('target_date')
                ->form([
                    DatePicker::make('from')->label('Service Date'),
                ])
                ->query(function (Builder $query, array $data) {
                    if ($data['from']) {
                        return $query->whereDate('target_date', $data['from']);
                    } else {
                        return $query->whereDate('target_date',  Carbon::now());
                    }
                })

                ->indicateUsing(function (array $data): array {
                    $indicators = [];

                    if ($data['from'] ?? null) {
                        $indicators['from'] = 'Service Date: ' . Carbon::parse($data['from'])->toFormattedDateString();
                    }

                    return $indicators;
                })

        ];
    }

    private function getTrackers(): array
    {
        $data = [];
        // $trackers = Tracker::whereDate('created_at', Carbon::now())->get();
        $trackers = Tracker::all();
        foreach ($trackers as $tracker) {
            $data[] = [
                'location'  => [
                    'lat' => floatVal($tracker->lat),
                    'lng' => floatVal($tracker->lng),
                ],


                'label' => $tracker->team->code,

                'icon' => [
                    'url' => asset('/storage/images/car-svgrepo-com.svg'),
                    'type' => 'svg',
                    'scale' => [35, 35],
                ],
            ];
        }

        return $data;
    }
}
