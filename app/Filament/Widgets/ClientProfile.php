<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class ClientProfile extends Widget
{
    public ?Model $record = null;
    protected static string $view = 'filament.widgets.client-profile';

    
}