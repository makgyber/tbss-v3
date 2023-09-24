<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CallLogs;
use App\Filament\Widgets\DailyInboundInquiries;
use App\Filament\Widgets\ExtensionLeadsFunnel;
use App\Filament\Widgets\LeadsByServiceTypeChart;
use App\Filament\Widgets\LeadsBySourceChart;
use App\Filament\Widgets\LeadsChart;
use App\Filament\Widgets\ProspectiveLeadsFunnel;
use App\Filament\Widgets\RenewalLeadsFunnel;
use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    protected static bool $shouldRegisterNavigation = false;

    public static function shouldRegisterNavigation(): bool
    {
        return !auth()->user()->hasRole('technician');
    }

    public function mount(): void
    {
        if (auth()->user()->hasRole('technician')) {
            redirect(route('filament.admin.pages.command-center'));
        };
    }

    public function getWidgets(): array
    {
        return [
            LeadsBySourceChart::class,
            LeadsByServiceTypeChart::class,
            ProspectiveLeadsFunnel::class,
            RenewalLeadsFunnel::class,
            ExtensionLeadsFunnel::class,
            // LeadsChart::class
            // ExpiringContracts::class,
            // PendingContracts::class,
        ];
    }
}
