@extends('layouts.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Daftar Perjalanan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                            <li class="breadcrumb-item active">Daftar Perjalanan</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- general form elements -->
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Form Perjalanan</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="POST" id="perjalanan-form">
                                @csrf
                                <input type="text" hidden readonly name="id" id="id">
                                <input type="text" hidden readonly name="jarak" id="jarak">
                                <input type="text" hidden readonly name="kalkulasi" id="kalkulasi">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="id_kendaraan">Kendaraan</label>
                                        <select name="id_kendaraan" id="id_kendaraan" class="form-control">
                                            <option value="">Pilih Kendaraan</option>
                                            @foreach ($kendaraan as $data_kendaraan)
                                                <option value="{{ $data_kendaraan->id }}">{{ $data_kendaraan->merk->nama }}
                                                    {{ $data_kendaraan->nama }} ({{ $data_kendaraan->no_polisi }}) -
                                                    {{ $data_kendaraan->konsumsi }}km/l</option>
                                            @endforeach
                                        </select>
                                        <span class="error-text" data-error="id_kendaraan" style="color: red;"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="id_rute">Rute</label>
                                        <select name="id_rute[]" id="id_rute" class="form-control" multiple>
                                            <option value="">Pilih Rute</option>
                                            @foreach ($rute as $data_rute)
                                                <option value="{{ $data_rute->id }}">
                                                    {{ $data_rute->nama }}</option>
                                            @endforeach
                                        </select>
                                        <span class="error-text" data-error="id_rute" style="color: red;"></span>
                                    </div>
                                    @can('Kelola Perjalanan')
                                        <div class="form-group">
                                            <label for="id_user">Driver</label>
                                            <select name="id_user" id="id_user" class="form-control">
                                                <option value="">Pilih Driver</option>
                                                @foreach ($user as $data_user)
                                                    <option value="{{ $data_user->id }}">
                                                        {{ $data_user->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error-text" data-error="id_user" style="color: red;"></span>
                                        </div>
                                    @endcan
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->

                    </div>
                    <div class="col-12" style="margin-bottom: 20px;">
                        <div id="route-info" class="mt-2 text-muted"></div>
                    </div>
                    <div class="col-12" style="margin-bottom: 20px;">
                        <div id="map" style="height: 400px;"></div>
                    </div>
                    <div class="col-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Table Perjalanan</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap" id="perjalanan-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Rute</th>
                                            <th>Kendaraan</th>
                                            <th>Driver</th>
                                            <th>Jarak</th>
                                            <th>Kalkulasi</th>
                                            {{-- <th>Status</th> --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#id_kendaraan').select2({
                placeholder: "Pilih Kendaraan",
                allowClear: true
            });

            $('#id_rute').select2({
                placeholder: "Pilih Rute",
                allowClear: true,
                multiple: true,
                closeOnSelect: false,
                width: '100%'
            });

            $('#id_user').select2({
                placeholder: "Pilih User",
                allowClear: true,
            });
        });
    </script>
    <!-- Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Leaflet Routing Machine -->
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>

    <!-- OpenRouteService Plugin (Correct Version) -->
    <script src="https://unpkg.com/@gegeweb/leaflet-routing-machine-openroute@0.1.10/dist/leaflet-routing-openroute.min.js">
    </script>
    <script src="https://unpkg.com/@mapbox/polyline@1.1.1/src/polyline.js"></script>

    <script>
        let kendaraanData = @json($kendaraan);
        $('#id_kendaraan').on('change', function() {
            let selectedId = $(this).val();
            let kendaraan = kendaraanData.find(k => k.id == selectedId);
            window.selectedKonsumsi = kendaraan ? kendaraan.konsumsi : null;
        });
    </script>

    <script>
        let currentPolyline = null;
        let map = L.map('map').setView([-8.65, 115.20], 13);

        // Add the base tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        // Keep track of markers and route control globally
        let markers = [];
        let routeControl = null;

        // When route selection changes
        $('#id_rute').on('change', function() {
            let selectedRouteIds = $(this).val() || [];

            // 🧹 Remove old markers
            markers.forEach(marker => map.removeLayer(marker));
            markers = [];

            // 🧹 Remove old polyline
            if (currentPolyline) {
                map.removeLayer(currentPolyline);
                currentPolyline = null;
            }

            // 🛑 Exit early if fewer than 2 points
            if (selectedRouteIds.length < 2) return;

            // Get selected rute data
            let allRute = @json($rute);
            let selectedRute = allRute.filter(r => selectedRouteIds.includes(r.id.toString()));

            // Add markers
            let coords = selectedRute.map(r => {
                let lat = parseFloat(r.latitude);
                let lng = parseFloat(r.longitude);

                let marker = L.marker([lat, lng])
                    .addTo(map)
                    .bindPopup(`<strong>${r.nama}</strong><br>${r.alamat}`);
                markers.push(marker);

                return [lng, lat]; // For ORS
            });

            // 🟦 Call route drawer
            plotRouteOnMap(coords);
        });




        // Draw route using ORS
        function plotRouteOnMap(coords) {
            // Ensure coords is valid
            if (coords.length < 2) return;

            fetch('/api/ors-route', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({
                        coordinates: coords
                    })
                })
                .then(res => res.json())
                .then(data => {
                    const encoded = data.routes[0].geometry;
                    const decoded = polyline.decode(encoded); // [lat, lng] pairs

                    // 🟦 Draw polyline
                    currentPolyline = L.polyline(decoded, {
                        color: 'blue'
                    }).addTo(map);
                    map.fitBounds(currentPolyline.getBounds());

                    // ✅ Get distance from ORS in kilometers
                    const distanceInKm = (data.routes[0].summary.distance / 1000).toFixed(2);

                    // ✅ If kendaraan selected and konsumsi is available
                    if (window.selectedKonsumsi) {
                        const fuelUsed = (distanceInKm / window.selectedKonsumsi).toFixed(2);
                        $('#perjalanan-form #jarak').val(distanceInKm);
                        $('#perjalanan-form #kalkulasi').val(fuelUsed);
                        // 💬 Display in alert or update in UI
                        document.getElementById('route-info').innerHTML =
                            `Jarak total: <strong>${distanceInKm} km</strong><br>Perkiraan BBM: <strong>${fuelUsed} liter</strong>`;
                    }
                })

                .catch(err => {
                    console.error('ORS fetch failed:', err);
                    alert('Failed to draw route.');
                });
        }
    </script>

    <script>
        $(function() {
            $('#perjalanan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('perjalanan.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'rute',
                        name: 'rute',
                    },
                    {
                        data: 'kendaraan.nama',
                        name: 'kendaraan.nama'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name'
                    },
                    {
                        data: 'jarak',
                        name: 'jarak'
                    },
                    {
                        data: 'kalkulasi',
                        name: 'kalkulasi'
                    },
                    // {
                    //     data: 'status',
                    //     name: 'status'
                    // },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>

    <script>
        $('#perjalanan-form').on('submit', function(e) {
            e.preventDefault();

            $('.error-text').text('');
            let formData = $('#perjalanan-form').serialize();
            let perjalananId = $('#perjalanan-form #id').val();

            let ajaxMethod = perjalananId ? 'PATCH' : 'POST';
            let ajaxUrl = perjalananId ? `/perjalanan/${perjalananId}` : '/perjalanan';

            const $submitBtn = $(this).find('button[type="submit"]');
            $submitBtn.prop('disabled', true).text('Submitting...');

            $.ajax({
                url: ajaxUrl,
                type: ajaxMethod,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    });
                    $('.form-control').removeClass('is-invalid');
                    $('#perjalanan-form')[0].reset();
                    $('#perjalanan-form select').val(null).trigger('change');
                    $('#perjalanan-table').DataTable().ajax.reload(null, false);

                    if (currentPolyline) {
                        map.removeLayer(currentPolyline);
                        currentPolyline = null;
                    }

                    // 🧹 Clear markers
                    markers.forEach(marker => map.removeLayer(marker));
                    markers = [];

                    // 🧹 Clear optional route info display
                    $('#route-info').html('');
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            $(`span[data-error="${field}"]`).text(messages[0]);
                            $('#' + field).addClass('is-invalid');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An unexpected error occurred.',
                        });
                    }
                },
                complete: function() {
                    $submitBtn.prop('disabled', false).text('Submit');
                }
            });
        });
    </script>

    <script>
        $(document).on('click', '.edit-btn', function() {
            var perjalananId = $(this).data('id');
            const $editBtn = $(this);
            $editBtn.prop('disabled', true).text('Loading...');

            $.ajax({
                url: '/perjalanan/' + perjalananId,
                method: 'GET',
                success: function(response) {
                    let ruteIds = response.rute.map(r => r.id);
                    $('#perjalanan-form #id').val(response.id);
                    $('#perjalanan-form #id_kendaraan').val(response.id_kendaraan).trigger('change');
                    $('#perjalanan-form #id_rute').val(ruteIds).trigger('change');
                    $('#perjalanan-form #id_user').val(response.id_user).trigger('change');
                    // $('#kendaraan-form #status').val(response.status).trigger('change');
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred.',
                    });
                },
                complete: function() {
                    $editBtn.prop('disabled', false).text('Edit');
                }
            });
        });
    </script>

    <script>
        $(document).on('click', '.delete-btn', function() {
            var perjalananId = $(this).data('id');
            const $deleteBtn = $(this);
            $deleteBtn.prop('disabled', true).text('Loading...');

            $.ajax({
                url: '/perjalanan/' + perjalananId,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    });
                    $('#perjalanan-table').DataTable().ajax.reload(null, false);
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred.',
                    });
                },
                complete: function() {
                    $deleteBtn.prop('disabled', false).text('Delete');
                }
            });
        });
    </script>

    <script>
        $(document).on('click', '.maps-btn', function() {
            const perjalananId = $(this).data('id');

            $.ajax({
                url: `/perjalanan/${perjalananId}`,
                method: 'GET',
                success: function(response) {
                    // 🧹 Clear previous route and markers
                    if (currentPolyline) {
                        map.removeLayer(currentPolyline);
                        currentPolyline = null;
                    }

                    markers.forEach(marker => map.removeLayer(marker));
                    markers = [];

                    const selectedRute = response.rute;

                    // Prepare coords: [lng, lat]
                    const coords = selectedRute.map(r => {
                        let lat = parseFloat(r.latitude);
                        let lng = parseFloat(r.longitude);

                        // Add marker
                        const marker = L.marker([lat, lng])
                            .addTo(map)
                            .bindPopup(`<strong>${r.nama}</strong><br>${r.alamat}`);
                        markers.push(marker);

                        return [lng, lat];
                    });

                    if (coords.length >= 2) {
                        plotRouteOnMap(coords);
                    } else {
                        map.fitBounds(L.featureGroup(markers).getBounds());
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat rute perjalanan.',
                    });
                }
            });
        });
    </script>
@endpush
