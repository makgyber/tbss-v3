<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use App\Models\Team;
use Carbon\Carbon;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\View\View;

class TeamScheduleDetails extends Page
{
    public ?Team $record = null;
    // protected static string $layout = 'filament.resources.schedule-resource.pages.print';
    protected ?string $heading = '';

    public $visit  = null;
    public $teams = null;

    protected static string $resource = ScheduleResource::class;

    protected static string $view = 'filament.resources.schedule-resource.pages.team-schedule-details';

    public function mount(Team $record)
    {
        $this->visit = (new Carbon($record->schedule->visit_at, 'Asia/Manila'))->toFormattedDayDateString();
        $this->teams = [$this->record];
    }

    public function render(): View
    {
        if (request('print')) {
            return view(static::$view, $this->getViewData())
                ->layout('filament.resources.schedule-resource.pages.print', $this->getLayoutData());
        } else {
            return view(static::$view, $this->getViewData())
                ->layout(static::$layout, $this->getLayoutData());
        }
    }
}
