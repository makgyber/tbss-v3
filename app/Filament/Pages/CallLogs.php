<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CallLogs as WidgetsCallLogs;
use Filament\Pages\Page;

class CallLogs extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.call-logs';

    protected function getHeaderWidgets(): array
    {
        return [
            WidgetsCallLogs::class
        ];
    }
}
