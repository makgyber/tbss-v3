<div class="flex-grow text-sm">

    <h1 class="text-lg font-bold ">Teams</h1>

    @forelse($getState() as $team)
    @include('filament.tables.columns.team-detail', ['team' => $team])
    @empty
    <h3 class="text-md text-red-500">add teams for this day</h3>

    @endif
</div>