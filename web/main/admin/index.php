<?php
  require_once('./../../../foundation/main/configuration/Configuration.php');
?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        PBRC - Admin
    </title>
    <link href="./../../resources/assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="./../../resources/dist/css/adminlte.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="./../../resources/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link href='./../../resources/plugins/calendar/fullcalendar.min.css' rel='stylesheet' />
    <link rel="stylesheet" href="./../../resources/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="./../../resources/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="./../../resources/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="./../../resources/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="./../../resources/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="./../../resources/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <!-- <link rel="stylesheet" href="./../../resources/dist/css/custom.css"> -->
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="./../../resources/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="./../../resources/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="./../../resources/plugins/summernote/summernote-bs4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="./../../resources/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="./../../resources/assets/vendor/boxicons/css/boxicons.min.css" >

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.2.0/css/scroller.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/dist/toastr.min.css">
    <!-- Full Calendar -->

    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-c42qTSw/wPZ3/5LBzD+Bw5f7bSF2oxou6wEb+I/lqeaKV5FDIfMvvRp772y4jcJLKuGUOpbJMdg/BTl50fJYAw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" href="./../../resources/images/logo.png" type="image/png">
    <link rel="shortcut icon" href="./../../resources/images/logo.png" type="image/png">
    <!-- DataTables Select CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.4/css/select.dataTables.min.css">

</head>

