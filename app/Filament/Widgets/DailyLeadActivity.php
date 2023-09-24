<?php

namespace App\Filament\Widgets;

use App\Models\Contract;
use App\Models\Lead;
use App\Models\User;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

use function Ramsey\Uuid\v1;

class DailyLeadActivity extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected function getTableQuery(): Builder
    {
        return User::select(
            'users.id',
            "name",
            DB::raw('sum(if(status="pending", 1, 0)) as pending'),
            DB::raw('sum(if(status="inquiry", 1, 0)) as inquiry'),
            DB::raw('sum(if(status="pending entom",1, 0)) as pending_entom'),
            DB::raw('sum(if(status="entom done", 1, 0)) as entom_done'),
            DB::raw('sum(if(status="pending proposal", 1, 0)) as pending_proposal'),
            DB::raw('sum(if(status="proposal submitted", 1, 0)) as proposal_submitted'),
            DB::raw('sum(if(status="closed", 1, 0)) as closed'),
            DB::raw('sum(if(status="declined", 1, 0)) as declined'),
            DB::raw('count(status) as total'),
        )
            ->join('leads', 'leads.assigned_to', '=', 'users.id')
            ->whereDate("leads.updated_at", now())
            ->groupBy('assigned_to');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')->label('Assigned To'),
            TextColumn::make('inquiry'),
            TextColumn::make('pending_entom'),
            TextColumn::make('entom_done'),
            TextColumn::make('pending_proposal'),
            TextColumn::make('proposal_submitted'),
            TextColumn::make('closed'),
            TextColumn::make('declined'),
            TextColumn::make('total'),
        ];
    }
}
