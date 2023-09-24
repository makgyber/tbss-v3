@php
$recommendations = $getState()
@endphp
@if(count($recommendations))
<table class="table w-full pt-8 rounded border mb-10 border-spacing-10">
    <caption class="text-xs font-semibold text-blue-500 py-4">Recommendations</caption>
    <thead>
        <tr class="text-xs bg-gray-500 text-white">
            <th>Type</th>
            <th>Priority</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        @foreach($recommendations as $recommendation)
        <tr class="text-xs  pb-1">
            <td>{{$recommendation['service_type']}}</td>
            <td>{{$recommendation['priority']}}</td>
            <td>{{$recommendation['description']}}</td>
        </tr>
        @if($recommendation->getFirstMediaUrl('attachedrecommendations')!="")
        <tr>
            <td colspan="3"><img src="{{$recommendation->getFirstMediaUrl('attachedrecommendations')}}" class="object-contain h-48" /></td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>
@endif