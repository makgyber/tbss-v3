<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContractResource;
use App\Models\Contract;
use App\Traits\HasExporter;
use Carbon\Carbon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ExpiringContracts extends BaseWidget
{
    // use HasExporter;

    protected static ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Contract::where('status', 'closed')
            ->whereDate(
                'end',
                '<=',
                Carbon::now()->addDays(60)
            )
            ->orderBy('end', 'asc')
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            IconColumn::make('alert')->boolean()->default(fn ($record) => !($record->end <= Carbon::now())),
            TextColumn::make('end')->label('Expiry')->date('Y-m-d')->sortable()->color(fn ($record) =>  $record->end <= Carbon::now() ? 'danger' : ''),
            TextColumn::make('code')->url(fn ($record) => ContractResource::getUrl('edit', ['record' => $record->id]))
                ->description(fn ($record) => $record->contract_type),
            TextColumn::make('client.name')->label('Client')->sortable()->searchable()->wrap(),
            TextColumn::make('addresses.street'),
            TextColumn::make('assignedTo.name')->label('Assigned To')->wrap()->searchable(),
        ];
    }
}
