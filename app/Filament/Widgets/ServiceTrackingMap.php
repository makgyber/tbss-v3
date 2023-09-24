<?php

namespace App\Filament\Widgets;

use App\Models\JobOrder;
use App\Models\Tracker;
use Carbon\Carbon;
use Cheesegrits\FilamentGoogleMaps\Widgets\MapWidget;

class ServiceTrackingMap extends MapWidget
{
    protected static ?string $heading = 'Service Schedule Tracking';

    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = '3s';
    protected int | string | array $columnSpan = 'full';

    protected static ?bool $clustering = false;

    protected static ?bool $fitToBounds = true;

    protected static ?int $zoom = 12;

    protected static string $view = 'filament.widgets.filament-google-maps-widget';

    protected function getData(): array
    {
        $trackers = Tracker::whereDate('created_at', Carbon::now())->get();
        // $trackers = Tracker::all();
        $data = [];

        foreach ($trackers as $tracker) {

            $data[] = [
                'location'  => [
                    'lat' => $tracker->lat ? round(floatval($tracker->lat), static::$precision) : 0,
                    'lng' => $tracker->lng ? round(floatval($tracker->lng), static::$precision) : 0,
                ],

                'label'     => $tracker->team->code,

                'icon' => [
                    'url' => asset('/storage/images/car-svgrepo-com.svg'),
                    'type' => 'svg',
                    'scale' => [35, 35],
                ],
            ];
        }


        return array_merge($data, $this->getJobOrderLocations());
    }

    protected function getJobOrderLocations(): array
    {
        $data = [];
        $jobOrders = JobOrder::whereDate("target_date", Carbon::now())->get();
        foreach ($jobOrders as $jo) {
            if ($jo->address->latitude && $jo->address->longitude) {
                $name = ($jo->client) ? $jo->client->name : $jo->jobable->client->name;
                $data[] = [
                    'location'  => [
                        'lat' => floatVal($jo->address->latitude),
                        'lng' => floatVal($jo->address->longitude),
                    ],
                    'label' => $jo->code . ' ' . $name . ' ' . $jo->address->street,
                    'icon' => [
                        'url' => asset('/storage/images/home-selected-state-svgrepo-com.svg'),
                        'type' => 'svg',
                        'scale' => [35, 35],
                    ],
                ];
            }
        }
        return $data;
    }

    public function mountTableAction()
    {
        return $this;
    }

    public function getHeight()
    {
        return '70vh';
    }
}
