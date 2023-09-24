<div class="border border-gray-300 shadow-sm rounded filament-tables-container dark:divide-gray-700 w-full  my-6 p-4">
    <table class="filament-tables-table w-full text-start divide-y table-fixed dark:divide-gray-700">
        <caption class="caption-top text-left text-blue-500">Findings</caption>
        <thead>
            <tr class="bg-gray-500/5">
                <th class="filament-tables-header-cell p-0 font-medium text-sm">Location</th>
                <th class="filament-tables-header-cell p-0 font-medium text-sm">Infestation</th>
                <th class="filament-tables-header-cell p-0 font-medium text-sm">Degree</th>
                <th class="filament-tables-header-cell p-0 font-medium text-sm">Remarks</th>
            </tr>
        </thead>
        <tbody class="divide-y  dark:divide-gray-700">
            @foreach($getState() as $index => $row)
            <tr class="filament-tables-row transitio hover:bg-gray-50 dark:hover:bg-gray-500/10 even:bg-gray-100 dark:even:bg-gray-900">
                <td class="filament-tables-cell dark:text-white">
                    {{ $row->location }}
                </td>
                <td class="filament-tables-cell dark:text-white">
                    {{ $row->infestation }}
                </td>
                <td class="filament-tables-cell dark:text-white">
                    {{ $row->degree }}
                </td>
                <td class="filament-tables-cell dark:text-white flex-wrap">
                    {!! html_entity_decode($row->remarks) !!}
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>