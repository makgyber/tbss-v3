<?php

namespace App\Filament\Resources\JobOrderResource\Widgets;

use App\Models\JobOrder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class JobOrderOverview extends BaseWidget
{
    public ?JobOrder $record = null;

    protected function getCards(): array
    {
        $cards = [];
        foreach (config('tbss.job_order_status') as $key => $value) {
            array_push($cards, Card::make($value, fn () => JobOrder::where('status', $key)->count()));
        }
        array_push($cards, Card::make('TOTAL', fn () => JobOrder::all()->count()));
        return $cards;
    }
}
