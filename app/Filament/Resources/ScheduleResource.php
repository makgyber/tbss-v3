<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Schemas\ScheduleSchema;
use App\Filament\Resources\ClientResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Filament\Resources\ScheduleResource\RelationManagers\TeamsRelationManager;
use App\Filament\Resources\ScheduleResource\Widgets\UnassignedJobOrders;
use App\Filament\Resources\ScheduleResource\Widgets\VisitCalendar;
use App\Models\Schedule;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?int $navigationSort = 35;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(ScheduleSchema::getSchema(false));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('visit_at')->date('Y-m-d')->searchable()
                    ->color(fn ($record) => ($record->visit_at)->format('Y-m-d') == Carbon::now()->format('Y-m-d') ? 'danger' : '')
                    ->extraAttributes(["class" => 'text-orange-400'])
                    ->description(function ($record) {
                        $teamCount = count($record->teams);
                        if ($teamCount) {
                            return "Teams: " . $teamCount;
                        }
                        return "No teams assigned today";
                    }),
                // Panel::make([
                //     ViewColumn::make('teams')
                //         ->view('filament.tables.columns.schedule-teams'),
                // ])
                //     ->collapsible(true),

            ])
            ->filters([
                Filter::make('visit_at')
                    ->form([
                        DatePicker::make('from')->default(Carbon::now()->startOfMonth(0)),
                        DatePicker::make('until')->default(Carbon::now()->lastOfMonth()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('visit_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('visit_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators['from'] = 'Created from ' . Carbon::parse($data['from'])->toFormattedDateString();
                        }

                        if ($data['until'] ?? null) {
                            $indicators['until'] = 'Created until ' . Carbon::parse($data['until'])->toFormattedDateString();
                        }

                        return $indicators;
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print')
                    ->url(fn ($record) => ScheduleResource::getUrl('service_details', $record))
                    ->color('success')
                    ->icon('heroicon-s-printer')
                    ->openUrlInNewTab(true),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 7,
            ]);;
    }

    public static function getRelations(): array
    {
        return [
            TeamsRelationManager::class,
            ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
            'service_details' => Pages\ServiceDetails::route('/{record}/service-details'),
            'team_schedule_details' => Pages\TeamScheduleDetails::route('/{record}/team-schedule-details'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            // UnassignedJobOrders::class,
            // VisitCalendar::class,
        ];
    }
}
