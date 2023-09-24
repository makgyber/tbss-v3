<?php

namespace App\Filament\Resources\PestTypeResource\Pages;

use App\Filament\Resources\PestTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePestTypes extends ManageRecords
{
    protected static string $resource = PestTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
