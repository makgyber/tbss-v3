<?php

namespace App\Filament\Resources\ConcernResource\Pages;

use App\Filament\Resources\ConcernResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConcerns extends ListRecords
{
    protected static string $resource = ConcernResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableFiltersFormColumns(): int
    {
        return 2;
    }
}
