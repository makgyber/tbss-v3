<x-filament::page>

    <x-filament::card>
        {{ $record->code }}
        {{ $record->concerns }}
        {{ $record->status }}
    </x-filament::card>

    @if($record->client)
    @include('filament.resources.common.client-information', ['client' => $record->client])
    @endif
</x-filament::page>