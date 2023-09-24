<h1 class="text-lg text-red-500">Entom Summary</h1>
<div class="filament-tables-table-container overflow-x-auto relative dark:border-gray-700 border-t">
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
                            {{$entom?->status}}
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
                            {{ $entom?->target_date }}
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="filament-tables-row transition hover:bg-gray-50 dark:hover:bg-gray-500/10">
                <td class="filament-tables-cell dark:text-white">
                    <div class="filament-tables-column-wrapper">
                        <div class="filament-tables-text-column px-4 py-3">Requested By</div>
                    </div>
                </td>
                <td>
                    <div class="filament-tables-column-wrapper">
                        <div class="filament-tables-text-column px-4 py-3">
                            {{ $entom?->requestedBy?->name }}
                        </div>
                    </div>

                </td>
            </tr>
            <tr class="filament-tables-row transition hover:bg-gray-50 dark:hover:bg-gray-500/10">
                <td class="filament-tables-cell dark:text-white">
                    <div class="filament-tables-column-wrapper">
                        <div class="filament-tables-text-column px-4 py-3">Assigned To
                        </div>
                    </div>
                </td>
                <td class="filament-tables-cell dark:text-white">
                    <div class="filament-tables-column-wrapper">
                        <div class="filament-tables-text-column px-4 py-3">
                            {{ $entom?->assignedTo?->name }}
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="filament-tables-row transition hover:bg-gray-50 dark:hover:bg-gray-500/10">
                <td class="filament-tables-cell dark:text-white">
                    <div class="filament-tables-column-wrapper">
                        <div class="filament-tables-text-column px-4 py-3">Client Requests:</div>
                    </div>
                </td>
                <td>
                    <div class="filament-tables-column-wrapper whitespace-normal">
                        <div class="filament-tables-text-column px-4 py-3">
                            {{ $entom?->client_requests }}
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="filament-tables-row transition hover:bg-gray-50 dark:hover:bg-gray-500/10">
                <td class="filament-tables-cell dark:text-white">
                    <div class="filament-tables-column-wrapper">
                        <div class="filament-tables-text-column px-4 py-3">Remarks</div>
                    </div>
                </td>
                <td>
                    <div class="filament-tables-column-wrapper whitespace-normal">
                        <div class="filament-tables-text-column px-4 py-3">

                            {{ $entom?->remarks }}
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>

    </table>
</div>