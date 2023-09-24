<h1 class="text-lg text-red-500 py-8">Job Order Details</h1>

@if(isset($jobOrders))

@forelse($jobOrders as $jobOrder)

<table class="filament-tables-table w-full text-start divide-y table-auto dark:divide-gray-700">
    <thead>
        <tr class="bg-gray-500/5">

            <th class="filament-tables-header-cell p-0 filament-table-header-cell-is-important">
                <button type="button" class="flex items-center w-full px-4 py-2 whitespace-nowrap space-x-1 rtl:space-x-reverse font-medium text-sm text-gray-600 dark:text-gray-300 cursor-default ">
                    <span>
                        Field
                    </span>

                </button>
            </th>
            <th class="filament-tables-header-cell p-0 filament-table-header-cell-body">
                <button type="button" class="flex items-center w-full px-4 py-2 whitespace-nowrap space-x-1 rtl:space-x-reverse font-medium text-sm text-gray-600 dark:text-gray-300 cursor-default ">
                    <span>
                        Value
                    </span>

                </button>
            </th>
        </tr>
    </thead>
    <tbody class="divide-y whitespace-nowrap dark:divide-gray-700">
        <tr class="filament-tables-row transition hover:bg-gray-50 dark:hover:bg-gray-500/10">

            <td>
                <div class="filament-tables-column-wrapper">
                    <div class="filament-tables-text-column px-4 py-3">
                        Status
                    </div>
                </div>
            </td>
            <td>
                <div class="filament-tables-column-wrapper">
                    <div class="filament-tables-text-column px-4 py-3">
                        {{$jobOrder->status}}
                    </div>
                </div>
            </td>

        </tr>
        <tr class="filament-tables-row transition hover:bg-gray-50 dark:hover:bg-gray-500/10">

            <td>
                <div class="filament-tables-column-wrapper">
                    <div class="filament-tables-text-column px-4 py-3">
                        Code
                    </div>
                </div>
            </td>
            <td>
                <div class="filament-tables-column-wrapper">
                    <div class="filament-tables-text-column px-4 py-3">
                        {{$jobOrder->code}}
                    </div>
                </div>
            </td>

        </tr>
        <tr class="filament-tables-row transition hover:bg-gray-50 dark:hover:bg-gray-500/10">
            <td class="filament-tables-cell dark:text-white">
                <div class="filament-tables-column-wrapper">
                    <div class="filament-tables-text-column px-4 py-3">Summary</div>
                </div>
            </td>
            <td>
                <div class="filament-tables-column-wrapper whitespace-normal">
                    <div class="filament-tables-text-column px-4 py-3 inline-flex items-center space-x-1 rtl:space-x-reverse">
                        {{$jobOrder->summary}}
                    </div>
                </div>
            </td>
        </tr>
        <tr class="filament-tables-row transition hover:bg-gray-50 dark:hover:bg-gray-500/10">
            <td class="filament-tables-cell dark:text-white">
                <div class="filament-tables-column-wrapper">
                    <div class="filament-tables-text-column px-4 py-3">Target Date</div>
                </div>
            </td>
            <td>
                <div class="filament-tables-column-wrapper whitespace-normal">
                    <div class="filament-tables-text-column px-4 py-3 inline-flex items-center space-x-1 rtl:space-x-reverse">
                        {{$jobOrder->target_date}}
                    </div>
                </div>
            </td>
        </tr>
        <tr class="filament-tables-row transition hover:bg-gray-50 dark:hover:bg-gray-500/10">
            <td class="filament-tables-cell dark:text-white">
                <div class="filament-tables-column-wrapper">
                    <div class="filament-tables-text-column px-4 py-3">Created By</div>
                </div>
            </td>
            <td>
                <div class="filament-tables-column-wrapper">
                    <div class="filament-tables-text-column px-4 py-3">
                        {{$jobOrder->createdBy?->name}}
                    </div>
                </div>

            </td>
        </tr>
    </tbody>

</table>



<x-filament::card>
    @include('filament.resources.common.instructions-list', ['instructions' => $jobOrder->instructions])
</x-filament::card>

<x-filament::card>
    @include('filament.resources.common.findings-list', ['findings' => $jobOrder->findings])
</x-filament::card>
<x-filament::card>
    @include('filament.resources.common.recommendations-list', ['recommendations' => $jobOrder->recommendations])
</x-filament::card>
@empty

<h1>No job order found</h1>

@endforelse

@endif