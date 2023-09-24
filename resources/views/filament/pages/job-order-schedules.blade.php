<x-filament::page>
    @once('css')
    <link rel="stylesheet" href="{{url('css/filament-fullcalendar.css')}}" />
    @endonce

    <x-filament::card>
        <h1>Color codes are based on job order status:</h1>
        <div class="w-full flex-auto">
            <div><span class="text-red-500">unscheduled</span></div>
            <div><span class="text-blue-500">scheduled</span></div>
            <div><span class="text-yellow-500">started</span></div>
            <div><span style="color: green;">serviced</span></div>
        </div>
    </x-filament::card>
</x-filament::page>