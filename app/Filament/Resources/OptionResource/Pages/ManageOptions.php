<?php

namespace App\Filament\Resources\OptionResource\Pages;

use App\Filament\Resources\OptionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageOptions extends ManageRecords
{
    protected static string $resource = OptionResource::class;
    protected static ?string $title = "Options List";

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
