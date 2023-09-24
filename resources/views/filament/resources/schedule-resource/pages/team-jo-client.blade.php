 <x-filament::card>
     <div style="padding-top:8px">
         <table class="table rounded border table-auto">
             <thead class="table-header-group">
                 <tr class="table-header-row">
                     <th>Time</th>
                     <th>Service Type</th>
                     <th>Client</th>
                     <th>Location</th>
                     <th>Contacts</th>
                     <th>Summary</th>
                 </tr>
             </thead>
             <tbody>
                 @forelse($team->jobOrders as $jo)
                 <tr class="border border-spacing-4 py-10 table-row">
                     <td class="table-cell  p-4">{{date_format($jo->target_date, 'h:i a')}}</td>
                     <td class="table-cell p-4">{{$jo->job_order_type}}</td>
                     <td class="table-cell p-4">{{$jo->client?->name}}</td>
                     <td class="table-cell p-4">{{$jo->address->fullAddress}}</td>
                     <td class="table-cell p-4">@php
                         if(!is_null($jo->client))
                         foreach($jo->client?->contact_information as $contacts) echo "<div>" . $contacts['type'] . ": " . $contacts['value']. "</div>"
                         @endphp
                     </td>
                     <td class="table-cell p-4">{{$jo->summary}}</td>
                 </tr>
                 @empty
                 <tr>
                     <td>
                         <alert>No job orders assigned</alert>
                     </td>
                 </tr>
                 @endforelse
             </tbody>
         </table>
     </div>
 </x-filament::card>