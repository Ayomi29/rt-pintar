<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description"
        content="Website RT Pintar merupakan website dashboard admin untuk mengelola data - data administrasi RT 15 Perumnas Balikpapan">
    <meta name="keywords"
        content="admin, website RT Pintar, dashboard RT Pintar, website rt pintar, dashboard rt pintar, RT 15 Perumnas Balikpapan, rt 15 perumnas balikpapan">

    <meta name="_token" content="{{ csrf_token() }}">
    <title>RT Pintar | @yield('title')</title>
    <link rel="shortcut icon" type="image/x-icon" href="/app-assets/logo-02.png">
    <link href="/app-assets/fonts/fonts.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="/app-assets/fonts/line-awesome/css/line-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="/app-assets/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/datatables.min.css">
    <!-- END VENDOR CSS-->
    <!-- BEGIN MODERN CSS-->
    <link rel="stylesheet" type="text/css" href="/app-assets/css/app.min.css">
    <!-- END MODERN CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="/app-assets/css/core/menu/menu-types/vertical-menu-modern.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/core/colors/palette-gradient.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/charts/jquery-jvectormap-2.0.3.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/charts/morris.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/fonts/simple-line-icons/style.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/core/colors/palette-gradient.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/extensions/toastr.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/plugins/extensions/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/forms/selects/select2.min.css">
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    @yield('style')
    <style>
        .main-menu.menu-light .navigation>li ul li>a {
            padding: 8px 18px 8px 25px;
        }
    </style>
    <!-- END Custom CSS-->
</head>

