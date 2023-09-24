<x-filament::card>

    @foreach($this->record->entom as $entom)
    @include('filament.resources.common.entom-details', ['entom' => $entom])

    @if($entom)
    @include('filament.resources.common.job-order-info', ['jobOrders' => $entom->jobOrders])
    @endif
    @endforeach
</x-filament::card>