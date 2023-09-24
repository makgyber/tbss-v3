<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ClientResource;
use App\Filament\Resources\ContractResource;
use App\Filament\Resources\JobOrderResource;
use App\Filament\Resources\LeadResource;
use App\Models\Client;
use App\Models\Comment;
use App\Models\Lead;
use App\Traits\HasExporter;
use Carbon\Carbon;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CallLogs extends BaseWidget
{
    use HasExporter;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Call / Activity Logs';

    protected function getTableQuery(): Builder
    {
        return Comment::whereDate('created_at', '>=', Carbon::now()->subDays(7))
            ->with('clients', 'leads', 'contracts')->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id'),
            IconColumn::make('is_important')->boolean(),
            TextColumn::make('created_at')->label('Logged Date')->sortable()->dateTime("M d, Y h:i a"),
            TextColumn::make('source')->default(function ($record) {
                if ($record->clients->count())
                    return 'Client';
                if ($record->leads->count())
                    return 'Lead';
                if ($record->contracts->count())
                    return 'Contract';
                if ($record->jobOrders->count())
                    return 'Job Order';
            })->url(function ($record) {
                if ($record->clients->count()) {
                    return ClientResource::getUrl('view', ['record' => $record->clients->first()->id, 'activeRelationManager' => 1]);
                }

                if ($record->leads->count()) {
                    return LeadResource::getUrl('view', ['record' => $record->leads->first()->id, 'activeRelationManager' => 0]);
                }

                if ($record->contracts->count()) {
                    return ContractResource::getUrl('view', ['record' => $record->contracts->first()->id, 'activeRelationManager' => 0]);
                }
                if ($record->jobOrders->count()) {
                    return JobOrderResource::getUrl('edit', ['record' => $record->jobOrders->first()->id, 'activeRelationManager' => 6]);
                }
            })->description(function ($record) {
                if ($record->leads->count()) {
                    $lead = $record->leads->first();
                    return $lead->status;
                }

                if ($record->contracts->count()) {
                    return $record->contracts->first()->status;
                }

                if ($record->jobOrders->count()) {
                    return $record->jobOrders->first()->status;
                }
            })->wrap(),

            TextColumn::make('clients.name')->label('Client Name')->sortable()->default(function ($record) {
                if ($record->clients->count()) {
                    return $record->clients->first->name;
                }

                if ($record->leads->count()) {
                    return $record->leads->first()->client?->name;
                }

                if ($record->contracts->count()) {
                    return $record->contracts->first()->client->name;
                }
                if ($record->jobOrders->count()) {

                    return $record->jobOrders->first()->client->name;
                }
            })->wrap(),
            TextColumn::make('body')->label('Details')->default(function ($record) {
                if ($record->body != '') {
                    return $record->body;
                }

                if ($record->leads->count()) {
                    $lead = $record->leads->first();
                    return $lead->concerns;
                }
            })->wrap(),
            TextColumn::make('commentedBy.name')->label('Received by')->sortable(),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [5, 10, 25, 50];
    }
}
