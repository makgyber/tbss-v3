<?php

namespace App\Filament\Resources\EntomResource\Pages;

use App\Filament\Resources\EntomResource;
use App\Filament\Resources\EntomResource\Widgets\EntomDetails;
use App\Filament\Resources\EntomResource\Widgets\JobOrderDetails;
use App\Filament\Resources\LeadResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEntom extends ViewRecord
{
    protected static string $resource = EntomResource::class;

    protected function getHeaderWidgets(): array
    {
        return [

            EntomDetails::class,
            JobOrderDetails::class,
            // ClientWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\Action::make('View parent lead')
            //     ->url(function ($livewire) {
            //         if ($livewire->data['lead_id']) return LeadResource::getUrl('view', [$livewire->data['lead_id']]);
            //     }),
            // Actions\DeleteAction::make(),
        ];
    }
}
