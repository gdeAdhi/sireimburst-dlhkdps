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
                                        <label for="bobot">Bobot</label>
                                        <input type="text" class="form-control" id="bobot" name="bobot"
                                            placeholder="Bobot">
                                        <span class="error-text" data-error="bobot" style="color: red;"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="faktor_beban">Faktor Beban</label>
                                        <input type="text" class="form-control" id="faktor_beban" name="faktor_beban"
                                            placeholder="Faktor Beban">
                                        <span class="error-text" data-error="faktor_beban" style="color: red;"></span>
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

                                    <div class="form-group">
                                        <label for="kategori">Kategori</label>
                                        <select name="kategori" id="kategori" class="form-control">
                                            <option value="">Pilih Kategori</option>
                                            <option value="Loading">Loading</option>
                                            <option value="Unloading">Unloading</option>
                                        </select>
                                        <span class="error-text" data-error="kategori" style="color: red;"></span>
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
                        <div id="route-breakdown" class="mt-2 text-muted"></div>
                    </div>
                    <div class="col-12" style="margin-bottom: 20px;">
                        <div id="map" style="height: 400px;"></div>
                    </div>
                    {{-- <div id="route-breakdown" class="mt-3"></div> --}}
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
                                            <th>Faktor Beban</th>
                                            <th>Bobot</th>
                                            <th>Kalkulasi</th>
                                            <th>Kategori</th>
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

            $('#kategori').select2({
                placeholder: "Pilih Kategori",
                allowClear: true
            });

            selectedRouteOrder = [];

            $('#id_rute').select2({
                placeholder: "Pilih Rute",
                allowClear: true,
                multiple: true,
                closeOnSelect: false,
                width: '100%'
            });

            $('#id_rute').on('select2:select', function(e) {
                const id = e.params.data.id;
                if (!selectedRouteOrder.includes(id)) {
                    selectedRouteOrder.push(id);
                }
                updateMap();
            });

            $('#id_rute').on('select2:unselect', function(e) {
                const id = e.params.data.id;
                selectedRouteOrder = selectedRouteOrder.filter(item => item !== id);
                updateMap();
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
        let debounceTimer;
        $('#bobot, #faktor_beban').on('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                if (window.lastRouteSegments && window.lastRouteSegments.length > 1) {
                    plotSegmentedRoute(window.lastRouteSegments);
                }
            }, 500); // 500ms delay
        });
    </script>

    <script>
        function updateMap() {
            document.getElementById('route-breakdown').innerHTML = "";
            let selectedRouteIds = selectedRouteOrder;
            console.log("Selected IDs (in order):", selectedRouteIds);

            markers.forEach(marker => map.removeLayer(marker));
            markers = [];
            currentPolylines.forEach(line => map.removeLayer(line));
            currentPolylines = [];

            if (selectedRouteIds.length < 2) return;

            let allRute = @json($rute);
            let selectedRute = allRute
                .filter(r => selectedRouteIds.includes(r.id.toString()))
                .sort((a, b) => {
                    return selectedRouteIds.indexOf(a.id.toString()) - selectedRouteIds.indexOf(b.id.toString());
                });

            routeSegments = [];
            let inCoords = [];
            let outCoords = [];

            selectedRute.forEach((r, index) => {
                let lat = parseFloat(r.latitude);
                let lng = parseFloat(r.longitude);

                if (r.nama.startsWith("OUT - ")) {
                    lat += 0.0001;
                    lng += 0.0001;
                }

                let bgColor = r.nama.startsWith("IN - ") ? "#007bff" : "#28a745";

                let numberIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background:${bgColor};color:white;border-radius:50%;width:30px;height:30px;text-align:center;line-height:30px;font-weight:bold">${index + 1}</div>`,
                    iconSize: [30, 30],
                    iconAnchor: [15, 30]
                });

                let marker = L.marker([lat, lng], {
                        icon: numberIcon
                    })
                    .addTo(map)
                    .bindPopup(`<strong>${r.nama}</strong><br>${r.alamat}`)
                    .bindTooltip(r.nama, {
                        permanent: true,
                        direction: "top",
                        offset: [0, -30]
                    });

                markers.push(marker);

                routeSegments.push({
                    name: r.nama,
                    lat: lat,
                    lng: lng
                });
            });

            window.lastRouteSegments = routeSegments;

            if (routeSegments.length >= 2) {
                plotSegmentedRoute(routeSegments);
            }
        }
    </script>

    <script>
        let currentPolylines = [];
        let map = L.map('map').setView([-8.65, 115.20], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        let markers = [];
        let colorPalette = ['blue', 'green', 'red', 'orange', 'purple', 'brown', 'black'];

        function plotSegmentedRoute(routeSegments) {
            $('#route-breakdown').html(''); // Clear previous
            let breakdownHtml = '';
            let totalDistance = 0;
            let totalFuel = 0;
            let segmentPromises = [];

            const bobot = parseFloat($('#bobot').val());
            const faktorBeban = parseFloat($('#faktor_beban').val());
            const validBobot = !isNaN(bobot) ? bobot : 0; // Default factor to 1 if not set
            const validFaktorBeban = !isNaN(faktorBeban) ? faktorBeban : 0; // Default factor to 1 if not set

            for (let i = 0; i < routeSegments.length - 1; i++) {
                const from = routeSegments[i];
                const to = routeSegments[i + 1];
                const coords = [
                    [from.lng, from.lat],
                    [to.lng, to.lat]
                ];

                segmentPromises.push(
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
                        const decoded = polyline.decode(encoded);

                        const polylineColor = to.name.startsWith("IN -") ? "blue" : "green";
                        const poly = L.polyline(decoded, {
                            color: polylineColor,
                            weight: 5,
                            opacity: 0.8
                        }).addTo(map);

                        currentPolylines.push(poly);

                        const distance = data.routes[0].summary.distance / 1000;
                        totalDistance += distance;

                        // Updated calculation: (konsumsi * distance) * bobot
                        let fuelUsed = null;
                        if (window.selectedKonsumsi && validBobot !== 0 && validBobot !== null &&
                            validFaktorBeban !== 0 && validFaktorBeban !== null) {
                            fuelUsed = (distance / window.selectedKonsumsi) * (1 + (validBobot * validFaktorBeban));
                            totalFuel += fuelUsed;
                        } else {
                            console.log("Invalid Bobot or Konsumsi");
                            fuelUsed = (distance / window.selectedKonsumsi);
                            totalFuel += fuelUsed;
                        }

                        breakdownHtml += `
                    <div>
                        <strong>${from.name}</strong> → <strong>${to.name}</strong>: 
                        ${distance.toFixed(2)} km, 
                        BBM: ${fuelUsed ? fuelUsed.toFixed(2) + ' liter' : '-'}
                    </div>
                `;
                    })
                );
            }

            Promise.all(segmentPromises).then(() => {
                breakdownHtml += `
            <hr>
                <div><strong>Total:</strong> ${totalDistance.toFixed(2)} km,
                BBM: ${totalFuel ? totalFuel.toFixed(2) + ' liter' : '-'}</div>
            `;
                $('#route-breakdown').html(breakdownHtml);

                // Set form values
                $('#perjalanan-form #jarak').val(totalDistance.toFixed(2));
                $('#perjalanan-form #kalkulasi').val(totalFuel ? totalFuel.toFixed(2) : '');
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
                        data: 'faktor_beban',
                        name: 'faktor_beban'
                    },
                    {
                        data: 'bobot',
                        name: 'bobot'
                    },
                    {
                        data: 'kalkulasi',
                        name: 'kalkulasi'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori'
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

                    currentPolylines.forEach(poly => map.removeLayer(poly));
                    currentPolylines = [];

                    markers.forEach(marker => map.removeLayer(marker));
                    markers = [];

                    $('#route-info').html('');
                    $('#route-breakdown').html(''); // Clear previous
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
                    $('#perjalanan-form #bobot').val(response.bobot);
                    $('#perjalanan-form #faktor_beban').val(response.faktor_beban);
                    $('#perjalanan-form #id_rute').val(ruteIds).trigger('change');
                    $('#perjalanan-form #id_user').val(response.id_user).trigger('change');
                    $('#perjalanan-form #kategori').val(response.kategori).trigger('change');
                    window.selectedKonsumsi = response.kendaraan.konsumsi;
                    window.selectedRouteOrder = ruteIds.map(String);

                    $('#bobot').trigger('input');
                    $('#faktor_beban').trigger('input');

                    setTimeout(() => {
                        updateMap();
                    }, 100);
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
                    // Clear map
                    markers.forEach(marker => map.removeLayer(marker));
                    markers = [];
                    currentPolylines.forEach(line => map.removeLayer(line));
                    currentPolylines = [];

                    $('#route-breakdown').html('');
                    $('#perjalanan-form')[0].reset(); // Optional: reset form inputs

                    const selectedRute = response.rute;
                    const routeSegments = [];

                    selectedRute.forEach((r, index) => {
                        let lat = parseFloat(r.latitude);
                        let lng = parseFloat(r.longitude);

                        if (r.nama.startsWith("OUT - ")) {
                            lat += 0.0001;
                            lng += 0.0001;
                        }

                        let bgColor = r.nama.startsWith("IN - ") ? "#007bff" : "#28a745";

                        let numberIcon = L.divIcon({
                            className: 'custom-div-icon',
                            html: `<div style="background:${bgColor};color:white;border-radius:50%;width:30px;height:30px;text-align:center;line-height:30px;font-weight:bold">${index + 1}</div>`,
                            iconSize: [30, 30],
                            iconAnchor: [15, 30]
                        });

                        let marker = L.marker([lat, lng], {
                                icon: numberIcon
                            })
                            .addTo(map)
                            .bindPopup(`<strong>${r.nama}</strong><br>${r.alamat}`)
                            .bindTooltip(r.nama, {
                                permanent: true,
                                direction: "top",
                                offset: [0, -30]
                            });

                        markers.push(marker);

                        routeSegments.push({
                            name: r.nama,
                            lat: lat,
                            lng: lng
                        });
                    });

                    // Store for live update on bobot/faktor_beban change
                    window.lastRouteSegments = routeSegments;
                    $('#bobot').val(response.bobot);
                    $('#faktor_beban').val(response.faktor_beban);
                    window.selectedKonsumsi = response.kendaraan.konsumsi;
                    $('#bobot').trigger('input');
                    $('#faktor_beban').trigger('input');
                    if (routeSegments.length >= 2) {
                        setTimeout(() => {
                            plotSegmentedRoute(routeSegments);
                        }, 50);
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
