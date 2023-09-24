<?php

namespace App\Filament\Forms\Schemas;

use App\Models\JobOrder;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class ScheduleSchema
{
    public static function getSchema($showTeams = false): array
    {
        return [
            DatePicker::make('visit_at')
                ->label('Day of visit')
                ->unique('schedules', 'visit_at')
                ->required()
                ->reactive(),
            Repeater::make('teams')
                ->relationship('teams')
                ->schema([
                    TextInput::make('code')->required(),
                    CheckboxList::make('users')
                        ->relationship('users', 'name', fn () => User::role('operations')),
                    CheckboxList::make('jobOrders')
                        ->relationship('jobOrders', 'code', function (callable $get) {
                            return JobOrder::whereDate('target_date', $get('../../visit_at'));
                        }),
                ])
                ->visible($showTeams),
        ];
    }
}
