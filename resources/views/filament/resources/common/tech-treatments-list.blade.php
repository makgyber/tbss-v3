@php
$treatments = $getState()
@endphp
@if(count($treatments))
<table class="table w-full  pt-8 rounded mb-10 border border-spacing-10">
    <caption class="text-xs font-semibold text-blue-500 py-4">Treatments</caption>
    <thead>
        <tr class="text-xs bg-gray-500 text-white">
            <th>Type</th>
            <th>Location</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
        @foreach($treatments as $treatment)
        <tr class="text-xs  pb-1">
            <td>{{$treatment['treatment_type']}}</td>
            <td>{{$treatment['location']}}</td>
            <td>{{$treatment['quantity']}}</td>
        </tr>
        @if($treatment->getFirstMediaUrl('attachedtreatments')!="")
        <tr>
            <td colspan="3"><img src="{{$treatment->getFirstMediaUrl('attachedtreatments')?:''}}" class="object-contain h-48" /></td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>
@endif