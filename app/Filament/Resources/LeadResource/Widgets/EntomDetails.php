<?php

namespace App\Filament\Resources\LeadResource\Widgets;

use App\Models\Entom;
use App\Models\Lead;
use Filament\Widgets\Widget;

class EntomDetails extends Widget
{
    public ?Lead $record = null;
    public ?Entom $entom = null;
    protected static string $view = 'filament.resources.lead-resource.widgets.entom-details';

    public function mount(Lead $record)
    {
        $this->record = $record;
        $this->entom = $record->entom;
    }
}
