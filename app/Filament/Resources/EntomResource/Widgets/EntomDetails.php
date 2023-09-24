<?php

namespace App\Filament\Resources\EntomResource\Widgets;

use App\Models\Entom;
use Filament\Widgets\Widget;

class EntomDetails extends Widget
{
    public ?Entom $record = null;
    protected static string $view = 'filament.resources.entom-resource.widgets.entom-details';

    public function mount(Entom $record)
    {
        $this->record = $record;
    }

    public function getColumnSpan(): int | string | array
    {
        return 2;
    }
}
