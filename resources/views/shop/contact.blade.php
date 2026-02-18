@extends('layouts.shop')

@section('title', 'Contacto')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Contacto y Ubicación</h2>

        <div class="row">
            {{-- Info de contacto --}}
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-shop"></i> TecnoOutlet</h5>
                        <hr>
                        <p><i class="bi bi-geo-alt-fill text-danger"></i> <strong>Dirección:</strong><br>
                            Calle Larga, 45<br>
                            11500 El Puerto de Santa María<br>
                            Cádiz, España</p>
                        <p><i class="bi bi-telephone-fill text-primary"></i> <strong>Teléfono:</strong><br>
                            +34 956 123 456</p>
                        <p><i class="bi bi-envelope-fill text-success"></i> <strong>Email:</strong><br>
                            info@tecnooutlet.es</p>
                        <p><i class="bi bi-clock-fill text-warning"></i> <strong>Horario:</strong><br>
                            Lun - Vie: 10:00 - 14:00 / 17:00 - 20:30<br>
                            Sáb: 10:00 - 14:00<br>
                            Dom: Cerrado</p>
                    </div>
                </div>
            </div>

            {{-- Mapa de Google Maps --}}
            <div class="col-md-8 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-dark text-white">
                        <i class="bi bi-pin-map-fill"></i> Nuestra ubicación
                    </div>
                    <div class="card-body p-0">
                        <div id="google-map" style="width: 100%; height: 450px;"></div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@push('scripts')
    {{-- Google Maps --}}
    <script>
        function initMap() {
            // Coordenadas de la tienda
            const storeLocation = { lat: 36.5933, lng: -6.2269 };

            const map = new google.maps.Map(document.getElementById('google-map'), {
                zoom: 16,
                center: storeLocation,
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true,
            });

            // Marcador
            const marker = new google.maps.Marker({
                position: storeLocation,
                map: map,
                title: 'TecnoOutlet — Tienda de Informática',
                animation: google.maps.Animation.DROP,
            });

            // Ventana de información
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="max-width: 250px;">
                        <h6 style="margin: 0 0 5px;">🖥️ TecnoOutlet</h6>
                        <p style="margin: 0; font-size: 13px;">
                            Calle Larga, 45<br>
                            11500 El Puerto de Santa María<br>
                            <strong>Tel:</strong> +34 956 123 456<br>
                            <strong>Horario:</strong> L-V 10:00-20:30
                        </p>
                    </div>
                `,
            });

            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });

            // Abrir la ventana de info por defecto
            infoWindow.open(map, marker);
        }
    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY', '') }}&callback=initMap">
    </script>
@endpush
