<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DailyInboundInquiries;
use App\Filament\Widgets\DailyLeadActivity;
use App\Filament\Widgets\ExpiringContracts;
use App\Filament\Widgets\PendingContracts;
use Filament\Pages\Page;

class SalesDashboard extends Page
{
    // protected static ?string $title = 'Sales Dashboard: Contracts';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.sales-dashboard';
    protected ?string $heading = "Sales Dashboard for Contracts";

    protected ?string $subheading = "Contract activity reminders and alerts";

    protected function getHeaderWidgets(): array
    {
        return [
            DailyInboundInquiries::class,
            DailyLeadActivity::class,
            ExpiringContracts::class,
            PendingContracts::class,
        ];
    }
}
