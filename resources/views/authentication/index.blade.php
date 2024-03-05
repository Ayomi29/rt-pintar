<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <link rel="shortcut icon" type="image/x-icon" href="app-assets/logo-02.png">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" contet="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description"
        content="Website RT Pintar merupakan website dashboard admin untuk mengelola data - data administrasi RT 15 Perumnas Balikpapan">
    <meta name="keywords"
        content="admin, website RT Pintar, dashboard RT Pintar, website rt pintar, dashboard rt pintar, RT 15 Perumnas Balikpapan, rt 15 perumnas balikpapan">

    <title>RT Pintar | Login</title>
    <link href="/app-assets/fonts/fonts.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/app-assets/fonts/line-awesome/css/line-awesome.min.css">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="/app-assets/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/forms/icheck/icheck.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/forms/icheck/custom.css">
    <!-- END VENDOR CSS-->
    <!-- BEGIN MODERN CSS-->
    <link rel="stylesheet" type="text/css" href="/app-assets/css/app.min.css">
    <!-- END MODERN CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="/app-assets/css/core/menu/menu-types/vertical-menu-modern.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/core/colors/palette-gradient.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/pages/login-register.min.css">
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
    <!-- END Custom CSS-->
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/extensions/toastr.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/plugins/extensions/toastr.min.css">
</head>

