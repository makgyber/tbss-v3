<x-filament::card>
    @include('filament.resources.common.addresses-list', ['addresses' => $this->record->addresses])
</x-filament::card>