<body class="sidebar-mini layout-fixed control-sidebar-slide-open layout-navbar-fixed sidebar-mini-md sidebar-mini-xs" data-new-gr-c-s-check-loaded="14.991.0" data-gr-ext-installed="" style="height: auto;">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark border border-light border-top-0  border-left-0 border-right-0 navbar-light text-sm">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="./" class="nav-link nav-item pbrc-name"></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                    <div class="btn-group nav-link">
                        <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            <span class="fa fa-user">
                                <span id="admin-username">Username</span>
                            </span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item d-block py-1 admin-index" href="#admin/index" id="nav-my-account"><span class="fa fa-user"></span> My Account</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item d-block" href="#" id="btn-admin-logout"><span class="fas fa-sign-out-alt"></span> Logout</a>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand">
        <!-- Brand Logo -->
            <div class="container-fluid brand-link bg-primary text-sm">
                <img src="" alt="Store Logo" id="pbrc-logo" class="brand-image img-circle elevation-3" style="width: 1.8rem;height: 1.8rem;max-height: unset">
                <span class="navbar-brand mb-0 h1 pbrc-short-name"></span>
            </div>
            <!-- Sidebar -->
            <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden">
                <div class="os-resize-observer-host observed">
                    <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
                </div>
                <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
                    <div class="os-resize-observer"></div>
                </div>
                <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 646px;"></div>
                <div class="os-padding">
                    <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
                        <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
                            <!-- Sidebar user panel (optional) -->
                            <div class="clearfix"></div>
                            <!-- Sidebar Menu -->
                            <nav class="mt-4">
                                <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-compact nav-flat nav-child-indent nav-collapse-hide-child accordion" id="sidebar-accordion" data-widget="treeview" role="menu" data-accordion="true">
                                    <li class="nav-item dropdown">
                                        <a href="#home" id="nav-dashboard" class="home nav-link nav-home">
                                            <i class="nav-icon fas fa-tachometer-alt"></i>
                                            <p>
                                                Dashboard
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a href="#events/index" id="nav-event" class="events-index nav-link nav-events">
                                            <i class="nav-icon fas fa-calendar-day"></i>
                                            <p>
                                                Event List
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a href="#service/index" id="nav-service-reservation" class="service-index nav-link nav-appointment">
                                            <i class="nav-icon fas fa-calendar-check"></i>
                                            <p>
                                                Service Reservation
                                            </p>
                                        </a>
                                    </li>

                                    <li class="nav-item dropdown">
                                        <a href="#room/index" id="nav-room-reservation" class="room-index nav-link nav-room_request">
                                            <i class="nav-icon fas fa-calendar-check"></i>
                                            <p>
                                                Room Reservation
                                            </p>
                                        </a>
                                    </li>

                                    <li class="nav-header">Maintenance</li>
                                    <li class="nav-item dropdown">
                                        <a href="" class="nav-link collapsed nav-system_info" data-toggle="collapse" data-target="#collapse-room" aria-expanded="true" aria-controls="collapse-utilities">
                                            <i class="nav-icon fas fa-book"></i>
                                            <p>
                                                Room Manager
                                            </p>
                                        </a>
                                        <div id="collapse-room" class="collapse container" aria-labelledby="headingUtilities" data-parent="#sidebar-accordion">
                                            <div class="bg-white py-2 collapse-inner rounded ml-3 mr-2">
                                                <a class="nav-link nav-room-category collapse-item nav-item d-block pl-4 py-1 text-reset maintenance-room_category" href="#maintenance/room_category" id="nav-room-category">Room Category</a>
                                                <a class="nav-link nav-rooms collapse-item nav-item d-block pl-4 py-1 text-reset maintenance-room" href="#maintenance/room" id="nav-room-list">Room List</a>
                                                <a class="nav-link nav-rooms collapse-item nav-item d-block pl-4 py-1 text-reset maintenance-room" href="#maintenance/room_additional" id="nav-room-additional">Room Additional</a>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="nav-item dropdown">
                                        <a href="" class="nav-link collapsed nav-system_info" data-toggle="collapse" data-target="#collapse-service" aria-expanded="true" aria-controls="collapse-utilities">
                                            <i class="nav-icon fas fa-book"></i>
                                            <p>
                                            Service Manager
                                            </p>
                                        </a>
                                        <div id="collapse-service" class="collapse container" aria-labelledby="headingUtilities" data-parent="#sidebar-accordion">
                                            <div class="bg-white py-2 collapse-inner rounded ml-3 mr-2">
                                                <a href="#maintenance/service" id="nav-service-list" class="maintenance-service nav-link nav-sched_type">Service List</a>
                                                <a href="#maintenance/package" id="nav-package-list" class="maintenance-package nav-link">Service Package</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a href="#admin/list" id="nav-user-list" class="admin-list nav-link nav-admin/list">
                                            <i class="nav-icon fas fa-users"></i>
                                            <p>
                                                Admin List
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a href="#applicant/index" id="nav-applicant-list" class="applicant-list nav-link nav-applicant/index">
                                            <i class="nav-icon fas fa-users"></i>
                                            <p>
                                                Applicant
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a href="" class="nav-link collapsed nav-system_info" data-toggle="collapse" data-target="#collapse-archive" aria-expanded="true" aria-controls="collapse-utilities">
                                            <i class="nav-icon fas fa-book"></i>
                                            <p>
                                                Archive
                                            </p>
                                        </a>
                                        <div id="collapse-archive" class="collapse container z-3" aria-labelledby="headingUtilities" data-parent="#sidebar-accordion">
                                            <div class="bg-white py-2 collapse-inner rounded ml-3 mr-2">
                                                <a class="nav-link collapse-item nav-item d-block pl-4 py-1 text-reset archive-room" href="#archive/room" id="nav-archive-room">Room Reservation</a>
                                                <a class="nav-link collapse-item nav-item d-block pl-4 py-1 text-reset archive-service" href="#archive/service" id="nav-archive-service">Service Reservation</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a href="" class="nav-link collapsed nav-system_info" data-toggle="collapse" data-target="#collapse-setting" aria-expanded="true" aria-controls="collapse-setting">
                                            <i class="nav-icon fas fa-cogs"></i>
                                            <p>
                                                Settings
                                            </p>
                                        </a>
                                        <div id="collapse-setting" class="collapse container" aria-labelledby="headingUtilities" data-parent="#sidebar-accordion">
                                            <div class="bg-white py-2 collapse-inner rounded ml-3 mr-2">
                                                <a class="nav-link nav-system_info collapse-item nav-item d-block pl-4 py-1 text-reset system-index" id="nav-setting" href="#system/index">Website Information</a>
                                                <a class="nav-link nav-system_info collapse-item nav-item d-block pl-4 py-1 text-reset system-payment" id="nav-payment" href="#system/payment">GCash Payment</a>
                                                <!-- <a class="nav-link collapse-item nav-item d-block pl-4 py-1 text-reset" href="#forum.answer-list">Theme</a> -->
                                                <!-- <a class="nav-link collapse-item nav-item d-block pl-4 py-1 text-reset" href="#forum.answer-list">Gallery</a> -->
                                            </div>
                                        </div>
                                    </li>

                                </ul>
                            </nav>
                            <!-- /.sidebar-menu -->
                        </div>
                    </div>
                </div>
                <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
                    <div class="os-scrollbar-track">
                        <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
                    </div>
                </div>
                <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
                    <div class="os-scrollbar-track">
                        <div class="os-scrollbar-handle" style="height: 55.017%; transform: translate(0px, 0px);"></div>
                    </div>
                </div>
                <div class="os-scrollbar-corner"></div>
            </div>
            <!-- /.sidebar -->
        </aside>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper pt-3" style="min-height: 567.854px;">

            <!-- Main content -->
            <section class="content  text-dark">
                <div class="container-fluid">
                    <div id="main-container"></div>
                </div>
            </section>
            <!-- /.content -->
            <div class="modal fade" id="confirm_modal" role='dialog'>
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirmation</h5>
                        </div>
                        <div class="modal-body">
                            <div id="delete_content"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="uni_modal" role='dialog'>
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                        </div>
                        <div class="modal-body">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Submit</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="uni_modal_right" role='dialog'>
                <div class="modal-dialog modal-full-height  modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="fa fa-arrow-right"></span>
        </button>
                        </div>
                        <div class="modal-body">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="viewer_modal" role='dialog'>
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
                        <img src="" alt="">
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-wrapper -->
    </div>
