<div class="p-2 w-2 h-2 m-4">
    <h2 class="text-base font-bold p-2 bg-slate-500">{{ $team->code }}</h2>
    <div class="p-2 text-gray-100 m-4">
        <h2 class="text-base font-bold">Members</h2>
        <p class="text-sm p-2 ">
            @foreach($team->users as $technician)
            {{$technician->name}},
            @endforeach
        </p>
    </div>

    <div class="p-2 text-gray-100 m-4">
        <h2 class="text-base font-bold">Job Orders</h2>

        @foreach($team->jobOrders as $jo)
        <div class="p-2">
            <h2 class="text-base font-semibold">
                <span class="text-red-500">{{date_format($jo->target_date, 'h:i a')}}</span>: {{$jo->code}}
            </h2>
            <p>Contact Details:<br>
                {{($jo->jobable_id) ? $jo->jobable->client->name : $jo->client->name}}
                {{$jo->site->contact_name}}
                {{$jo->site->contact_number}}
            </p>
            <p>Location:<br>
                {{$jo->site->label}},
                {{$jo->address?->street}},
                {{$jo->address?->city->name}}
            </p>
        </div>
        @endforeach
    </div>
</div>