<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\LeadResource;
use App\Models\Lead;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;

class SummaryLead extends Page
{

    public ?Lead $record = null;

    protected static string $resource = LeadResource::class;

    protected static string $view = 'filament.resources.lead-resource.pages.summary-lead';

    public function mount(Lead $record)
    {
        $this->record = $record;
    }
}
