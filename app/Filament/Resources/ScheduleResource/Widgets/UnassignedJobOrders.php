<?php

namespace App\Filament\Resources\ScheduleResource\Widgets;

use App\Filament\Resources\ClientResource;
use App\Filament\Resources\JobOrderResource;
use App\Filament\Tables\Columns\JobOrderColumns;
use App\Models\JobOrder;
use App\Models\Schedule;
use Carbon\Carbon;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class UnassignedJobOrders extends BaseWidget
{
    protected ?Schedule $record = null;

    protected function getTableQuery(): Builder
    {
        return JobOrder::doesntHave('teams');
    }

    protected function getTableColumns(): array
    {
        return JobOrderColumns::getColumns();
        // return [
        //     TextColumn::make('target_date')
        //         ->date('Y-m-d')->sortable(),
        //     TextColumn::make('code')
        //         ->url(fn ($record) => JobOrderResource::getUrl('edit', $record))->searchable(),
        //     TextColumn::make('jobable.client.name')->searchable()
        //         ->wrap()
        //         ->url(fn ($record) => ClientResource::getUrl('view', $record?->jobable->client->id)),
        //     TextColumn::make('address.city.name')->sortable(),
        //     TextColumn::make('days_from_target')
        //         ->color(function ($record) {
        //             return (Carbon::now()->isBefore($record->target_date)) ? 'success' : 'danger';
        //         })
        //         ->default(fn ($record) => Carbon::now()->diffForHumans($record->target_date)),


        // ];
    }
}
