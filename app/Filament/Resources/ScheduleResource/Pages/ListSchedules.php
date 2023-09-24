<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use App\Filament\Resources\ScheduleResource\Widgets\UnassignedJobOrders;
use App\Filament\Resources\ScheduleResource\Widgets\VisitCalendar;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            VisitCalendar::class,


        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }

    protected function getFooterWidgets(): array
    {
        return [
            UnassignedJobOrders::class,
        ];
    }

    public function getFooterWidgetsColumns(): int|array
    {
        return 1;
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [36, 72];
    }
}
