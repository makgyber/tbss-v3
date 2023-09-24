<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use App\Filament\Resources\ScheduleResource\Widgets\DailyJobOrderOverview;
use App\Filament\Resources\ScheduleResource\Widgets\JobOrderReportsOverview;
use App\Filament\Resources\ScheduleResource\Widgets\JobOrdersList;
use App\Filament\Resources\ScheduleResource\Widgets\ScheduleOverview;
use App\Filament\Resources\ScheduleResource\Widgets\TechnicianOverview;
use App\Filament\Resources\ScheduleResource\Widgets\TechniciansList;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchedule extends EditRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),

            Actions\Action::make('view_all_teams')
                ->url(fn () => ScheduleResource::getUrl('service_details', ['record' => $this->data['id']]))
                ->icon('heroicon-s-eye')
                ->color('success')
                ->openUrlInNewTab(true),
            Actions\Action::make('print_all_teams')
                ->url(fn () => ScheduleResource::getUrl('service_details', ['record' => $this->data['id'], 'print' => 1]))
                ->icon('heroicon-s-printer')
                ->color('warning')
                ->openUrlInNewTab(true),
        ];
    }

    public function getTitle(): string
    {
        return 'Manage Schedule for ' . date_format($this->record->visit_at, 'M j, Y');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // ScheduleOverview::class,
            // TechnicianOverview::class,

            // TechniciansList::class,
            DailyJobOrderOverview::class
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            JobOrdersList::class,
        ];
    }
}
