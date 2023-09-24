<?php

namespace App\Filament\Resources\ScheduleResource\Widgets;

use App\Filament\Resources\ScheduleResource;
use App\Models\Schedule;
use App\Widgets\FullCalendarWidget;

class VisitCalendar extends FullCalendarWidget
{
    protected int | string | array $columnSpan = '1';

    public function getViewData(): array
    {
        $schedules = Schedule::all();
        $events = [];
        foreach ($schedules as $sched) {

            $item = [
                'id' => $sched->id,
                'start' => $sched->visit_at,
                'title' => 'no schedules',
                'url' => ScheduleResource::getUrl('edit', ['record' => $sched->id]),
            ];

            if ($sched->teams->count() === 0) {
                array_push($events, $item);
                continue;
            }

            foreach ($sched->teams as $team) {

                // if ($team->jobOrders->count() === 0) {
                $item['title'] = $team->code;
                array_push($events, $item);
                // continue;
                // }

                // foreach ($team->jobOrders as $jo) {
                //     $item['start'] = $jo->target_date;
                //     $item['title'] = $team->code . ' : ' . $jo->code;
                //     array_push($events, $item);
                // }
            }
        }

        return $events;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(?array $event = null): bool
    {
        return false;
    }
}
