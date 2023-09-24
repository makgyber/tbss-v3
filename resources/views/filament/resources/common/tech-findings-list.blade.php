@php
$findings = $getState()

@endphp

@if(count($findings))
<table class="table w-full pt-8  rounded-lg mb-10 border">
    <caption class="text-xs text-blue-500 font-semibold py-4">Findings</caption>
    <thead>
        <tr class="text-xs text-white border-spacing-1 pb-1 bg-gray-500">
            <th>Location</th>
            <th>Infestation</th>
            <th>Degree</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($findings as $finding)

        <tr class="p-4 text-xs border-spacing-2 border-white border">
            <td class="p-4 border border-white">{{$finding['location']}}</td>
            <td class="p-4 border border-white">{{$finding['infestation']}}</td>
            <td class="p-4 border border-white">{{$finding['degree']}}</td>
            <td class="p-4 border border-white">{!!$finding['remarks']!!}</td>
        </tr>

        <tr>
            <td colspan="4" class="">
                <div class="container grid grid-cols-4 gap-2 mx-auto">
                    @foreach($finding->getMedia('attachedfindings') as $media)
                    <div class="w-full rounded-lg border bg-black overflow-hidden">
                        <img src="{{$media->getUrl()}}" alt="image" class="w-full  flex-grow p-1 rounded-lg">
                    </div>
                    @endforeach
                </div>
            </td>
        </tr>

        @endforeach
    </tbody>
</table>
@endif