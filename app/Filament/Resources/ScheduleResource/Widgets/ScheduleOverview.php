<?php

namespace App\Filament\Resources\ScheduleResource\Widgets;

use App\Models\JobOrder;
use App\Models\Schedule;
use App\Models\Team;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ScheduleOverview extends BaseWidget
{
    public ?Schedule $record = null;

    protected function getCards(): array
    {
        $cards = [];
        $statuses = config('tbss.job_order_status');

        foreach ($statuses as $status) {
            array_push($cards, Card::make(ucwords($status . ' Job Orders'), $this->getJobOrderCountByStatus($status)));
        }
        array_push($cards, Card::make('Total Job Orders for today', JobOrder::whereDate('target_date', $this->record->visit_at)->count()));
        array_push($cards, Card::make('Teams for today', $this->getTeamCount()));
        return $cards;
    }

    private function getTeamCount(): int
    {
        return  Team::where('schedule_id', $this->record->id)
            ->count();
    }

    private function getJobOrderCountByStatus($status): int
    {
        return JobOrder::whereDate('target_date', $this->record->visit_at)
            ->where('status', $status)
            ->count();
    }

    private function getTotalCount(): int
    {
        return User::whereHas("roles", function ($q) {
            $q->where("name", config('tbss.operations.technician.role'));
        })->count();
    }
}
