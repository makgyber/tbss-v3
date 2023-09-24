<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Resources\Pages\Page;

class ServiceHistory extends Page
{
    protected static string $resource = ClientResource::class;

    protected static string $view = 'filament.resources.client-resource.pages.service-history';

    public function mount(): void
    {
        static::authorizeResourceAccess();
    }
}
