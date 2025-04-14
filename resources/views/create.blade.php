@extends('layout.main')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Tambah User</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Data User</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row  -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <form action="{{ route('user.store') }}" method="POST">
      @csrf
      <div class="col-md-6">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Add User</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form>
            <div class="card-body">
              <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Enter email">
                @error('email')
                  <small>{{ $message }}</small>
                @enderror
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Username</label>
                <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Enter username">
                @error('name')
                  <small>{{ $message }}</small>
                @enderror
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                @error('password')
                  <small>{{ $message }}</small>
                @enderror
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
    </form>

    <!-- Main content -->

    <!-- /.content -->
  </div>
 
@endsection