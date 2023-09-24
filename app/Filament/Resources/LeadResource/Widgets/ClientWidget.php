<?php

namespace App\Filament\Resources\LeadResource\Widgets;

use App\Models\Client;
use Filament\Widgets\Widget;
use App\Models\Lead;

class ClientWidget extends Widget
{

    public ?Lead $record = null;
    public ?Client $client = null;

    protected static string $view = 'filament.resources.lead-resource.widgets.client-widget';

    public function mount(Lead $record)
    {
        $this->record = $record;
        $this->client = $record->client;
    }
}
