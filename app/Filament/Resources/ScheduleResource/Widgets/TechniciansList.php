<?php

namespace App\Filament\Resources\ScheduleResource\Widgets;

use App\Models\Schedule;
use App\Models\Team;
use App\Models\User;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class TechniciansList extends BaseWidget
{
    public ?Schedule $record = null;

    protected function getTableQuery(): Builder
    {
        return User::role(config('tbss.operations.technician.role'));
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')->sortable(),
            TextColumn::make('teams')
                ->label('Team')
                ->getStateUsing(function ($record) {
                    $team = Team::with('users')
                        ->whereHas('users', fn ($query) => $query->where('team_user.user_id', $record->id))
                        ->where('schedule_id', $this->record->id)->get();
                    return $team->pluck('code')->all();
                }),
        ];
    }
}
