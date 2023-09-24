<x-filament::widget>
    <x-filament::card>
        <h1 class="text-lg font-extrabold text-red-400">Entom Summary</h1>
        <p>
            {{ $record->client_requests}}
        </p>


        @include("filament.resources.common.client-information", ['client' => $record->client])


    </x-filament::card>
</x-filament::widget>