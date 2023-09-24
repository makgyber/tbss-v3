<?php

namespace App\Filament\Resources\JobOrderResource\Widgets;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Models\Contract;
use App\Models\Entom;
use App\Models\JobOrder;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class ServiceHistory extends BaseWidget
{
    public ?JobOrder $record = null;
    protected int | string | array $columnSpan = 2;
    protected static ?string $heading = "Service History";

    protected function getTableQuery(): Builder
    {

        return JobOrder::whereHasMorph(
            'jobable',
            [Contract::class, Entom::class],

            function (Builder $query) {
                $query->where('jobable_id', $this->record->jobable_id);
            }
        )->orderBy('target_date', 'desc');
    }

    protected function getTableColumns(): array
    {
        return [

            Split::make(
                [
                    TextColumn::make('target_date')->label('Time')->sortable(),
                    TextColumn::make('job_order_type')->sortable()->wrap(),
                    TextColumn::make('status')->sortable(),
                    TextColumn::make('service_interval')->sortable(),
                    TextColumn::make('summary')->sortable(),
                    TextColumn::make('teams.code')->wrap()
                        ->description(function ($record) {
                            $tm = [];
                            $team = $record->teams->first();
                            if (is_null($team)) {
                                return '';
                            }
                            foreach ($team->users as $user) {
                                array_push($tm, $user->name);
                            };
                            return implode(', ', $tm);
                        })
                        ->sortable()
                        ->label('Assigned to'),

                ]
            ),
            Panel::make([
                Stack::make([
                    ViewColumn::make('findings')
                        ->view("filament.tables.columns.tabular-findings"),
                    ViewColumn::make('recommendations')
                        ->view("filament.tables.columns.tabular-recommendations"),
                    ViewColumn::make('comments')
                        ->view("filament.tables.columns.tabular-comments"),
                    ViewColumn::make('comments')
                        ->view("filament.tables.columns.tabular-treatments"),

                ])
            ])->collapsible(),

        ];
    }

    // protected function getTableHeaderActions(): array
    // {
    //     return [
    //         FilamentExportHeaderAction::make('export')
    //     ];
    // }
}
