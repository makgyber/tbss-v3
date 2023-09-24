<?php

namespace App\Filament\Resources\ConcernTypeResource\Pages;

use App\Filament\Resources\ConcernTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageConcernTypes extends ManageRecords
{
    protected static string $resource = ConcernTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
