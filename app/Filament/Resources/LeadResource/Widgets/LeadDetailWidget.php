<?php

namespace App\Filament\Resources\LeadResource\Widgets;

use Filament\Widgets\Widget;
use App\Models\Lead;

class LeadDetailWidget extends Widget
{
    public ?Lead $record = null;
    protected static string $view = 'filament.resources.lead-resource.widgets.lead-detail-widget';

    public function mount(Lead $record)
    {
        $this->record = $record;
    }
}
