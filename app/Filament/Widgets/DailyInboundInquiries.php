<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DailyInboundInquiries extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected function getTableQuery(): Builder
    {
        return User::select(
            'users.id',
            "received_on",
            "name",
            DB::raw('sum(if(source="website", 1, 0)) as website'),
            DB::raw('sum(if(source="fb", 1, 0)) as fb'),
            DB::raw('sum(if(source="referral", 1, 0)) as referral'),
            DB::raw('sum(if(source="existing client", 1, 0)) as existing_client'),
            DB::raw('sum(if(source="returning client", 1, 0)) as returning_client'),
            DB::raw('count(source) as total'),
        )
            ->leftJoin('leads', 'leads.assigned_to', '=', 'users.id')
            ->whereDate("received_on", now())
            ->groupBy('assigned_to');
    }

    protected function getTableColumns(): array
    {
        return [
            // TextColumn::make('received_on')->date(),
            TextColumn::make('name')->label("Received by"),
            TextColumn::make('website'),
            TextColumn::make('fb'),
            TextColumn::make('referral'),
            TextColumn::make('existing_client'),
            TextColumn::make('returning_client'),
            TextColumn::make('total'),
        ];
    }
}
