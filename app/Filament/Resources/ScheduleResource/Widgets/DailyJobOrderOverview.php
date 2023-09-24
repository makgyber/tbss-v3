<?php

namespace App\Filament\Resources\ScheduleResource\Widgets;

use App\Filament\Resources\ScheduleResource;
use App\Models\JobOrder;
use App\Models\Schedule;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class DailyJobOrderOverview extends BaseWidget
{
    public ?Schedule $record = null;

    protected static ?string $pollingInterval = '300s';

    protected function getCards(): array
    {

        $cards = [];
        foreach (config('tbss.job_order_status') as $key => $value) {
            array_push($cards, Card::make($value, fn () => JobOrder::where('status', $key)->whereDate("target_date", $this->record->visit_at)->count()));
        }
        array_push($cards, Card::make('TOTAL', fn () => JobOrder::whereDate('target_date', $this->record->visit_at)->count()));
        return $cards;
    }
}
