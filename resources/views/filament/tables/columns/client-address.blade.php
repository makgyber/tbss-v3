<div class="flex-grow">
    <ol>
        @foreach($getState() as $index=>$address)
        <li class="text-sm">
            {{ $address->street }}, {{ $address->barangay?->name }}
            <br>
            {{ $address->city->name }}
            , {{ $address->province->name }}
            <br>{{ $address->region->name }}
        </li>
        @endforeach
    </ol>


</div>