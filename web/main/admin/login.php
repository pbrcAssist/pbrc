<?php
  require_once('./../../../foundation/main/configuration/Configuration.php');
?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
<link rel="stylesheet" href="./../../resources/plugins/bootstrap5/bootstrap.min.css" integrity="sha512-b2QcS5SsA8tZodcDtGRELiGv5SaKSk1vDHDaQRda0htPYWZ6046lr3kJ5bAAQdpV2mmA/4v0wQF9MyU6/pDIAg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="./../../resources/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<link href="./../../resources/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
<link href="./../../resources/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
<!-- Template Main CSS File -->
<link href="./../../resources/assets/css/admin-styles.css" rel="stylesheet">
<link href="./../../resources/assets/css/admin-lte.css" rel="stylesheet">
<link href="./../../resources/assets/css/admin-custom.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer"
/>
<link rel="icon" href="./../../resources/images/logo.png" type="image/png">
<link rel="shortcut icon" href="./../../resources/images/logo.png" type="image/png">


<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="./" class="h1"><b>Login</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form id="login-form">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" id="username" placeholder="Username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <div class="row mt-4">
                    <div class="col-6">
                        <a href="./../../../index.html">Go to Website</a>
                    </div>
                    <div class="col-6">
                        <p class="text-right">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modal-change-password">Forgot Password?</a>
                        </p>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- Modal -->
    <div class="modal fade" id="modal-change-password" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Forgot Password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-send-email" class="form-group">
                        <div class="alert alert-danger alert-dismissible" id="error" style="display:none;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        </div>
                        <input type="email" name="" id="input-email" class="form-control" placeholder="Enter email address">
                        <input type="submit" value="Send Email" id="button-send-email" class="btn btn-primary btn-block mt-3">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="./../../resources/plugins/jquery/jquery.min.copy.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <!-- AdminLTE App -->
    <script src="./../../resources/plugins/jquery/aes.js "></script>
    <script src="./../../resources/dist/js/adminlte.min.js"></script>
    <script src="./../../resources/dist/js/script.js"></script>
    <script src="./../../resources/assets/js/constants.js"></script>
    <script src="./../../resources/assets/js/common.js"></script>

    <script>
        function sendEmail(email, userID) {
            var key = encrypt(userID + "," + new Date());
            $.ajax({
                url: adminRequest(MAILER_URL),
                type: POST,
                data: {
                    action: 'send',
                    email: email,
                    key: key
                },
                cache: false,
                success: function(dataResult) {
                    var dataResult = JSON.parse(dataResult);
                    $("#spinner-forgot-password").addClass("d-none");
                    if (dataResult.statusCode == 200) {
                        $("#button-send-email").val("Send Email");
                        $("#button-send-email").removeAttr("disabled");
                        alert("Forgot password instruction has been sent to your email!");
                    } else if (dataResult.statusCode == 500) {
                        $("#error").show();
                        $('#error').html("Unable to send an email, please try again later!");
                    }
                }
            });
        }

        $(document).ready(function() {
            $("#form-send-email").on("submit", function(event) {
                event.preventDefault();
                $("#error").hide();
                var email = $('#input-email').val();

                $("#button-send-email").val("Processing, please wait...");
                $("#button-send-email").attr("disabled", true);

                $.ajax({
                    url: adminRequest(GET_LOGIN_URL + GET_EMAIL_ADDRESS + populateQueryParameter('email', email)),
                    type: "GET",
                    cache: false,
                    success: function(dataResult) {
                        var dataResult = JSON.parse(dataResult);
                        if (dataResult.statusCode == 200) {
                            $("#button-submit").attr("disabled", true);
                            $("#spinner-forgot-password").removeClass("d-none");
                            sendEmail(email, dataResult.accountID);
                        } else if (dataResult.statusCode == 5006) {
                            $("#error").show();
                            $('#error').html("Invalid Email Address!");
                            $("#button-send-email").val("Send Email");
                            $("#button-send-email").removeAttr("disabled");
                        }
                    }
                });
            });

            $("#login-form").on('submit', function(event) {
                event.preventDefault();
                start_loader();
                $.ajax({
                    url: "./../../../foundation/main/business/operation/Login.php",
                    type: "POST",
                    data: {
                        username: $('#username').val(),
                        password: $('#password').val(),
                        type: '1',
                        action: 'login'
                    },
                    cache: false,
                    success: function(dataResult) {
                        var dataResult = JSON.parse(dataResult);
                        if (dataResult.status = 200) {
                            location.reload(true);
                        }
                    }
                }).done(function() {
                    end_loader();
                });
            });

        });
    </script>
</body>

</html>