<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Component;

class JobOrderList extends Component
{
    protected string $view = 'forms.components.job-order-list';

    public static function make(): static
    {
        return new static();
    }
}
