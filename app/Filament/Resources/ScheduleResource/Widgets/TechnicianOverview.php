<?php

namespace App\Filament\Resources\ScheduleResource\Widgets;

use App\Models\Schedule;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class TechnicianOverview extends BaseWidget
{
    public ?Schedule $record = null;

    protected function getCards(): array
    {
        $cards = [];

        $unavailableCount = $this->getUnavailableCount();
        $deployedCount = $this->getDeployedCount();
        $totalCount = $this->getTotalCount();

        array_push($cards, Card::make('Unavailable Technicians (day-off, leave, absent)', $unavailableCount));
        array_push($cards, Card::make('Deployed / Assigned Technicians', $deployedCount));
        array_push($cards, Card::make('Available / unscheduled Technicians', $totalCount - $unavailableCount - $deployedCount));
        array_push($cards, Card::make('TOTAL Number of Technicians', $totalCount));

        return $cards;
    }

    private function getDeployedCount(): int
    {
        return User::whereHas("roles", function ($q) {
            $q->where("name", config('tbss.operations.technician.role'));
        })
            ->whereHas('teams', function ($q) {
                $q->where('schedule_id', $this->record->id);
            })
            ->count();
    }

    private function getUnavailableCount(): int
    {
        return User::whereHas("roles", function ($q) {
            $q->where("name", config('tbss.operations.technician.role'));
        })
            ->whereHas("leaves", function ($q1) {
                $q1->where('leave_date', $this->record->visit_at);
            })
            ->count();
    }

    private function getTotalCount(): int
    {
        return User::whereHas("roles", function ($q) {
            $q->where("name", config('tbss.operations.technician.role'));
        })->count();
    }
}
