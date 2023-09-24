<?php

namespace App\Filament\Resources\LeadResource\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class LeadsOverview extends BaseWidget
{
    public ?Lead $record = null;

    protected function getCards(): array
    {
        $cards = [];
        foreach (config('tbss.lead_status') as $key => $value) {
            array_push($cards, Card::make($value, fn () => Lead::where('status', $key)->count()));
        }

        array_push($cards, Card::make('TOTAL', fn () => Lead::all()->count()));
        return $cards;
    }
}
