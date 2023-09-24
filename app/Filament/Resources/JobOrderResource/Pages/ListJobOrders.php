<?php

namespace App\Filament\Resources\JobOrderResource\Pages;

use App\Filament\Resources\JobOrderResource;
use App\Filament\Resources\JobOrderResource\Widgets\JobOrderOverview;
use App\Models\JobOrder;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListJobOrders extends ListRecords
{
    protected static string $resource = JobOrderResource::class;

    protected function getTableQuery(): Builder
    {
        return JobOrder::withCount(['findings', 'recommendations']);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            JobOrderOverview::class,
        ];
    }
}
