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

class PendingContracts extends BaseWidget
{
    use HasExporter;

    protected static ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Contract::where('status', 'pending')
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('created_at')->date('Y-m-d'),
            TextColumn::make('start')->date('Y-m-d'),
            TextColumn::make('end')->date('Y-m-d'),
            TextColumn::make('code')->url(fn ($record) => ContractResource::getUrl('edit', ['record' => $record->id]))
                ->description(fn ($record) => $record->contract_type),
            TextColumn::make('client.name')->label('Client')->sortable()->searchable()->wrap(),
            TextColumn::make('addresses.street'),
            TextColumn::make('assignedTo.name')->label('Assigned To')->wrap()->searchable(),
        ];
    }
}
