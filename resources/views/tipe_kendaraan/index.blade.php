@extends('layouts.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Daftar Tipe Kendaraan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Daftar Kendaraan</a></li>
                            <li class="breadcrumb-item active">Daftar Tipe Kendaraan</li>
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
                                <h3 class="card-title">Form Tipe Kendaraan</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form method="POST" id="tipe-kendaraan-form">
                                @csrf
                                <input type="text" hidden readonly name="id" id="id">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nama">Tipe Kendaraan</label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            placeholder="Masukkan Tipe Kendaraan">
                                        <span class="error-text" data-error="nama" style="color: red;"></span>
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
                                <h3 class="card-title">Table Tipe Kendaraan</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap" id="tipe-kendaraan-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tipe Kendaraan</th>
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
            $('#status').select2({
                placeholder: "Pilih Status",
                allowClear: true
            });
        });
    </script>
    <script>
        $(function() {
            $('#tipe-kendaraan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('tipe.kendaraan.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
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
        $('#tipe-kendaraan-form').on('submit', function(e) {
            e.preventDefault();

            $('.error-text').text('');
            let formData = $('#tipe-kendaraan-form').serialize();
            let tipeKendaraanId = $('#tipe-kendaraan-form #id').val();

            let ajaxMethod = tipeKendaraanId ? 'PATCH' : 'POST';
            let ajaxUrl = tipeKendaraanId ? `/tipe/kendaraan/${tipeKendaraanId}` : '/tipe/kendaraan';

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
                    $('#tipe-kendaraan-form')[0].reset();
                    $('#tipe-kendaraan-table').DataTable().ajax.reload(null, false);
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
            var tipeKendaraanId = $(this).data('id');
            console.log('/tipe/kendaraan/' + tipeKendaraanId);
            const $editBtn = $(this);
            $editBtn.prop('disabled', true).text('Loading...');

            $.ajax({
                url: '/tipe/kendaraan/' + tipeKendaraanId,
                method: 'GET',
                success: function(response) {
                    $('#tipe-kendaraan-form #id').val(response.id);
                    $('#tipe-kendaraan-form #nama').val(response.nama);
                    $('#tipe-kendaraan-form #status').val(response.status).trigger('change');
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
            var tipeKendaraanId = $(this).data('id');
            const $deleteBtn = $(this);
            $deleteBtn.prop('disabled', true).text('Loading...');

            $.ajax({
                url: '/tipe/kendaraan/' + tipeKendaraanId,
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
                    $('#tipe-kendaraan-table').DataTable().ajax.reload(null, false);
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
