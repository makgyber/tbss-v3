<x-filament::widget>
    @php
    $polling_interval = config('tbss.tracker_polling_interval');
    @endphp
    @hasrole('technician')
    @push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {

            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);

                } else {
                    x.innerHTML = "Geolocation is not supported by this browser.";
                }
            }

            function showPosition(position) {
                console.log(position.coords.latitude, position.coords.longitude);
                Livewire.emit('positionChanged', position.coords.latitude, position.coords.longitude);
            }

            getLocation();
            setInterval(getLocation, @php echo $polling_interval @endphp);

        });
    </script>
    @endpush
    @endhasrole
</x-filament::widget>