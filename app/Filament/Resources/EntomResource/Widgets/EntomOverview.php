<?php

namespace App\Filament\Resources\EntomResource\Widgets;

use App\Models\Entom;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class EntomOverview extends BaseWidget
{
    public ?Entom $record = null;

    protected function getCards(): array
    {
        $cards = [];
        foreach (config('tbss.entom_status') as $key => $value) {
            array_push($cards, Card::make($value, fn () => Entom::where('status', $key)->count()));
        }

        array_push($cards, Card::make('TOTAL', fn () => Entom::all()->count()));
        return $cards;
    }
}
