@extends('layouts.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Daftar Kendaraan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                            <li class="breadcrumb-item active">Daftar Kendaraan</li>
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
                                <h3 class="card-title">Form Kendaraan</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="POST" id="kendaraan-form">
                                @csrf
                                <input type="text" hidden readonly name="id" id="id">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="id_tipe">Tipe Kendaraan</label>
                                        <select name="id_tipe" id="id_tipe" class="form-control">
                                            <option value="">Pilih Tipe Kendaraan</option>
                                            @foreach ($tipe_kendaraan as $data_tipe_kendaraan)
                                                <option value="{{ $data_tipe_kendaraan->id }}">
                                                    {{ $data_tipe_kendaraan->nama }}</option>
                                            @endforeach
                                        </select>
                                        <span class="error-text" data-error="id_tipe" style="color: red;"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="id_merk">Merk Kendaraan</label>
                                        <select name="id_merk" id="id_merk" class="form-control">
                                            <option value="">Pilih Merk Kendaraan</option>
                                            @foreach ($merk_kendaraan as $data_merk_kendaraan)
                                                <option value="{{ $data_merk_kendaraan->id }}">
                                                    {{ $data_merk_kendaraan->nama }}</option>
                                            @endforeach
                                        </select>
                                        <span class="error-text" data-error="id_merk" style="color: red;"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">Nama Kendaraan</label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            placeholder="Masukkan Nama Kendaraan">
                                        <span class="error-text" data-error="nama" style="color: red;"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="no_polisi">Nomor Polisi / Plat Nomor</label>
                                        <input type="text" class="form-control" id="no_polisi" name="no_polisi"
                                            placeholder="Masukkan Nomor Polisi Kendaraan">
                                        <span class="error-text" data-error="no_polisi" style="color: red;"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="warna">Warna</label>
                                        <input type="text" class="form-control" id="warna" name="warna"
                                            placeholder="Masukkan Warna Kendaraan">
                                        <span class="error-text" data-error="warna" style="color: red;"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="bahan_bakar">Bahan Bakar</label>
                                        <select name="bahan_bakar" id="bahan_bakar" class="form-control">
                                            <option value="">Pilih Bahan Bakar</option>
                                            <option value="Pertalite">Pertalite</option>
                                            <option value="Pertamax">Pertamax</option>
                                            <option value="Solar">Solar</option>
                                            <option value="Dexlite">Dexlite</option>
                                            <option value="Biosolar">Biosolar</option>
                                            <option value="Listrik">Listrik</option>
                                        </select>
                                        <span class="error-text" data-error="bahan_bakar" style="color: red;"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="konsumsi">Konsumsi Bahan Bakar (km/liter)</label>
                                        <input type="number" class="form-control" id="konsumsi" name="konsumsi"
                                            placeholder="Masukkan Konsumsi Bahan Bakar Kendaraan">
                                        <span class="error-text" data-error="konsumsi" style="color: red;"></span>
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
                                <h3 class="card-title">Table Kendaraan</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap" id="kendaraan-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tipe Kendaraan</th>
                                            <th>Merk Kendaraan</th>
                                            <th>Nama Kendaraan</th>
                                            <th>No Polisi</th>
                                            <th>Warna</th>
                                            <th>Bahan Bakar</th>
                                            <th>Konsumsi</th>
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
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#id_tipe').select2({
                placeholder: "Pilih Tipe Kendaraan",
                allowClear: true
            });

            $('#id_merk').select2({
                placeholder: "Pilih Merk Kendaraan",
                allowClear: true
            });

            $('#id_status').select2({
                placeholder: "Pilih Status",
                allowClear: true
            });
        });
    </script>
    <script>
        $(function() {
            $('#kendaraan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('kendaraan.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'tipe.nama',
                        name: 'tipe.nama'
                    },
                    {
                        data: 'merk.nama',
                        name: 'merk.nama'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_polisi',
                        name: 'no_polisi'
                    },
                    {
                        data: 'warna',
                        name: 'warna'
                    },
                    {
                        data: 'bahan_bakar',
                        name: 'bahan_bakar'
                    },
                    {
                        data: 'konsumsi',
                        name: 'konsumsi'
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
        $('#kendaraan-form').on('submit', function(e) {
            e.preventDefault();

            $('.error-text').text('');
            let formData = $('#kendaraan-form').serialize();
            let kendaraanId = $('#kendaraan-form #id').val();

            let ajaxMethod = kendaraanId ? 'PATCH' : 'POST';
            let ajaxUrl = kendaraanId ? `/kendaraan/${kendaraanId}` : '/kendaraan';

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
                    $('#kendaraan-form')[0].reset();
                    $('#kendaraan-table').DataTable().ajax.reload(null, false);
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
            var KendaraanId = $(this).data('id');
            const $editBtn = $(this);
            $editBtn.prop('disabled', true).text('Loading...');

            $.ajax({
                url: '/kendaraan/' + KendaraanId,
                method: 'GET',
                success: function(response) {
                    $('#kendaraan-form #id').val(response.id);
                    $('#kendaraan-form #id_tipe').val(response.id_tipe);
                    $('#kendaraan-form #id_merk').val(response.id_merk);
                    $('#kendaraan-form #nama').val(response.nama);
                    $('#kendaraan-form #no_polisi').val(response.no_polisi);
                    $('#kendaraan-form #bahan_bakar').val(response.bahan_bakar);
                    $('#kendaraan-form #warna').val(response.warna);
                    $('#kendaraan-form #konsumsi').val(response.konsumsi).trigger('change');
                    $('#kendaraan-form #status').val(response.status).trigger('change');
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
            var KendaraanId = $(this).data('id');
            const $deleteBtn = $(this);
            $deleteBtn.prop('disabled', true).text('Loading...');

            $.ajax({
                url: '/kendaraan/' + KendaraanId,
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
                    $('#kendaraan-table').DataTable().ajax.reload(null, false);
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
@endpush
