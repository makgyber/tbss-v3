<x-filament::page>
    <div class="text-red-500 font-bold">{{ $visit }} - Team Schedule</div>
    <hr />
    @forelse($teams as $team)
    @include("filament.resources.schedule-resource.pages.team-service-details", ['team'=>$team])
    @empty
    <div class="">No schedules defined today</div>
    @endforelse
</x-filament::page>