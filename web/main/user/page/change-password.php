<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PBRC</title>
    <link rel="stylesheet" href="./../../../resources/assets/vendor/bootstrap/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="./../../../login.css"> -->
    <link rel="stylesheet" href="./../../../resources/assets/css/style.css">
</head>

<body style="background-color: rgb(245, 245, 245)">

    <section class="login">
        <div class="px-4 py-5 px-md-5 text-lg-start h-100 " style="background-color: hsl(0, 0%, 96%)">
            <div class="container">
                <div class="row gx-lg-5 ">
                    <div class="col-lg-3 text-center"></div>
                    <div class="col-lg-6 mb-5 mb-lg-0">
                        <div class="card">
                            <div class="card-body py-5 px-md-5 " id="login-form">
                                <div>
                                    <form id="form-change-password">
                                        <div id="container-change-password" style="display: none">
                                            <div class="row">
                                                <div class="col-md-6 mb-4">
                                                    <h2>Forgot Password</h2>
                                                </div>
                                            </div>

                                            <div class="alert alert-danger alert-dismissible" id="error" style="display:none;">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                            </div>

                                            <!-- Email input -->
                                            <div class="form-outline mb-4">
                                                <input type="password" id="password" class="form-control" placeholder="Enter New Password" onkeyup="checkChangePasswordStrength();" required/>
                                                <label class="form-label" for="password">New Password</label>
                                                <div id="change-password-strength-status"></div>
                                            </div>

                                            <!-- Password input -->
                                            <div class="form-outline mb-4">
                                                <input type="password" id="confirm-password" class="form-control" placeholder="Enter Confirm Password" required/>
                                                <label class="form-label" for="confirm-password">Confirm Password</label>
                                            </div>

                                            <!-- Submit button -->
                                            <button type="submit" id="button-change-password" class="btn btn-primary btn-block mb-4 w-100">Change Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Jumbotron -->
    </section>

    <!-- Section: Design Block -->
</body>
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> -->
    <script src="./../../../resources/plugins/jquery/jquery.min.copy.js"></script>
    <script src="./../../../resources/plugins/jquery/jquery.cookie.js"></script>
    <script src="./../../../resources/plugins/jquery/aes.js"></script>
    <script src="./../../../resources/assets/js/common.js"></script>

    <script src="./../../../resources/assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="./../../../resources/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./../../../resources/assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="./../../../resources/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="./../../../resources/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="./../../../resources/assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="./../../../resources/assets/vendor/php-email-form/validate.js"></script>


    <!-- Template Main JS File -->
    <script src="./../../../resources/assets/js/main.js"></script>

<script>

    function checkChangePasswordStrength() {
            var number = /([0-9])/;
            var alphabets = /([a-zA-Z])/;
            var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
            var password = $("#password").val().trim();
            if (password.length < 6) {
                $('#change-password-strength-status').removeClass();
                $('#change-password-strength-status').addClass('weak-password');
                $('#change-password-strength-status').html("Weak (should be atleast 6 characters.)");
                $('#password').addClass('is-invalid');
                return false;
            } else {
                if (password.match(number) && password.match(alphabets) && password.match(special_characters)) {
                    $('#change-password-strength-status').removeClass();
                    $('#change-password-strength-status').addClass('strong-password');
                    $('#change-password-strength-status').html("Strong");
                    $('#password').removeClass('is-invalid');
                    return true;
                } else {
                    $('#change-password-strength-status').removeClass();
                    $('#change-password-strength-status').addClass('medium-password');
                    $('#change-password-strength-status').html("Medium (should include alphabets, numbers and special characters.)");
                    $('#password').addClass('is-invalid');
                    return false;
                }
            }
    }
    

    $(document).ready(function() {
        var key = "<?php echo $_SERVER['QUERY_STRING']; ?>";
        var decryptedKey = decrypt(key.substr(4));
        var accountID = '';
        if (decryptedKey == "") {
            $("#form-change-password").html("You're not authorized to do this password change!");
            $("#form-change-password").addClass("text-center text-danger h5");
        } else {
            const query = decryptedKey.split(",");
            accountID = query[0];
            let queryDate = new Date(query[1]);
            const date = new Date();
            date.setMinutes(date.getMinutes() - 30);
            if (date > queryDate) {
                $("#form-change-password").html("Session Expired!");
                $("#form-change-password").addClass("text-center text-warning h5");
            } else {
                $("#container-change-password").removeAttr("style");
            }
            console.log("Q " + queryDate);
            console.log("V " + date);
            console.log(date < queryDate);
        }

        $("#form-change-password").on("submit", function(event) {
            event.preventDefault();
            var password = $('#password').val();
            var confirmPassword = $('#confirm-password').val();



            if (password != confirmPassword) {
                $("#error").show();
                $('#error').html("Password doesn't match!!");
                return;
            }

            if(!checkChangePasswordStrength()){
                return;
            }

            $.ajax({
                url: "./../../../../foundation/main/business/operation/Login.php",
                type: "POST",
                data: {
                    action: 'change-password',
                    accountID: accountID,
                    password: password
                },
                cache: false,
                success: function(dataResult) {
                    var dataResult = JSON.parse(dataResult);
                    if (dataResult.statusCode == 200) {
                        $("#form-change-password").html("Password has been successfully changed!");
                        $("#form-change-password").addClass("text-center text-success h5");
                    } else {
                        $("#error").show();
                        $('#error').html('Unable to change password, please try again later.');
                    }
                }
            });
        });
    });
</script>

</html>