<body class="vertical-layout vertical-menu-modern 2-columns menu-expanded fixed-navbar" data-open="click"
    data-menu="vertical-menu-modern" data-col="2-columns">
    <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-dark"
        style="background-color: #01CCFF">
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row align-item-center">
                    <li class="nav-item mobile-menu d-md-none mr-auto">
                        <a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#">
                            <i class="ft-menu font-large-1"></i>
                        </a>
                    </li>
                    <li class="nav-item mr-auto">
                        <a class="navbar-brand" href="/home">
                            <img style="width: 75px" alt="logo RT Kita" src="/app-assets/logo-03.png">
                            <h5 class="brand-text text-center">Dashboard</h5>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block float-right">
                        <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
                            <i class="toggle-icon font-medium-3 white ft-toggle-left" data-ticon="ft-toggle-right"></i>
                        </a>
                    </li>
                    <li class="nav-item d-md-none">
                        <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile">
                            <i class="la la-ellipsis-v"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="navbar-container content">
                <div class="collapse navbar-collapse" id="navbar-mobile">
                    <ul class="nav navbar-nav mr-auto float-left">
                        <li class="nav-item d-md-none">
                            <a class="nav-link disabled">
                                @if(Auth::user()->admin == true)
                                {{ Auth::user()->family_member->family_member_name }}
                                @endif
                            </a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav float-right">

                        <li class="dropdown dropdown-notification nav-item">
                            <a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i
                                    class="ficon ft-bell"></i>
                                <span
                                    class="badge badge-pill badge-default badge-danger badge-default badge-up badge-glow">{{
                                    $dashboard_notification_unread }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0">
                                        <span class="grey darken-2">Notifikasi</span>
                                    </h6>

                                    <span class="notification-tag badge badge-default badge-danger float-right m-0">{{
                                        $dashboard_notification_unread }} New</span>
                                </li>
                                <li class="scrollable-container media-list w-100">
                                    @if ($dashboard_notifications == null)
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="notification-text font-small-3 text-muted">Tidak ada notifikasi
                                            </p>
                                        </div>
                                    </div>

                                    @else

                                    @foreach($dashboard_notifications as $dashboard_notification)
                                    @if($dashboard_notification->category == 'Warga')
                                    <a href="javascript:void(0)" data-redirect="/users"
                                        data-id="{{ $dashboard_notification->id }}" class="dashboardNotificationRead">
                                        <div class="media"
                                            style="{{ $dashboard_notification->status == 1 ? 'background: rgba(236, 240, 241, 0.5) !important;' : '' }}">
                                            <div class="media-left align-self-center"><i
                                                    class="la la-user icon-bg-circle bg-info"></i></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">
                                                    {{ $dashboard_notification->category }}
                                                    @if($dashboard_notification->status == 0)
                                                    Baru!
                                                    @endif
                                                </h6>
                                                <p class="notification-text font-small-3 text-muted">{{
                                                    $dashboard_notification->description }}</p>
                                                <small>
                                                    <time class="media-meta text-muted">{{
                                                        $dashboard_notification->created_at->format('d-m-Y H:i')
                                                        }}</time>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                    @endif
                                    @if($dashboard_notification->category == 'Surat pengantar')
                                    <a href="javascript:void(0)" data-redirect="/cover-letters"
                                        data-id="{{ $dashboard_notification->id }}" class="dashboardNotificationRead">
                                        <div class="media"
                                            style="{{ $dashboard_notification->status == 1 ? 'background: rgba(236, 240, 241, 0.5) !important;' : '' }}">
                                            <div class="media-left align-self-center"><i
                                                    class="la la-envelope icon-bg-circle bg-info"></i></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">
                                                    {{ $dashboard_notification->category }}
                                                    @if($dashboard_notification->status == 0)
                                                    Baru!
                                                    @endif
                                                </h6>
                                                <p class="notification-text font-small-3 text-muted">{{
                                                    $dashboard_notification->description }}</p>
                                                <small>
                                                    <time class="media-meta text-muted">{{
                                                        $dashboard_notification->created_at->format('d-m-Y H:i')
                                                        }}</time>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                    @endif
                                    @if($dashboard_notification->category == 'Aduan warga')
                                    <a href="javascript:void(0)" data-redirect="/complaints"
                                        data-id="{{ $dashboard_notification->id }}" class="dashboardNotificationRead">
                                        <div class="media"
                                            style="{{ $dashboard_notification->status == 1 ? 'background: rgba(236, 240, 241, 0.5) !important;' : '' }}">
                                            <div class="media-left align-self-center"><i
                                                    class="la la-bell icon-bg-circle bg-warning"></i></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">
                                                    {{ $dashboard_notification->category }}
                                                    @if($dashboard_notification->status == 0)
                                                    Baru!
                                                    @endif
                                                </h6>
                                                <p class="notification-text font-small-3 text-muted">{{
                                                    $dashboard_notification->description }}</p>
                                                <small>
                                                    <time class="media-meta text-muted">{{
                                                        $dashboard_notification->created_at->format('d-m-Y H:i')
                                                        }}</time>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                    @endif
                                    @if($dashboard_notification->category == 'Panik button')
                                    <a href="javascript:void(0)" data-redirect="/home"
                                        data-id="{{ $dashboard_notification->id }}" class="dashboardNotificationRead">
                                        <div class="media"
                                            style="{{ $dashboard_notification->status == 1 ? 'background: rgba(236, 240, 241, 0.5) !important;' : '' }}">
                                            <div class="media-left align-self-center"><i
                                                    class="la la-exclamation icon-bg-circle bg-danger"></i></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">
                                                    {{ $dashboard_notification->category }}
                                                    @if($dashboard_notification->status == 0)
                                                    Baru!
                                                    @endif
                                                </h6>
                                                <p class="notification-text font-small-3 text-muted">{{
                                                    $dashboard_notification->description }}</p>
                                                <small>
                                                    <time class="media-meta text-muted">{{
                                                        $dashboard_notification->created_at->format('d-m-Y H:i')
                                                        }}</time>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                    @endif
                                    @if($dashboard_notification->category == 'Bayar Iuran')
                                    <a href="javascript:void(0)" data-redirect="/iurans"
                                        data-id="{{ $dashboard_notification->id }}" class="dashboardNotificationRead">
                                        <div class="media"
                                            style="{{ $dashboard_notification->status == 1 ? 'background: rgba(236, 240, 241, 0.5) !important;' : '' }}">
                                            <div class="media-left align-self-center"><i
                                                    class="la la-file-invoice-dollar icon-bg-circle bg-info"></i></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">
                                                    {{ $dashboard_notification->category }}
                                                    @if($dashboard_notification->status == 0)
                                                    Baru!
                                                    @endif
                                                </h6>
                                                <p class="notification-text font-small-3 text-muted">{{
                                                    $dashboard_notification->description }}</p>
                                                <small>
                                                    <time class="media-meta text-muted">{{
                                                        $dashboard_notification->created_at->format('d-m-Y H:i')
                                                        }}</time>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                    @endif
                                    @endforeach
                                    @endif
                                </li>
                                <li class="dropdown-menu-footer">
                                    <a class="dropdown-item text-muted text-center" href="javascript:void(0)">Klik untuk
                                        membaca
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown dropdown-user nav-item">
                            <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <span class="mr-1">
                                    <span class="user-name text-bold-700">
                                        @if(Auth::user()->admin == true)
                                        {{ Auth::user()->family_member->family_member_name }}
                                        @endif
                                    </span>
                                </span>
                                <span class="avatar avatar-online">
                                    <img src="/app-assets/default.jpg" alt="avatar">
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <form action="/logout" method="POST">
                                    @csrf
                                    <div class="dropdown-item">
                                        <button type="submit" class="bg-white border border-0"><i
                                                class="ft-log-out"></i> Keluar</button>
                                    </div>

                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">

        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation"
                style="font-size: 14px !important">
                <li class="nav-item menu-navigasi">
                    <a href="/home">
                        <i class="la la-home"></i>
                        <span class="menu-title">Halaman Home</span>
                    </a>
                </li>
                @if(Auth::user()->admin == true)

                <li class="nav-item has-sub">
                    <a href="#">
                        <i class="las la-users"></i>
                        <span class="menu-title">Manajemen User</span>
                    </a>
                    <ul class="menu-content" style="">
                        <li class="nav-item menu-navigasi">
                            <a href="/roles">
                                <span class="menu-title">Role User</span>
                            </a>
                        </li>
                        <li class="nav-item menu-navigasi">
                            <a href="/users">
                                <span class="menu-title">Warga</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item menu-navigasi">
                    <a href="/data-rts">
                        <i class="la la-building"></i>
                        <span class="menu-title">Manajemen Data RT</span>
                    </a>
                </li>
                <li class="nav-item menu-navigasi">
                    <a href="/important-numbers">
                        <i class="la la-tty"></i>
                        <span class="menu-title">Manajemen Nomor Penting</span>
                    </a>
                </li>
                <li class="nav-item menu-navigasi">
                    <a href="/houses">
                        <i class="la la-home"></i>
                        <span class="menu-title">Manajemen Data Rumah</span>
                    </a>
                </li>
                <li class="nav-item menu-navigasi">
                    <a href="/family-cards">
                        <i class="la la-credit-card"></i>
                        <span class="menu-title">Manajemen Data KK</span>
                    </a>
                </li>
                <li class="nav-item menu-navigasi">
                    <a href="/family-members">
                        <i class="la la-credit-card"></i>
                        <span class="menu-title">Manajemen Data Anggota KK</span>
                    </a>
                </li>
                <li class="nav-item menu-navigasi">
                    <a href="/notices">
                        <i class="las la-bullhorn"></i>
                        <span class="menu-title">Manajemen Pengumuman</span>
                    </a>
                </li>

                <li class="nav-item menu-navigasi">
                    <a href="/complaints">
                        <i class="las la-exclamation-triangle"></i>
                        <span class="menu-title">Manajemen Aduan Warga</span>
                    </a>
                </li>

                <li class="nav-item menu-navigasi">
                    <a href="/pollings">
                        <i class="la la-poll"></i>
                        <span class="menu-title">Manajemen Polling</span>
                    </a>
                </li>

                <li class="nav-item menu-navigasi">
                    <a href="/cover-letters">
                        <i class="las la-envelope"></i>
                        <span class="menu-title">Surat Pengantar</span>
                    </a>
                </li>

                <li class="nav-item menu-navigasi">
                    <a href="/iurans">
                        <i class="las la-file-invoice-dollar"></i>
                        <span class="menu-title">Manajemen Data Iuran</span>
                    </a>
                </li>

                @endif
            </ul>
        </div>

    </div>

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row mb-3">
                @yield('content-header')
            </div>
            <div class="content-body">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <footer class="footer footer-static footer-light navbar-border navbar-shadow">
        <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
            <span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2023
                <a class="text-bold-800 grey darken-2" href="" target="_blank">RT Pintar</a>, All rights reserved.
            </span>
            <span class="float-md-right d-block d-md-inline-blockd-none d-lg-block">Hand-crafted & Made with
                <i class="ft-heart pink"></i>
            </span>
        </p>
    </footer>
    {{-- Select2 --}}

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- BEGIN VENDOR JS-->
    <script src="/app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="/app-assets/vendors/js/charts/chart.min.js" type="text/javascript"></script>
    <script src="/app-assets/vendors/js/charts/raphael-min.js" type="text/javascript"></script>
    <script src="/app-assets/vendors/js/charts/morris.min.js" type="text/javascript"></script>
    <script src="/app-assets/vendors/js/charts/jvector/jquery-jvectormap-2.0.3.min.js" type="text/javascript"></script>
    <script src="/app-assets/vendors/js/charts/jvector/jquery-jvectormap-world-mill.js" type="text/javascript"></script>
    <script src="/app-assets/vendors/js/extensions/toastr.min.js" type="text/javascript"></script>
    <script src="/app-assets/js/scripts/extensions/toastr.min.js" type="text/javascript"></script>
    <script src="/app-assets/data/jvector/visitor-data.js" type="text/javascript"></script>
    <script src="/ckeditor/ckeditor.js"></script>
    <script src="/ckeditor/adapters/jquery.js"></script>
    <script src="/app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN MODERN JS-->
    <script src="/app-assets/js/core/app-menu.min.js" type="text/javascript"></script>
    <script src="/app-assets/js/core/app.min.js" type="text/javascript"></script>
    <script src="/app-assets/js/scripts/customizer.min.js" type="text/javascript"></script>
    <script src="/app-assets/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>
    <script src="/app-assets/js/scripts/forms/select/form-select2.min.js" type="text/javascript"></script>
    <!-- END MODERN JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    @if(session('OK'))
    <script>
        toastr.success('{{ session("OK") }}', 'Success!');
            toastr.success('{{ session("success") }}', 'Success!');
    </script>
    @endif
    @if(session('ERR'))
    <script>
        toastr.error('{{ session("ERR") }}', 'Error!');
            toastr.error('{{ session("error") }}', 'Error!');
    </script>
    @endif
    <script>
        let apiBaseUrl = "{{ url('/') }}/api";
        $(document).ready(function() {
        // get current URL path and assign 'active' class
        console.log(apiBaseUrl);
        let pathname = window.location.pathname;
        $('.nav-item a[href="'+pathname+'"]').parent().addClass('active');
        })
        let datatable = $(".datatable");
        if (datatable != null) {
            $(".datatable").DataTable();
        }
        
        $(".input-number").on("keypress", function(evt)
        {
            var charCode = (evt.which) ? evt.which : evt.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        });
        
        var token = "{{ csrf_token() }}";
        
        var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=' + token,
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token=' + token
        };
        
        
        
        $(document).on("click", ".dashboardNotificationRead", function(){
            var id = $(this).data("id");
            var redirect = $(this).data("redirect");
            $.ajax({
               method: 'post',
               url: '/dashboard-notification-read',
               data:{
                   id : id,
                   _token : token 
               },
            }).done(function(response){
                
                window.location = redirect;
            });
        });
        
    </script>
    <script type="text/javascript">
        var togglePassword = document.querySelector('#togglePassword');
      var password = document.querySelector('#password');
    
      togglePassword.addEventListener('click', function (e) {
        // toggle the type attribute
        var type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // toggle the eye / eye slash icon
        this.classList.toggle('la-eye');
        this.classList.toggle('la-eye-slash');
      });
    </script>
    @yield('script')
    <!-- END PAGE LEVEL JS-->

</body>

</html>