<x-filament::page>
    <h4 class="text-lg text-red-500 font-bold">{{ $visit }} - Overall Schedule</h4>

    @forelse($teams as $team)
    <hr />
    <p>
        @include('filament.resources.schedule-resource.pages.team-details', ['team' => $team])
        @include('filament.resources.schedule-resource.pages.team-jo-client', ['team' => $team])
    </p>
    @empty
    <div class="">No schedules defined today</div>
    @endforelse
</x-filament::page>