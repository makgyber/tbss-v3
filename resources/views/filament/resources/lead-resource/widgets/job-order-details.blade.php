<x-filament::widget>
    <x-filament::card>

        @if(isset($jobOrders))
        @include("filament.resources.common.job-order-info", ['jobOrders' => $jobOrders])
        @else
        <div>No job orders found</div>
        @endif
    </x-filament::card>
</x-filament::widget>