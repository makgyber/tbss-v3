<div style="font-size:normal;">
    <span>Team Name:</span>
    <span style="font-weight: bold;">{{strtoupper($team->code)}}</span>
</div>
<div style="font-size:normal;margin-bottom:2px;">
    <span>Technicians:</span>
    <span style="font-size:normal">
        @forelse($team->users as $technician)
        <li>{{ $technician->name }}</li>
        @empty
        <alert>No teams defined</alert>
        @endforelse
    </span>
</div>