<body class="vertical-layout vertical-menu-modern 1-column menu-expanded blank-page blank-page bg-gradient-x-dark"
    data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="app-content content h-100">
        <div class="content-wrapper h-100">
            <div class="content-body row h-100">
                <div class="col-7">
                    <div class="d-flex h-100">
                        <div class="justify-content-center align-self-center text-center mx-auto">
                            <img src="https://source.unsplash.com/1600x900/?house, sky" alt="branding logo">
                            {{-- <img src="app-assets/images/balikpapan.jpg" alt="branding logo"> --}}
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="row h-100">
                        <div class="col-12 box-shadow-2 p-0 h-100">
                            <div class="card border-grey border-lighten-3 mr-1 rounded-0 w-100 h-100"
                                style="padding: 5% 15% 0% 15% !important;">
                                <div class="card-header border-0">
                                    <div class="card-title text-center">
                                        <img src="app-assets/logo-01.png" alt="logo RT Pintar">
                                        </h6>
                                        <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                                            <span>RT Pintar</span>
                                    </div>
                                </div>
                                <div class="card-content" id="add-content">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <!-- BEGIN VENDOR JS-->
    <script src="/app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="/app-assets/vendors/js/forms/icheck/icheck.min.js" type="text/javascript"></script>
    <script src="/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js" type="text/javascript">
    </script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN MODERN JS-->
    <script src="/app-assets/js/core/app-menu.min.js" type="text/javascript"></script>
    <script src="/app-assets/js/core/app.min.js" type="text/javascript"></script>
    <!-- END MODERN JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <script src="/app-assets/js/scripts/forms/form-login-register.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS-->
    <script src="/app-assets/vendors/js/extensions/toastr.min.js" type="text/javascript"></script>
    <script src="/app-assets/js/scripts/extensions/toastr.min.js" type="text/javascript"></script>
    <script src="/js/sw-loader.js"></script>
    @yield('script')

    <script>
        window.onload = content;
        $("#add-content").html('<div class="text-center mt-5" id="loading-content"><img src="app-assets/images/Spinner-1s-200px.gif" width="100px"></div>');

        function content() {
            $("#loading-content").remove();
            $("#add-content").html('<div class="card-body pt-1" id="remove-content"><form class="form-horizontal form-simple" method="POST" action="/">@csrf<fieldset class="form-group position-relative has-icon-left mb-1"><input type="text" name="email" class="form-control" placeholder="Masukkan e-mail atau nomor telepon" autofocus required><div class="form-control-position"><i class="ft-user"></i></div></fieldset><fieldset class="form-group position-relative has-icon-left"><input type="password" name="password" class="form-control"id="user-password" placeholder="Masukkan password" minlength="6" required><div class="form-control-position"><i class="ft-lock"></i></div></fieldset><div class="form-group mb-3"><button type="submit" class="btn btn-block btn-info round box-shadow-3"><i class="ft-unlock"></i> Login</button></div><div class="form-group row"><div class="col-12 text-center"><p>Lupa password? <br> <a href="#" class="card-link" id="link" onClick="viaEmail()" style="color: #1E9FF2 !important;">Atur ulang password</a></p></div></div></form></div>');
        }

        function viaEmail(){
            $("#remove-content").fadeOut(3000);
            $("#remove-content").remove();
            $("#add-content").html('<div class="card-body pt-1" id="remove-content"><form class="form-horizontal form-simple" method="POST" action="/send-email-otp-admin">@csrf<fieldset class="form-group position-relative has-icon-left mb-1"><input type="email" name="email" id="email" class="form-control" placeholder="Masukkan e-mail" autofocus required><div class="form-control-position"><i class="la la-envelope"></i></div></fieldset><div class="mt-2" id="check_email"></div><div class="form-group mt-3"><button type="button" id="buttonSendEmail" onClick="emailOtp()" class="btn btn-block btn-info round box-shadow-3">Kirim</button></div><div class="form-group mb-3"><button type="button" onClick="content()" class="btn btn-block btn-secondary round box-shadow-3">Kembali</button></div></div></form></div>')
        }

        function emailOtp(){
            var buttonSendEmail = document.getElementById('buttonSendEmail');
            $("#buttonSendEmail").attr("disabled", true);
            buttonSendEmail.innerHTML = "Mengirim...";
            var email = $("#email").val();
            $.ajax({
                url: '/send-email-otp-admin',
                type: 'post',
                data: {
                    email: email,
                    "_token": "{{ csrf_token() }}",
                }
            }).done(function(response)
            {
                
                if(response != 'email yang anda masukkan salah'){
                    $("#remove-content").remove();
                    $("#add-content").html('<div class="card-body pt-1" id="remove-content"><form class="form-horizontal form-simple" method="POST" action="/confirm-otp-admin">@csrf<fieldset class="form-group position-relative has-icon-left mb-1"><input type="number" name="code" id="code" class="form-control" placeholder="Masukkan code otp" autofocus required><div class="form-control-position"><i class="la la-unlock-alt"></i></div></fieldset><div class="mt-2" id="check_otp"></div><div class="form-group mt-3"><button type="button" id="buttonConfirmOtp" class="btn btn-block btn-info round box-shadow-3">Konfirmasi</div><div class="form-group row"><div class="col-12 text-center"><p>Belum mendapatkan kode otp? <br> <a href="#" class="card-link" onClick="viaEmail()" style="color: #1E9FF2 !important;">Kirim ulang</a></p></div></div></form></div>')
                    $("#buttonConfirmOtp").click(function(){
                        var buttonConfirmOtp = document.getElementById('buttonConfirmOtp');
                        $("#buttonConfirmOtp").attr("disabled", true);
                        buttonConfirmOtp.innerHTML = "Mengkonfirmasi...";
                        var code = $("#code").val();
                        $.ajax({
                            url: '/confirm-otp-admin',
                            type: 'post',
                            data: {
                                email: email,
                                code: code,
                                "_token": "{{ csrf_token() }}",
                            }
                        }).done(function(response)
                        {
                            
                            if(response != 'kode otp tidak valid'){
                                $("#remove-content").remove();
                                $("#add-content").html('<div class="card-body pt-1" id="remove-content"><form class="form-horizontal form-simple" method="POST" action="/change-password">@csrf<fieldset class="form-group position-relative has-icon-left mb-1"><input type="hidden" name="code" id="getCode"><input type="hidden" name="email" id="getEmail"><input type="password" name="password" id="password" placeholder="Masukkan password" class="form-control" minlength="6" required><div class="form-control-position"><i class="la la-unlock-alt"></i></div></fieldset><div class="form-group mt-3"><button type="submit" id="buttonChangePassword" class="btn btn-block btn-info round box-shadow-3">Simpan</button></div></form></div>');
                                $("#getEmail").val(email);
                            } else{
                                $("#buttonConfirmOtp").attr("disabled", false);
                                buttonConfirmOtp.innerHTML = "Konfirmasi";
                                $('#check_otp').html("<span style='color: red;'>Kode Otp tidak valid</span>");
                                $("#code").css("border", "1px solid #f00");
                            }
                        });     
                    });
                } else{
                    $("#buttonSendEmail").attr("disabled", false);
                    buttonSendEmail.innerHTML = "Kirim";
                    $('#check_email').html("<span style='color: red;'>E-mail yang anda masukkan salah</span>");
                    $("#email").css("border", "1px solid #f00");
                }
            });     
        }
    </script>
    @if(session('OK'))
    <script>
        toastr.success('{{ session("OK") }}', 'Success!');
    </script>
    @endif
    @if(session('ERR'))
    <script>
        toastr.error('{{ session("ERR") }}', 'Error!');
    </script>
    @endif
</body>

</html>