<?php

namespace App\Filament\Resources\FindingResource\Pages;

use App\Filament\Resources\FindingResource;
use App\Models\JobOrder;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateFinding extends CreateRecord
{

    protected static string $resource = FindingResource::class;

    public function getHeading(): string | Htmlable
    {
        $jo = JobOrder::find(request("job_order_id"));

        return "Create  Finding " . $jo?->code;
    }
}
