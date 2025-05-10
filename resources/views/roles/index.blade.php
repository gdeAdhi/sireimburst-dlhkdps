@extends('layouts.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Role Management</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                            <li class="breadcrumb-item active">Role Management</li>
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
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Form Role</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('roles.store') }}" method="POST" id="roles-form">
                                    @csrf
                                    <input type="text" class="form-control" id="id" name="id" disabled
                                        hidden>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Role</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Masukkan Nama Role">
                                        <span class="error-text" data-error="name" style="color: red;"></span>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Assign Permissions to Role</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('roles.assign-permissions') }}" method="POST"
                                    id="assign-permissions-form">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Pilih Role</label>
                                        <select name="role_id" id="role" class="form-control" required>
                                            <option value="">-- Pilih Role --</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Permissions</label>
                                        <div class="row">
                                            @foreach ($permissions as $permission)
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                                            value="{{ $permission->id }}" id="perm_{{ $permission->id }}">
                                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Assign Permissions</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Table Roles</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap" id="roles-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Role</th>
                                            <th>Permission(s)</th>
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
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#role').select2({
                placeholder: "Pilih Role",
                allowClear: true
            });
        });
    </script>
    <script>
        $(function() {
            $('#roles-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('roles.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'permissions',
                        name: 'permissions'
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
        $('#roles-form').on('submit', function(e) {
            e.preventDefault();

            $('.error-text').text('');
            let formData = $('#roles-form').serialize();
            let rolesId = $('#roles-form #id').val();

            let ajaxMethod = rolesId ? 'PATCH' : 'POST';
            let ajaxUrl = rolesId ? `/roles/${rolesId}` : '/roles';

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
                    const $roleSelect = $('#role');
                    if (ajaxMethod === 'POST') {
                        // Add the new role as an option
                        $roleSelect.append(
                            '<option value="' + response.role.id + '">' + response.role.name +
                            '</option>'
                        );
                    } else if (ajaxMethod === 'PATCH') {
                        // Update the existing option text
                        $roleSelect.find('option[value="' + response.role.id + '"]').text(response.role
                            .name);
                    }
                    $('#roles-form')[0].reset();
                    $('#roles-table').DataTable().ajax.reload(null, false);
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
            var rolesId = $(this).data('id');
            const $editBtn = $(this);
            $editBtn.prop('disabled', true).text('Loading...');

            $.ajax({
                url: '/roles/' + rolesId,
                method: 'GET',
                success: function(response) {
                    $('#roles-form #id').val(response.id);
                    $('#roles-form #name').val(response.name);
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
            var rolesId = $(this).data('id');
            const $deleteBtn = $(this);
            $deleteBtn.prop('disabled', true).text('Loading...');

            $.ajax({
                url: '/roles/' + rolesId,
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
                    $('#role option[value="' + response.role_id + '"]').remove();
                    $('#roles-table').DataTable().ajax.reload(null, false);
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
        $(document).ready(function() {
            $('#role').on('change', function() {
                let roleId = $(this).val();

                // First, clear all checkboxes
                $('input[name="permissions[]"]').prop('checked', false);

                if (roleId) {
                    $.ajax({
                        url: '/roles/' + roleId + '/permissions',
                        type: 'GET',
                        success: function(permissionIds) {
                            permissionIds.forEach(function(id) {
                                $('#perm_' + id).prop('checked', true);
                            });
                        },
                        error: function() {
                            alert('Failed to fetch role permissions.');
                        }
                    });
                }
            });
        });
    </script>

    <script>
        $('#assign-permissions-form').on('submit', function(e) {
            e.preventDefault();

            $('.error-text').text('');
            let formData = $('#assign-permissions-form').serialize();

            $.ajax({
                url: 'roles/assign-permissions',
                type: 'POST',
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
                    $('#roles-table').DataTable().ajax.reload(null, false);
                    $('#assign-permissions-form')[0].reset();
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
                }
            });
        });
    </script>
@endpush
