<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Filament\Resources\UserResource;
use App\Models\Leave;
use App\Widgets\FullCalendarWidget;

class LeavesCalendar extends FullCalendarWidget
{
    public function getViewData(): array
    {
        $leaves = Leave::all();
        $events = [];
        foreach ($leaves as $leave) {
            array_push($events, [
                'id' => $leave->id,
                'start' => $leave->leave_date,
                'title' => $leave->user->name,
                'url' => UserResource::getUrl('edit', ['record' => $leave->user_id]),
            ]);
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
