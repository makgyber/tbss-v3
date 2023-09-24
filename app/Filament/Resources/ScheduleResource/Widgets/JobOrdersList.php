<?php

namespace App\Filament\Resources\ScheduleResource\Widgets;

use App\Filament\Resources\ClientResource;
use App\Filament\Resources\JobOrderResource;
use App\Models\JobOrder;
use App\Models\JobOrderType;
use App\Models\Schedule;
use Carbon\Carbon;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\EloquentSortable\Sortable;

class JobOrdersList extends BaseWidget
{

    public ?Schedule $record = null;

    protected int | string | array $columnSpan = 2;


    protected function getTableQuery(): Builder
    {
        return JobOrder::withCount(["findings", 'recommendations'])->whereDate('target_date', $this->record->visit_at);
    }

    protected function getTableColumns(): array
    {
        return [

            TextColumn::make('target_date')->label('Time')->date('h:i a')->sortable(),
            TextColumn::make('teams.code')->wrap()
                ->sortable()
                ->label('Assigned to'),
            TextColumn::make('job_order_type')->sortable()->wrap(),
            TextColumn::make('status')->sortable(),
            TextColumn::make('code')->searchable()
                ->wrap()
                ->sortable()
                ->url(fn ($record) => JobOrderResource::getUrl('edit', ['record' => $record->id])),
            TextColumn::make('client.name')->wrap()->searchable()->sortable()
                ->url(fn ($record) => ClientResource::getUrl('view', ['record' =>  $record->client_id])),
            TextColumn::make('address.fullAddress')->wrap()
                ->searchable(['addresses.street']),
            TextColumn::make('findings_count')->label('Findings')->sortable(),
            TextColumn::make('recommendations_count')->label('Recommendations')->sortable(),
            TextColumn::make('Aging')
                ->color(function ($record) {
                    return (Carbon::now()->isBefore($record->target_date)) ? 'success' : 'danger';
                })
                ->default(fn ($record) => Carbon::now()->diffForHumans($record->target_date)),

        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('status')
                ->options(config("tbss.job_order_status")),
            SelectFilter::make('job_order_type')
                ->options(fn () => JobOrderType::all()->pluck('name', 'name'))
        ];
    }
}
