<x-filament::widget>
    <x-filament::card>
        @if($record)
        @include('filament.resources.common.job-order-info', ["jobOrders" => $record->jobOrders])
        @endif
    </x-filament::card>
</x-filament::widget>