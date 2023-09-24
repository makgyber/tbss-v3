<?php

namespace App\Filament\Widgets;

use App\Models\Schedule;
use App\Models\Team;
use App\Models\Tracker as ModelsTracker;
use Carbon\Carbon;
use Filament\Widgets\Concerns\CanPoll;
use Filament\Widgets\Widget;

class Tracker extends Widget
{
    use CanPoll;

    protected static string $view = 'filament.widgets.tracker';
    public $lat = null;
    public $lng = null;

    protected $listeners = ['positionChanged' => 'updatePosition'];


    protected function getPollingInterval(): ?string
    {
        return '60s';
    }

    public function mount()
    {
        $this->fill(['lat' => $this->lat, 'lng' => $this->lng]);
    }

    public function updatePosition($lat, $lng)
    {

        $now = Carbon::now();

        ModelsTracker::whereDate('created_at', '<', $now)->delete();

        $schedule = Schedule::select("id")->whereDate("visit_at", $now)->first();

        $team = Team::with('users')
            ->whereHas('users', fn ($query) => $query->where('team_user.user_id', auth()->user()->id))
            ->where('schedule_id', $schedule->id)->first();

        if ($team) {
            $tracker = ModelsTracker::where('team_id', $team->id)->whereDate('created_at', $now)->first();


            if ($tracker) {
                $tracker->lat = $lat;
                $tracker->lng = $lng;
                $tracker->save();
            } else {
                ModelsTracker::create([
                    'team_id' =>  $team->id,
                    'lat' => $lat,
                    'lng' => $lng,
                ]);
            }
        }
    }
}
