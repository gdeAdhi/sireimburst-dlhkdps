<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIMREIMBURS DLHK KOTA DENPASAR</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #mapModal {
            position: fixed;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 80%;
            background: white;
            z-index: 1050;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.4);
            overflow: hidden;
        }

        .select2-container .select2-selection--single {
            height: 38px;
            /* match Bootstrap input height */
            padding: 6px 12px;
            border: 1px solid #ced4da;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px;
        }

        /* Blue outline when selected/focused */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #ced4da;
            border-radius: 4px;
            height: 38px;
            /* match Bootstrap input height */
            padding: 6px 12px;
            background-color: #fff;
            color: #000;
        }

        /* Change text color to black inside the selection */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #e9f5ff;
            color: #000;
            /* Black text */
            /* border: 1px solid #007BFF; */
        }

        /* Placeholder and typed text */
        .select2-container--default .select2-search--inline .select2-search__field {
            color: #000;
            /* Black text input */
        }

        /* Focused state (optional stronger blue) */
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            /* border-color: #0056b3; */
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('lte/dist/img/kodya.png') }}" alt="Kota Denpasar" height="60"
                width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Logout -->
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn nav-link">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('index') }}" class="brand-link">
                <img src="{{ asset('lte/dist/img/Kodya.png') }}" alt="AdminLTE Logo" class="brand-image"
                    style="opacity: .8">
                <span class="brand-text font-weight-light">DLHK DENPASAR</span>
            </a>
            <a href="javascript:void(0);" class="brand-link mb-3 text-center">
                <span class="brand-text font-weight-light">{{ auth()->user()->name }}</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="{{ route('index') }}" class="nav-link {{ Route::is('index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        @canany(['User Management', 'Role Management'])
                            <li
                                class="nav-item {{ Route::is('user.index') || Route::is('roles.index') ? 'menu-open' : '' }}">
                                <a href="javascript:void(0);"
                                    class="nav-link {{ Route::is('user.index') || Route::is('roles.index') ? 'active' : '' }}">
                                    <i class="nav-icon far fa-user"></i>
                                    <p>
                                        User & Role
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('User Management')
                                        <li class="nav-item">
                                            <a href="{{ route('user.index') }}"
                                                class="nav-link {{ Route::is('user.index') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>User Management</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('Role Management')
                                        <li class="nav-item">
                                            <a href="{{ route('roles.index') }}"
                                                class="nav-link {{ Route::is('roles.index') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Role Management</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                        <li class="nav-header">Data Pengelolaan</li>

                        @can('Kendaraan')
                            <li
                                class="nav-item {{ Route::is('tipe.kendaraan.index') || Route::is('merk.kendaraan.index') || Route::is('kendaraan.index') ? 'menu-open' : '' }}">
                                <a href="javascript:void(0);"
                                    class="nav-link {{ Route::is('tipe.kendaraan.index') || Route::is('kendaraan.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-car"></i>
                                    <p>
                                        Data Kendaraan
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('tipe.kendaraan.index') }}"
                                            class="nav-link {{ Route::is('tipe.kendaraan.index') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Tipe Kendaraan</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('merk.kendaraan.index') }}"
                                            class="nav-link {{ Route::is('merk.kendaraan.index') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Merk Kendaraan</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('kendaraan.index') }}"
                                            class="nav-link {{ Route::is('kendaraan.index') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Kendaraan</p>
                                        </a>
                                    </li>
                                    {{-- <li class="nav-item">
                                  <a href="{{ route('roles.index') }}" class="nav-link {{ Route::is('roles.index') ? 'active' : ''}}">
                                    <i class="far fa-circle nav-icon"></i>
                                        <p>Role Management</p>
                                    </a>
                                </li> --}}
                                </ul>
                            </li>
                        @endcan
                        @can('Rute Perjalanan')
                            <li class="nav-item">
                                <a href="{{ route('rute.perjalanan.index') }}"
                                    class="nav-link {{ Route::is('rute.perjalanan.index') ? 'active' : '' }}">
                                    <i class="nav-icon fa-solid fa-route"></i>
                                    <p>
                                        Daftar Rute Perjalanan
                                    </p>
                                </a>
                            </li>
                        @endcan
                        @canany(['Perjalanan', 'Kelola Perjalanan'])
                            <li class="nav-item">
                                <a href="{{ route('perjalanan.index') }}"
                                    class="nav-link {{ Route::is('perjalanan.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-plane"></i>
                                    <p>
                                        Form Perjalanan
                                    </p>
                                </a>
                            </li>
                        @endcanany
                        @canany(['Report Perjalanan','Kelola Report Perjalanan'])
                        <li class="nav-item">
                            <a href="{{ route('report.perjalanan.index') }}"
                                class="nav-link {{ Route::is('report.perjalanan.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    Form Report Perjalanan
                                </p>
                            </a>
                        </li>
                        @endcanany
                    </ul>
                    </li>

                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        @yield('content')
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2025 <a href="https://instagram.com/undeathdestiny">PGDA</a>.</strong>
            All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('lte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('lte/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('lte/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('lte/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('lte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('lte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('lte/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('lte/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    {{-- <script src="{{ asset('lte/dist/js/demo.js') }}"></script> --}}
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    {{-- <script src="{{ asset('lte/dist/js/pages/dashboard.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('scripts')
</body>

</html>
