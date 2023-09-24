<?php

namespace App\Filament\Resources\EntomResource\Widgets;

use App\Models\Entom;
use App\Models\JobOrder;
use Filament\Widgets\Widget;

class JobOrderDetails extends Widget
{
    public ?Entom $record = null;
    public ?JobOrder $jobOrder = null;

    protected static string $view = 'filament.resources.entom-resource.widgets.job-order-details';

    public function mount(Entom $record)
    {
        $this->jobOrder = $record->jobOrders->first();

        $this->record = $record;
    }

    public function getColumnSpan(): int | string | array
    {
        return 2;
    }
}
