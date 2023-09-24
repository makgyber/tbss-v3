<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Forms\ClientForm;
use App\Filament\Forms\Schemas\AddressSchema;
use App\Filament\Forms\Schemas\SiteSchema;
use App\Filament\Resources\ClientResource;
use Closure;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClient extends CreateRecord
{

    protected static string $resource = ClientResource::class;

    protected function getFormSchema(): array
    {
        return ClientForm::getSchema();
    }
}
