 @include('filament.resources.schedule-resource.pages.team-details', ['team'=>$team])

 @forelse($team->jobOrders as $jo)
 <x-filament::card>
     @include('filament.resources.schedule-resource.pages.job-order-service-details', ['jo' => $jo])
 </x-filament::card>
 @empty
 <alert>No job orders assigned</alert>
 @endforelse