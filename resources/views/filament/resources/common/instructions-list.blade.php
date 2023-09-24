<h4 class="text-md text-red-500">Instructions</h4>
<div class="filament-tables-table-container overflow-x-auto relative dark:border-gray-700 border-t">
    <table class="filament-tables-table w-full text-start divide-y table-auto dark:divide-gray-700">
        <thead>
            <tr class="bg-gray-500/5">
                <th class="filament-tables-header-cell p-0 filament-table-header-cell-is-important"></th>
                <th class="filament-tables-header-cell p-0 filament-table-header-cell-is-important">
                    <button type="button" class="flex items-center w-full px-4 py-2 whitespace-nowrap space-x-1 rtl:space-x-reverse font-medium text-sm text-gray-600 dark:text-gray-300 cursor-default ">
                        <span>
                            Instruction
                        </span>

                    </button>
                </th>
                <th class="filament-tables-header-cell p-0 filament-table-header-cell-body">
                    <button type="button" class="flex items-center w-full px-4 py-2 whitespace-nowrap space-x-1 rtl:space-x-reverse font-medium text-sm text-gray-600 dark:text-gray-300 cursor-default ">
                        <span>
                            Remarks
                        </span>
                    </button>
                </th>
            </tr>
        </thead>
        <tbody class="divide-y whitespace-nowrap dark:divide-gray-700">
            @forelse($instructions as $index => $instruction)
            <tr class="filament-tables-row transition hover:bg-gray-50 dark:hover:bg-gray-500/10">
                <td>
                    <div class="filament-tables-column-wrapper">
                        <div class="filament-tables-text-column px-4 py-3">
                            {{ $index+1 }}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="filament-tables-column-wrapper">
                        <div class="filament-tables-text-column px-4 py-3">
                            {{ $instruction->instruction }}
                        </div>
                    </div>
                </td>

                <td>
                    <div class="filament-tables-column-wrapper whitespace-normal">
                        <div class="filament-tables-text-column px-4 py-3 inline-flex items-center space-x-1 rtl:space-x-reverse">

                        </div>
                    </div>
                </td>

            </tr>
            @empty

            <tr class=bg-gray-500">
                <td class="px-4 py-3 text-blue-200">No instructions yet</td>
            </tr>
            @endforelse
        </tbody>

    </table>
</div>