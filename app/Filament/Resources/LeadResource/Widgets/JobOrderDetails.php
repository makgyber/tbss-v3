<?php

namespace App\Filament\Resources\LeadResource\Widgets;

use App\Models\Entom;
use App\Models\JobOrder;
use App\Models\Lead;
use Filament\Widgets\Widget;

class JobOrderDetails extends Widget
{
    public ?Lead $record = null;
    public ?Entom $entom = null;
    public $jobOrders = null;
    protected static string $view = 'filament.resources.lead-resource.widgets.job-order-details';

    public function getColumnSpan(): int | string | array
    {
        return 2;
    }

    public function mount(Lead $record)
    {
        $this->record = $record;
        $this->entom = $record->entom;
        $this->jobOrders = $record->entom?->jobOrders;
    }
}
