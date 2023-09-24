<x-filament::card>
    @if($this->record->contracts)


    @foreach($this->record->contracts as $contract)
    @include('filament.resources.common.contract-details', ['contract' => $contract])

    @if($contract)
    @include('filament.resources.common.job-order-info', ['jobOrders' => $contract->jobOrders])
    @endif
    @endforeach
    @else
    <h1>No contracts</h1>
    @endif
</x-filament::card>