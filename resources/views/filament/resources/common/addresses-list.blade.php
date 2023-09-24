<div class="filament-tables-table-container overflow-x-auto relative dark:border-gray-700 border-t">
    <table class="filament-tables-table w-full text-start divide-y table-auto dark:divide-gray-700">
        <thead>
            <tr class="bg-gray-500/5">

                <th class="filament-tables-header-cell p-0 filament-table-header-cell-is-important">
                    <button type="button" class="flex items-center w-full px-4 py-2 whitespace-nowrap space-x-1 rtl:space-x-reverse font-medium text-sm text-gray-600 dark:text-gray-300 cursor-default ">
                        <span>
                            Address
                        </span>

                    </button>
                </th>
                <th class="filament-tables-header-cell p-0 filament-table-header-cell-body">
                    <button type="button" class="flex items-center w-full px-4 py-2 whitespace-nowrap space-x-1 rtl:space-x-reverse font-medium text-sm text-gray-600 dark:text-gray-300 cursor-default ">
                        <span>
                            Sites
                        </span>

                    </button>
                </th>
            </tr>
        </thead>
        <tbody class="divide-y whitespace-nowrap dark:divide-gray-700">
            @foreach($addresses as $address)
            <tr class="filament-tables-row transition hover:bg-gray-50 dark:hover:bg-gray-500/10">
                <td>
                    <div class="filament-tables-column-wrapper whitespace-normal">
                        <div class="filament-tables-text-column px-4 py-3">
                            {{ $address->fullAddress }}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="filament-tables-column-wrapper">
                        <div class="filament-tables-text-column px-4 py-3">
                            @include("filament.resources.common.sites-list", ['sites' => $address->sites])
                        </div>
                    </div>
                </td>

            </tr>
            @endforeach


        </tbody>
    </table>
</div>