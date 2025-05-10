@extends('layouts.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Daftar Rute Perjalanan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                            <li class="breadcrumb-item">Daftar Rute Perjalanan</li>
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
                                <h3 class="card-title">Form Rute Perjalanan</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="POST" id="rute-perjalanan-form">
                                @csrf
                                <input type="text" hidden readonly name="id" id="id">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nama">Rute Perjalanan</label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            placeholder="Masukkan Rute Perjalanan">
                                        <span class="error-text" data-error="nama" style="color: red;"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <input type="text" class="form-control" id="alamat" name="alamat"
                                            placeholder="Masukkan Alamat">
                                        <span class="error-text" data-error="alamat" style="color: red;"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="latitude">Latitude</label>
                                        <input type="text" class="form-control" id="latitude" name="latitude"
                                            placeholder="Masukkan Latitude">
                                        <span class="error-text" data-error="latitude" style="color: red;"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="longitude">Longitude</label>
                                        <input type="text" class="form-control" id="longitude" name="longitude"
                                            placeholder="Masukkan Longitude">
                                        <span class="error-text" data-error="longitude" style="color: red;"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">Pilih Status</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                        <span class="error-text" data-error="status" style="color: red;"></span>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->

                    </div>
                    <div class="col-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Table Rute Perjalanan</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap" id="rute-perjalanan-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Rute</th>
                                            <th>Alamat</th>
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                            <th>Status</th>
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
                    <div id="mapModal" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Map View</h5>
                            <button class="btn btn-outline-secondary btn-sm" onclick="$('#mapModal').fadeOut()">✖</button>
                        </div>
                        <div id="map" style="height: 90%; width: 100%;"></div>
                    </div>
                </div>
                <div id="map2" style="height: 500px"></div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#status').select2({
                placeholder: "Pilih Status",
                allowClear: true
            });
        });
    </script>
    <script>
        $(function() {
            $('#rute-perjalanan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('rute.perjalanan.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'latitude',
                        name: 'latitude'
                    },
                    {
                        data: 'longitude',
                        name: 'longitude'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
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
        $('#rute-perjalanan-form').on('submit', function(e) {
            e.preventDefault();

            $('.error-text').text('');
            let formData = $('#rute-perjalanan-form').serialize();
            let rutePerjalananId = $('#rute-perjalanan-form #id').val();

            let ajaxMethod = rutePerjalananId ? 'PATCH' : 'POST';
            let ajaxUrl = rutePerjalananId ? `/rute/perjalanan/${rutePerjalananId}` : '/rute/perjalanan';

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
                    $('#rute-perjalanan-form')[0].reset();
                    $('#rute-perjalanan-table').DataTable().ajax.reload(null, false);
                    loadMapMarkers(map, L.latLngBounds()); // Refresh markers after submission
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
            var rutePerjalananId = $(this).data('id');
            const $editBtn = $(this);
            $editBtn.prop('disabled', true).text('Loading...');

            $.ajax({
                url: '/rute/perjalanan/' + rutePerjalananId,
                method: 'GET',
                success: function(response) {
                    $('#rute-perjalanan-form #id').val(response.id);
                    $('#rute-perjalanan-form #nama').val(response.nama);
                    $('#rute-perjalanan-form #alamat').val(response.alamat);
                    $('#rute-perjalanan-form #latitude').val(response.latitude);
                    $('#rute-perjalanan-form #longitude').val(response.longitude);
                    $('#rute-perjalanan-form #status').val(response.status).trigger('change');
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
            var rutePerjalananId = $(this).data('id');
            const $deleteBtn = $(this);
            $deleteBtn.prop('disabled', true).text('Loading...');

            $.ajax({
                url: '/rute/perjalanan/' + rutePerjalananId,
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
                    $('#rute-perjalanan-table').DataTable().ajax.reload(null, false);
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


    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <!-- Leaflet Routing Machine JS -->
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>

    <!-- Leaflet Routing Machine OpenRouteService Plugin -->
    <script src="https://unpkg.com/@gegeweb/leaflet-routing-machine-openroute@0.1.10/dist/leaflet-routing-openroute.min.js">
    </script>

    <script>
        $(document).ready(function() {
            let map;

            $(document).on('click', '.maps-btn', function() {
                const lat = parseFloat($(this).data('lat'));
                const lng = parseFloat($(this).data('lng'));
                const nama = $(this).data('nama');
                $('#mapModal').fadeIn(200);

                setTimeout(() => {
                    if (map) {
                        map.remove(); // remove previous instance
                    }

                    map = L.map('map').setView([lat, lng], 15);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    L.marker([lat, lng])
                        .addTo(map)
                        .bindPopup(nama)
                        .openPopup();

                    map.invalidateSize(); // fix rendering inside hidden container
                }, 300);
            });
        });
    </script>

    <script>
        function loadMapMarkers(map, bounds) {
            // Clear all existing markers
            map.eachLayer(function(layer) {
                if (layer instanceof L.Marker) {
                    map.removeLayer(layer);
                }
            });

            $.ajax({
                url: "{{ route('rute.perjalanan.maps') }}",
                type: "GET",
                dataType: "json",
                success: function(locations) {
                    if (locations.length === 0) {
                        alert("No route data found.");
                        return;
                    }

                    locations.forEach(loc => {
                        const marker = L.marker([loc.lat, loc.lng])
                            .addTo(map)
                            .bindTooltip(loc.label, {
                                permanent: true,
                                direction: 'top',
                                offset: [-13, -20]
                            });
                        bounds.extend(marker.getLatLng());
                    });

                    map.fitBounds(bounds);
                },
                error: function() {
                    alert("Failed to load route data.");
                }
            });
        }

        let map;
        let bounds;

        $(document).ready(function() {
            map = L.map('map2').setView([0, 0], 2);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            bounds = L.latLngBounds();

            loadMapMarkers(map, bounds);
        });
    </script>
@endpush