</body>

</body>

<!-- jQuery -->
<script src="./../../resources/plugins/jquery/jquery.min.js"></script>

<!-- jQuery UI 1.11.4 -->
<script src="./../../resources/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- SweetAlert2 -->
<script src="./../../resources/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="./../../resources/plugins/toastr/toastr.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/dist/toastr.min.js"></script> -->
<!-- Bootstrap 4 -->
<script src="./../../resources/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="./../../resources/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="./../../resources/plugins/sparklines/sparkline.js"></script>
<!-- Select2 -->
<script src="./../../resources/plugins/select2/js/select2.full.min.js"></script>
<!-- JQVMap -->
<script src="./../../resources/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="./../../resources/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="./../../resources/plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<!-- daterangepicker -->
<script src="./../../resources/plugins/moment/moment.min.js"></script>
<script src="./../../resources/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="./../../resources/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="./../../resources/plugins/summernote/summernote-bs4.min.js"></script>
<script type="text/javascript" src='./../../resources/plugins/calendar/fullcalendar.min.js'></script>
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="./../../resources/dist/js/adminlte.js"></script>
<script src="./../../resources/assets/js/constants.js"></script>
<script src="./../../resources/assets/js/common.js"></script>
<script src="./../../resources/assets/js/pbrc-admin.js"></script>
<script src="./../../resources/dist/js/script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js "></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>


<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script>



<!-- DataTables Select JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/select/1.3.4/js/dataTables.select.min.js"></script>


<script>
    $(document).ready(function() {
        $("#btn-admin-logout").on("click", function(event) {
            event.preventDefault();


            Swal.fire({
                title: 'Are you sure do you want to logout?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: adminRequest("foundation/main/business/operation/Login.php"),
                            type: "POST",
                            data: {
                                action: 'logout-admin'
                            },
                            cache: false,
                            success: function(dataResult) {
                                var dataResult = JSON.parse(dataResult);
                                if (dataResult.statusCode == 200) {
                                    Swal.fire(
                                    'Logged out!',
                                    '',
                                    'success'
                                    )
                                    location.reload();
                                }
                            },
                            beforeSend: function() {
                                console.log('Request is about to be sent.');
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                // Code to be executed if the request fails
                                console.log('Request failed: ' + textStatus, errorThrown);
                            },
                            complete: function() {
                                // Code to be executed after the request completes, regardless of success or failure
                                console.log('Request completed.');
                            }
                        });
                    }
                })

            
        });

    })
</script>


</html>