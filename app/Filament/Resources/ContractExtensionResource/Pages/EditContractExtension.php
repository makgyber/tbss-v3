<?php

namespace App\Filament\Resources\ContractExtensionResource\Pages;

use App\Filament\Resources\ContractExtensionResource;
use App\Filament\Resources\ContractExtensionResource\Widgets\ClientProfile;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContractExtension extends EditRecord
{
    protected static string $resource = ContractExtensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ClientProfile::class,
        ];
    }
}
