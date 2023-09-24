<div class="flex-grow text-xs">
    @if($getState())

    <table class="table">
        <thead>
            <tr class="bg-white text-gray-500 border-2 border-slate-500">
                <th>Key</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($getState() as $key => $value)

            <tr>
                <td class="border-2 bg-gray-500 px-4 py-3 border-black">{{ $key }}</td>
                <td class="border-2 bg-gray-500 px-4 py-3  border-black">

                    <pre> {{ is_array($value) ? (json_encode($value,JSON_PRETTY_PRINT)) : $value }} </pre>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @else
    --
    @endif
</div>


</div>