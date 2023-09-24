<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Forms\Schemas\ScheduleSchema;
use App\Filament\Resources\ScheduleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['scheduled_by'] = Auth()->user()->id;
        return $data;
    }
}
