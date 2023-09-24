<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use App\Models\Schedule;
use Carbon\Carbon;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\View\View;

class ServiceDetails extends Page
{
    // protected static string $layout = 'filament.resources.schedule-resource.pages.print';
    // protected static string $layout = 'layouts.app';
    protected static ?string $title = 'Service Schedule Details';
    protected ?string $heading = '';

    public ?Schedule $record = null;
    public $visit = null;
    public $teams = null;

    protected static string $resource = ScheduleResource::class;

    protected static string $view = 'filament.resources.schedule-resource.pages.service-details';

    public function mount(Schedule $record)
    {
        $this->visit = (new Carbon($record->visit_at, 'Asia/Manila'))->toFormattedDayDateString();
        $this->teams = $this->record->teams;
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
