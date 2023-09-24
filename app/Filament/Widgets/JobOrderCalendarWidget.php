<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\JobOrderResource;
use App\Filament\Resources\ScheduleResource;
use App\Models\Schedule;
use Filament\Widgets\Widget;
use App\Widgets\FullCalendarWidget;

class JobOrderCalendarWidget extends FullCalendarWidget
{


    protected array $fullCalendarConfig = [
        'headerToolbar' => [
            'left' => 'prev,next today',
            'center' => 'title',
            'right' => 'dayGridMonth,timeGridWeek,timeGridDay',
        ],
    ];

    public function getViewData(): array
    {
        $schedules = Schedule::where('visit_at', '>', now()->subDays(1))->get();
        $events = [];
        foreach ($schedules as $sched) {

            $item = [
                'id' => $sched->id,
                'start' => $sched->visit_at,
                'title' => 'no schedules',
                'url' => ScheduleResource::getUrl('edit', ["record" => $sched->id]),
            ];

            if ($sched->teams()->count() === 0) {
                array_push($events, $item);
                continue;
            }

            $color = [
                'unscheduled' => 'red',
                'confirmed' => 'orange',
                'scheduled' => '',
                'started' => 'yellow',
                'completed' => 'darkgreen',
                'serviced' => 'darkgreen',
                'postponed' => 'grey',
                'cancelled' => 'grey',
                '' => '',
            ];

            foreach ($sched->teams as $team) {
                foreach ($team->jobOrders as $jo) {
                    if (in_array($jo->status, ['cancelled', '', 'postponed'])) {
                        continue;
                    }
                    $item['title'] = $jo->code;
                    $item['start'] = $jo->target_date;
                    $item['color'] = $color[$jo->status];
                    $item['url'] = JobOrderResource::getUrl('edit', ['record' => $jo->id]);
                    array_push($events, $item);
                }
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
