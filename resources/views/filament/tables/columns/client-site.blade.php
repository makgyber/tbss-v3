<div class="flex-grow text-xs">
    <h3 class="text-sm">{{ $getState()->label }}</h3>
    <p>
        {{ $getState()->address->street }}, {{ $getState()->address->barangay?->name }}
    </p>
    <p>
        {{ $getState()->address->city->name }}, {{ $getState()->address->province->name }}
    </p>
    <p>
        {{ $getState()->address->region->name }}
    </p>

</div>


</div>