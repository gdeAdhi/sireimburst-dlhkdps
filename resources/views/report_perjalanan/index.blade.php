@extends('layouts.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Daftar Report Perjalanan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                            <li class="breadcrumb-item active">Daftar Report Perjalanan</li>
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
                                <h3 class="card-title">Form Report Perjalanan</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="POST" id="report-form">
                                @csrf
                                <input type="text" hidden readonly name="id" id="id"
                                    enctype="multipart/form-data">
                                <div class="card-body">
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
                                    <div class="form-group">
                                        <label for="id_perjalanan">Perjalanan</label>
                                        <select name="id_perjalanan" id="id_perjalanan" class="form-control">
                                            <option value="">Pilih Perjalanan</option>
                                            @foreach ($perjalanan as $data_perjalanan)
                                                <option value="{{ $data_perjalanan->id }}">
                                                    {{ $data_perjalanan->user->name }} -
                                                    {{ $data_perjalanan->rute->pluck('nama')->implode(', ') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="error-text" data-error="id_perjalanan" style="color: red;"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal">Tanggal</label>
                                        <input type="date" class="form-control" id="tanggal" name="tanggal"
                                            placeholder="Masukkan Tanggal">
                                        <span class="error-text" data-error="tanggal" style="color: red;"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="bukti">Bukti</label>
                                        <input type="file" class="form-control" id="bukti" name="bukti"
                                            placeholder="Masukkan Bukti Perjalanan">
                                        <small>Maksimal 2MB. Ekstensi yang diperbolehkan: jpeg, png, jpg</small>
                                        <br>
                                        <span class="error-text" data-error="bukti" style="color: red;"></span>
                                    </div>
                                    @can('Kelola Perjalanan')
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="">Pilih Status</option>
                                                <option value="confirmed">Terkonfirmasi</option>
                                                <option value="not_confirmed">Tidak Terkonfirmasi</option>
                                            </select>
                                            <span class="error-text" data-error="status" style="color: red;"></span>
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
                    <div class="col-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Table Report Perjalanan</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                @can('Kelola Perjalanan')
                                    <a href="{{ route('report.perjalanan.pdf') }}" class="btn btn-danger mb-3"
                                        style="float: right;margin: 10px">Export PDF</a>
                                @endcan
                                <table class="table table-hover text-nowrap" id="report-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Driver</th>
                                            <th>Perjalanan</th>
                                            <th>Tanggal</th>
                                            <th>Bukti</th>
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
                    <!-- Image Modal -->
                    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <img id="modal-image" src="" alt="Full Image"
                                        style="width: 100%; height: auto;">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    <script>
        $('#id_user').on('change', function() {
            let userId = $(this).val();
            $('#id_perjalanan').html('<option value="">Loading...</option>');

            if (!userId) {
                $('#id_perjalanan').html('<option value="">Pilih Perjalanan</option>');
                return;
            }

            $.ajax({
                url: `/perjalanan/by-user/${userId}`,
                method: 'GET',
                success: function(data) {
                    let options = '<option value="">Pilih Perjalanan</option>';
                    data.forEach(function(item) {
                        options += `<option value="${item.id}">${item.label}</option>`;
                    });
                    $('#id_perjalanan').html(options);
                },
                error: function() {
                    $('#id_perjalanan').html('<option value="">Gagal memuat data</option>');
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.bukti-thumbnail', function() {
            let imageUrl = $(this).data('full');
            $('#modal-image').attr('src', imageUrl);
            $('#imageModal').modal('show');
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#id_user').select2({
                placeholder: "Pilih Driver",
                allowClear: true
            });

            $('#id_perjalanan').select2({
                placeholder: "Pilih Perjalanan",
                allowClear: true
            });

            $('#status').select2({
                placeholder: "Pilih Status",
                allowClear: true
            });
        });
    </script>
    <script>
        $(function() {
            $('#report-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('report.perjalanan.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name'
                    },
                    {
                        data: 'perjalanan',
                        name: 'perjalanan'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'bukti',
                        name: 'bukti'
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
        $('#report-form').on('submit', function(e) {
            e.preventDefault();

            $('.error-text').text('');
            let form = document.getElementById('report-form');
            let formData = new FormData(form);
            let reportId = $('#report-form #id').val();

            let ajaxMethod = 'POST';
            let ajaxUrl = reportId ? `/report/perjalanan/${reportId}` : '/report/perjalanan';
            if (reportId) {
                formData.append('_method', 'PATCH');
            }

            const $submitBtn = $(this).find('button[type="submit"]');
            $submitBtn.prop('disabled', true).text('Submitting...');

            $.ajax({
                url: ajaxUrl,
                type: ajaxMethod,
                data: formData,
                contentType: false, // ⬅️ important for file uploads
                processData: false, // ⬅️ important for file uploads
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
                    $('#report-form select').val(null).trigger('change');
                    $('#report-form')[0].reset();
                    $('#report-table').DataTable().ajax.reload(null, false);
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
            var reportId = $(this).data('id');
            const $editBtn = $(this);
            $editBtn.prop('disabled', true).text('Loading...');

            $.ajax({
                url: '/report/perjalanan/' + reportId,
                method: 'GET',
                success: function(response) {
                    $('#report-form #id').val(response.id);
                    $('#report-form #id_user').val(response.id_user).trigger('change');
                    $('#report-form #id_perjalanan').val(response.id_perjalanan).trigger('change');
                    $('#report-form #tanggal').val(response.tanggal);
                    // $('#report-form #bukti').val(response.bukti);
                    $('#report-form #status').val(response.status).trigger('change');
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
            var reportId = $(this).data('id');
            const $deleteBtn = $(this);
            $deleteBtn.prop('disabled', true).text('Loading...');

            $.ajax({
                url: '/report/perjalanan/' + reportId,
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
                    $('#report-table').DataTable().ajax.reload(null, false);
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
