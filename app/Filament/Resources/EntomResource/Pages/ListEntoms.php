<?php

namespace App\Filament\Resources\EntomResource\Pages;

use App\Filament\Resources\EntomResource;
use App\Filament\Resources\EntomResource\Widgets\EntomOverview;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEntoms extends ListRecords
{
    protected static string $resource = EntomResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            EntomOverview::class,
        ];
    }
}
