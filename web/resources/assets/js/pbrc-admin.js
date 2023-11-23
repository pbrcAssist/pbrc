/**
 * Javascript code during onload event
 */
$("#about").load("web/main/user/section/about-us.html");
$("#events").load("web/main/user/section/events.html");
populateWebsiteInformation();

/**
 * Retrieve website information on database and display it on the UI side
 */
function populateWebsiteInformation() {
    $.ajax({
        url: adminRequest(GET_SYSTEM_URL + GET_WEBSITE_INFO),
        type: GET,
        cache: false,
        success: function(dataResult) {
            var dataResult = JSON.parse(dataResult);
            dataResult.websiteInfo.forEach(map => {
                if ($(map.key).length) {
                    if (map.type == 1) {
                        $(map.key).html(map.value);
                    } else if (map.type == 2) {

                    } else if (map.type == 3) {
                        $(map.key).attr("src", "./../../../" + map.value);
                    } else if (map.type == 4) {
                        $(map.key).attr("style", map.value);
                    } else if (map.type == 5) {

                    } else if (map.type == 6) {
                        $(map.key).html(map.value);
                    }
                }
            });
        }
    });
}

/**
 * JS will be executed once HTML has been loaded
 */
$(document).ready(function() {
    navigateAnchor();
    retrieveUserInformation();

    function navigateAnchor() {
        var form = "./";
        var hash = window.location.hash.replace("#", "");
        var anchor = hash;

        if (anchor == "") {
            form = form + "home.html";
            $(".home").addClass("active");
        } else {
            form = form + anchor + ".html";
            $("." + hash.replace("/", "-")).addClass("active");
        }
        $("#main-container").load(form);
    }

    $(".nav-link").on("click", function() {
        $(".nav-link").removeClass("active");
        $(this).addClass("active");
    });

    $("#nav-dashboard").on("click", function() {
        $("#main-container").load("./home.html");
    });

    $("#nav-event").on("click", function() {
        $("#main-container").load("./events/index.html");
    });

    $("#nav-service-reservation").on("click", function() {
        $("#main-container").load("./service/index.html");
    });

    $("#nav-room-reservation").on("click", function() {
        $("#main-container").load("./room/index.html");
    });

    $("#nav-room-list").on("click", function() {
        $("#main-container").load("./maintenance/room.html");
    });

    $("#nav-room-category").on("click", function() {
        $("#main-container").load("./maintenance/room_category.html");
    });

    $("#nav-room-additional").on("click", function() {
        $("#main-container").load("./maintenance/room_additional.html");
    });

    $("#nav-service-list").on("click", function() {
        $("#main-container").load("./maintenance/service.html");
    });

    $("#nav-package-list").on("click", function() {
        $("#main-container").load("./maintenance/package.html");
    });

    $("#nav-user-list").on("click", function() {
        $("#main-container").load("./admin/list.html");
    });

    $("#nav-applicant-list").on("click", function() {
        $("#main-container").load("./applicant/index.html");
    });

    $("#nav-setting").on("click", function() {
        $("#main-container").load("./system/index.html");
    });

    $("#nav-payment").on("click", function() {
        $("#main-container").load("./system/payment.html");
    });

    $("#nav-my-account").on("click", function() {
        $("#main-container").load("./admin/index.html");
    });

    $("#nav-archive-room").on("click", function() {
        $("#main-container").load("./archive/room.html");
    });

    $("#nav-archive-service").on("click", function() {
        $("#main-container").load("./archive/service.html");
    });



    // retrieveUserInformation();
    // retrieveRoomList();
    // retrieveServiceList();
    // retrieveUpcomingEvents();
    // retrieveGallery();

    /**
     * Start of Event Handler for Nav Menu
     */
    $("#btn-menu-reservation").on("click", function() {
        retrieveUserReservationList();
    });

    $("#button-modal-login").on("click", function() {
        $(".form-login").html("");
        login_source = LOGIN;
        $("#modal-body-login").load(WEB_LOGIN_DIRECTORY, function() {
            $("#form-login-request-from").val("login");
        });
    });


    $('#modal-login').on('shown.bs.modal', function() {
        $(".modal-backdrop").removeClass("d-none");
    });

    $('#modal-login').on('hidden.bs.modal', function() {
        $(".modal-backdrop").addClass("d-none");
    });

    $("#button-logout").on("click", function(event) {
        event.preventDefault();
        $.ajax({
            url: "foundation/main/business/operation/Login.php",
            type: "POST",
            data: {
                action: 'logout-user'
            },
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200) {
                    updateUserMenu(false);
                    logged_in_user = "";
                    alert("Logged out!");
                }
            }
        });
    });
    /**
     * End of Event Handler for Nav Menu
     */


    /**
     * Populate UI for service dropdown and service list section
     */
    function retrieveServiceList() {
        $.ajax({
            url: GET_SERVICE_URL + GET_SERVICE_LIST,
            type: GET,
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                var dropdownService = "";
                service_list = dataResult.serviceList;
                var serviceList = "";
                dataResult.serviceList.forEach(service => {
                    dropdownService += populateServiceListDropDown(service['id'], service['name']);
                    serviceList += populateServiceList(service['image'], service['id'], service['name'], service['description']);
                });
                $("#container-services").html(serviceList);
                $("#dropdown-service").html(dropdownService);

                $(".book-now-service").on("click", function() {
                    $('#modal-book-service-date').val("");
                    $('#modal-book-service-time').val("");
                    $("#modal-book-service-dialog").removeClass('modal-sm');
                    $("#modal-book-service-dialog").removeClass('modal-md');
                    $("#modal-book-service-dialog").removeClass('modal-lg');
                    $("#modal-book-service-dialog").addClass('modal-xl');
                    var id = $(this).data('id');
                    retrieveAppointmentReservationDetail(id);
                    service_list.forEach(service => {
                        if (id == service['id']) {
                            $("#modal-book-service-detail").removeClass("d-none");
                            $("#modal-book-service-login").addClass("d-none");
                            $("#modal-book-service-create-account").addClass("d-none");
                            $("#modal-book-service-change-password").addClass("d-none");
                            $("#modal-book-service-checkout").addClass("d-none");

                            $("#modal-book-service-name").html(service['name']);
                            $("#modal-book-service-id").html(service['id']);
                            $("#modal-book-service-price").html(service['price']);
                            $("#modal-book-service-description").html(service['description']);
                        }
                    });
                });

            }
        });
    }

    /**
     * 
     * @param {string} id - The id of the service 
     * @param {string} name - The name of the service
     * @returns string - html for service dropdown
     */
    function populateServiceListDropDown(id, name) {
        return `<li><a class="dropdown-item dropdown-service book-now-service" href="" data-id="${id}" data-bs-toggle="modal" data-bs-target="#modal-book-service">${name}</a></li>`;
    }

    /**
     * 
     * @param {string} image - The image for the service 
     * @param {string} id - The id of the service
     * @param {string} name - The name of the service
     * @param {string} description - The description othe service
     * @returns string - html for service section
     */
    function populateServiceList(image, id, name, description) {
        return `
        <div class="col-lg-6 col-md-6 portfolio-item filter-3 wow fadeInUp mb-4" data-wow-delay="0.2s">
            <div class="portfolio-wrap">
                <figure>
                    <img src="${image}" class="img-fluid" alt="">
                </figure>
                <div class="portfolio-info">
                    <h4>
                        <a href="" class="book-now-service" data-id="${id}" data-bs-toggle="modal" data-bs-target="#modal-event">${name}</a>
                    </h4>
                    
                    <p>${description}</p>
                    <input type="button" data-bs-toggle="modal" data-id="${id}" data-bs-target="#modal-book-service" class="btn btn-primary btn-sm mt-3 book-now-service" value="Book Now">
                </div>
            </div>
        </div>`;
    }



    /**
     * @param {string} image - Image for upcoming events
     * @param {string} id - Id for upcoming events
     * @param {string} name - Name for upcoming events
     * @param {string} description - Description for upcoming events
     * @param {string} date - Date for upcoming events
     * @param {string} time - Time for upcoming events
     * @returns html for upcoming events
     */
    function populateUpcomingEvents(image, id, name, description, date, time) {
        return `
        <div class="col-lg-4 col-md-6 portfolio-item filter-3 wow fadeInUp" data-wow-delay="0.2s">
            <div class="portfolio-wrap">
                <figure>
                    <img src="${image}" class="img-fluid" alt="">
                </figure>
                <div class="portfolio-info">
                    <h4><a href="" class="btn-upcoming-events" data-bs-toggle="modal" data-id="${id}" data-bs-target="#modal-event"">${name}</a></h4>
                    <b>${date} - ${formatTime(time)}</b>
                    <p>${description} <a href="" class="btn-upcoming-events" data-bs-toggle="modal" data-id="${id}" data-bs-target="#modal-event"">see more</a></p>
                </div>
            </div>
        </div>`;
    }

    function onClickUpcomingEvent() {
        $(".btn-upcoming-events").on('click', function(event) {
            event.preventDefault();
            var id = $(this).data('id');
            upcoming_event.forEach(event => {
                if (id == event['id']) {
                    $("#modal-event-name").html(event['name']);
                    $("#modal-event-schedule").html(event['date'] + " - " + formatTime(event['time']));
                    $("#modal-event-description").html(event['description']);

                    var modalEventImages = `<div class="portfolio-details-slider swiper">
                                            <div class="swiper-wrapper align-items-center">

                                                <div class="swiper-slide">
                                                    <img src="${event['image']}" alt="">
                                                </div>
                                            </div>
                                            <div class="swiper-pagination"></div>
                                        </div>`;

                    $("#modal-event-images").html(modalEventImages);
                }
            });
        });
    }

    function retrieveGallery() {
        $.ajax({
            url: GET_GALLERY_URL + GET_GALLERY,
            type: GET,
            cache: true,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                var indicator = "";
                var inner = "";
                var imageNumber = 0;
                dataResult.gallery.forEach(gallery => {
                    indicator += populateGalleryIndicator(imageNumber);
                    inner += populateGalleryImageAndCaption(gallery['image'], gallery['name'], gallery['description']);
                    imageNumber += 1;
                });

                $("#container-gallery").html(populateGallery(indicator, inner));
            }
        });
    }

    function populateGalleryIndicator(imageNumber) {
        return `<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="${imageNumber}" class="active" aria-current="true" aria-label="Slide 1"></button>`;
    }

    function populateGalleryImageAndCaption(image, name, description) {
        return `<div class="carousel-item active">
                    <img src="${image}" class="d-block w-100 h-75" alt="...">
                    <div class="carousel-caption d-none d-md-block card text-black px-1 mx-1">
                        <h5>${name}</h5>
                        <p>${description}</p>
                    </div>
                </div>`;
    }

    function populateGallery(indicator, inner) {
        return `<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    ${indicator}
                </div>
                <div class="carousel-inner">
                    ${inner}
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
            </div>`;
    }

    function retrieveRoomList() {
        $.ajax({
            url: GET_ROOM_URL + GET_ROOM_LIST,
            type: GET,
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                room_list = dataResult.roomList;
                $("#container-rooms").html(populateRoomList(dataResult));
                $("#dropdown-rooms").html(populateRoomDropdown(dataResult));
                $("#input-search-room").on('keyup', function() {
                    if ($(this).val() != "") {
                        $('.card-room').addClass('d-none');
                    } else {
                        $('.card-room').removeClass('d-none');
                    }
                });

                $(".book-now-room").on('click', function() {
                    $('#modal-book-room-checkin-date').val("");
                    $('#modal-book-room-checkin-time').val("");
                    $('#modal-book-room-checkout-date').val("");
                    $('#modal-book-room-checkout-time').val("");
                    $('#modal-book-room-date').val("");
                    $('#modal-book-room-time').val("");
                    var id = $(this).data('id');
                    retrieveRoomReservationList(id);
                    room_list.forEach(room => {
                        var image = `<div class="portfolio-details-slider swiper">
                                                    <div class="swiper-wrapper align-items-center">

                                                        <div class="swiper-slide">
                                                            <img src="${room['image']}" class="card-img-top" alt="">
                                                        </div>
                                                    </div>
                                                    <div class="swiper-pagination"></div>
                                                </div>`;

                        if (id == room['id']) {
                            $("#modal-book-room-detail").removeClass("d-none");
                            $("#modal-book-room-login").addClass("d-none");
                            $("#modal-book-room-create-account").addClass("d-none");
                            $("#modal-book-room-change-password").addClass("d-none");
                            $("#modal-book-room-checkout").addClass("d-none");

                            $("#modal-book-room-images").html(image);
                            $("#modal-book-room-name").html(room['name']);
                            $("#modal-book-room-id").html(room['id']);
                            $("#modal-book-room-price").html(room['price']);
                            $("#modal-book-room-pax").html(room['pax']);
                            $("#modal-book-room-description").html(room['description']);
                            $("#modal-book-room-amenities").html(room['amenities']);
                        }
                    });
                });
            }
        });
    }

    function retrieveAppointmentReservationDetail(serviceID) {
        $.ajax({
            url: GET_SERVICE_URL + GET_SERVICE_RESERVATION_LIST,
            type: GET,
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                service_reservation_list = dataResult;
                var datesForDisable = []
                if (dataResult.statusCode == 200) {
                    dataResult.reservationList.forEach(reservation => {
                        datesForDisable.push(reservation['checkinDate']);
                    });
                }
                var d = new Date();
                $('#modal-book-service-date').datepicker(DESTROY);
                $('#modal-book-service-date').datepicker(populateDatepickerConfiguration(datesForDisable));

                $('#modal-book-service-date').on('changeDate', function() {
                    console.log("dataresult");
                    var checkoutTime = "";
                    var nextDayCheckinTime = "";
                    if (service_reservation_list.statusCode == 200) {
                        var checkinDate = $(this).val();
                        var nextDayCheckinDate = plusDay(checkinDate, 1);

                        service_reservation_list.reservationList.forEach(reservation => {
                            if (reservation['checkinDate'] == nextDayCheckinDate) {
                                nextDayCheckinTime = reservation['checkinTime'];
                            }
                        });
                    }
                    $("#modal-book-service-time").html(populateTime(checkoutTime, nextDayCheckinTime));
                });
            }
        });
    }



    function getUnoccupiedRoom(occupiedRoom) {
        var unOccupiedRoom = [];

        room_reservation_list.roomList.forEach(room => {
            var valueToCheck = room['roomID'];
            if ($.inArray(valueToCheck, occupiedRoom) === -1) {
                console.log(valueToCheck + " does not exist in the array.");
                unOccupiedRoom.push(valueToCheck);
            }
        });
        return unOccupiedRoom;
    }

    function populateAvailableTime(roomList, date) {
        var availableTimeInMap = {};
        var availableTimeIn = "";
        var roomNumber = "";
        var availableTimeOutMap = {};
        room_reservation_list.roomList.forEach(room => {
            var roomID = room['roomID'];
            // Check if the value on array is equals to available room
            if ($.inArray(roomID, roomList) !== -1) {
                room.reservationList.forEach(reservation => {
                    if (reservation['checkoutDate'] == minusDay(date, 1)) {

                        if (availableTimeIn == "") {
                            availableTimeIn = reservation['checkoutTime'];
                        } else {
                            if (!compareTwoTime(availableTimeIn, reservation['checkoutTime'])) {
                                availableTimeIn = reservation['checkoutTime'];
                            }
                        }
                        addValueToMap(availableTimeInMap, roomID, reservation['checkinTime']);
                    }
                    if (reservation['checkinDate'] == plusDay(date, 1)) {
                        addValueToMap(availableTimeOutMap, roomID, reservation['checkoutTime']);
                    }
                });
            }
        });

        $("#modal-book-room-checkin-time").html(populateTime(availableTimeIn, ""));

    }

    // Start of Room Reservation

    // Populate calendar for checkin and checkoutDate
    function retrieveRoomReservationList(roomID) {
        roomCategoryID = roomID;
        $.ajax({
            url: GET_ROOM_URL + GET_ROOM_RESERVATION_LIST + populateQueryParameter('roomCategoryID', roomID),
            type: GET,
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                room_reservation_list = dataResult;
                var datesForDisable = [];

                var totalRoomNumber = dataResult.totalRoomNumber;

                var hashmap = {};
                var occupiedDate = {};

                if (dataResult.statusCode == 200) {
                    dataResult.roomList.forEach(room => {
                        room.reservationList.forEach(reservation => {
                            var key = room['roomID'];
                            var value;
                            let currentDate = new Date(reservation['checkinDate']);
                            endDate = new Date(reservation['checkoutDate']);
                            while (currentDate <= endDate) {
                                value = formatDate(currentDate);
                                currentDate = new Date(currentDate);
                                currentDate.setDate(currentDate.getDate() + 1);

                                if (!keyExists(hashmap, key)) {
                                    hashmap[key] = [value];
                                } else {
                                    if (!valueExists(hashmap, value)) {
                                        addValueToKey(hashmap, key, value);
                                    }
                                }

                                if (isDateOccupied(hashmap, occupiedDate, value, totalRoomNumber)) {
                                    datesForDisable.push(value);
                                }
                            }
                        });
                    });
                }

                buildCheckinCalendar(datesForDisable);
                onChangeCheckinCalendar(occupiedDate, datesForDisable);
                onChangeCheckinTime(datesForDisable);
                populatePrice();
            }
        });
    }

    // Event handler for onChange event of check in time 
    function onChangeCheckinTime(datesForDisable) {
        $("#modal-book-room-checkin-time").on("change", function() {
            if ($(this).val() == "") {
                return;
            }
            var checkinDate = $('#modal-book-room-checkin-date').val();

            buildCheckoutCalendar(datesForDisable, plusDay(checkinDate, 1), getNextReserveDate(checkinDate, datesForDisable));

            $("#modal-book-room-checkout-time").val(formatTime($("#modal-book-room-checkin-time").val()));
            $("#modal-book-room-checkout-detail").removeClass("d-none");
        });
    }

    // Event handler for onChange event of check in date
    function onChangeCheckinCalendar(occupiedDate, datesForDisable) {
        $('#modal-book-room-checkin-date').on('changeDate', function() {
            var checkinDate = $(this).val();
            var checkoutTime = "";
            var nextDayCheckinTime = "";

            if (!keyExists(occupiedDate, checkinDate)) {
                $("#modal-book-room-checkin-time").html(populateTime(checkoutTime, nextDayCheckinTime));
            } else {
                var unOccupiedRoom = getUnoccupiedRoom(occupiedDate[checkinDate]);
                populateAvailableTime(unOccupiedRoom, checkinDate);
            }
            buildCheckoutCalendar(datesForDisable, plusDay($('#modal-book-room-checkin-date').val(), 1), getNextReserveDate(checkinDate, datesForDisable));
        });
    }

    // Build bootstrap calendar for checkout calendar
    function buildCheckoutCalendar(datesForDisable, startDate, endDate) {
        $('#modal-book-room-checkout-date').datepicker("destroy");
        $('#modal-book-room-checkout-date').datepicker({
            format: 'mm-dd-yyyy',
            autoclose: true,
            todayHighlight: true,
            datesDisabled: datesForDisable,
            daysOfWeekHighlighted: '0,6',
            daysOfWeekDisabled: [0, 6],
            minDate: 0,
            format: 'yyyy-mm-dd',
            endDate: endDate,
            startDate: startDate
        });
    }

    // Build bootstrap calendar for checkin calendar
    function buildCheckinCalendar(datesForDisable) {
        $('#modal-book-room-checkin-date').datepicker("destroy");
        $('#modal-book-room-checkin-date').datepicker({
            format: 'mm-dd-yyyy',
            autoclose: true,
            todayHighlight: true,
            datesDisabled: datesForDisable,
            daysOfWeekHighlighted: '0,6',
            daysOfWeekDisabled: [0, 6],
            minDate: 0,
            format: 'yyyy-mm-dd',
            startDate: new Date()
        });
    }
    // End of Room Reservation


    // Start of Room Additional Section

    // Event handler for onChange of Additional Guest
    $("input[type=radio][name='option-additional-guest']").on("change", function() {
        if ($(this).val() == 1) {
            $('#collapse-additional-guest').collapse('show');
        } else {
            $('#collapse-additional-guest').collapse('hide');
        }
    });

    // Event handler for onChange of Additional Food
    $("input[type=radio][name='option-additional-food']").on("change", function() {
        if ($(this).val() == 1) {
            $('#collapse-additional-food').collapse('show');
        } else {
            $('#collapse-additional-food').collapse('hide');
        }
    });

    // Event handler for onChange of Additional Item
    $("input[type=radio][name='option-additional-item']").on("change", function() {
        if ($(this).val() == 1) {
            $('#collapse-additional-item').collapse('show');
        } else {
            $('#collapse-additional-item').collapse('hide');
        }
    });


    // End of Room Additional Section

    $('#form-booking-room-information').on("submit", function(event) {
        event.preventDefault();
        $("#modal-book-room-detail").addClass("d-none");
        if ($(".menu-user").hasClass("d-none")) {
            $(".form-login").html("");
            login_source = ROOM;
            $("#modal-book-room-login").load(WEB_LOGIN_DIRECTORY);
            $("#modal-book-room-create-account").load("web/main/user/form/form-create-account.html");
            $("#modal-book-room-login").removeClass("d-none");
            $("#modal-book-now-dialog").removeClass("modal-xl");
            $("#modal-book-now-dialog").removeClass("modal-sm");
        } else {
            $("#modal-book-room-checkout").load("web/main/user/form/form-room-checkout.html", function() {


                populateRoomCheckoutUserInformation(logged_in_user);
            });
            $("#modal-book-now-dialog").addClass("modal-xl");
            $("#modal-book-room-checkout").removeClass("d-none");
        }
    });

    function populatePrice() {
        $(".price-children").html(CHILDREN_PRICE);
        $(".price-adult").html(ADULT_PRICE);

        $(".price-breakfast").html(BREAKFAST_PRICE);
        $(".price-lunch").html(LUNCH_PRICE);
        $(".price-snack").html(SNACK_PRICE);
        $(".price-dinner").html(DINNER_PRICE);

        $(".price-towel").html(TOWEL_PRICE);
        $(".price-pillow").html(PILLOW_PRICE);
        $(".price-blanket").html(BLANKET_PRICE);
        $(".price-bed").html(BED_PRICE);
        $(".price-videoke").html(VIDEOKE_PRICE);
        $(".price-sound-system").html(SOUND_SYSTEM_PRICE);
        $(".price-chair").html(CHAIR_PRICE);
        $(".price-table").html(TABLE_PRICE);
        $(".room-pax").html($("#modal-book-room-pax").html());
    };

    // End of checkout section

    function populateTime(checkoutTime, nextDayCheckinTime) {
        console.log("checkoutTIme " + checkoutTime);
        var dropdownTime = "";
        var isDisabled = checkoutTime != "" ? "disabled" : "";
        var unavailable = checkoutTime != "" ? " (unavailable)" : "";

        dropdownTime += `<option value="">- Please select -</option>`;
        for (x = 8; x <= 16; x++) {
            var hour = x < 10 ? "0".concat(x) : x;

            if (hour + ":00:00" == checkoutTime) {
                isDisabled = "";
                unavailable = "";
            }

            if (hour + ":00:00" == nextDayCheckinTime) {
                isDisabled = "disabled";
                unavailable = " (unavailable)";
            }

            dropdownTime += `<option value="${x}:00" ${isDisabled}> ${formatTime(x + ":00")} ${unavailable}</option>`


            if (hour + ":30:00" == checkoutTime) {
                isDisabled = "";
                unavailable = "";
            }

            if (hour + ":30:00" == nextDayCheckinTime) {
                isDisabled = "disabled";
                unavailable = " (unavailable)";
            }
            dropdownTime += `<option value="${x}:30" ${isDisabled}>${formatTime(x + ":30")} ${unavailable}</option>`;
        }

        return dropdownTime;
    }



    function populateRoomDropdown(dataResult) {
        var roomsDropDown = "";
        dataResult.roomList.forEach(room => {
            roomsDropDown += `<li><a class="dropdown-item dropdown-rooms book-now-room" data-bs-toggle="modal" data-bs-target="#modal-book-room" data-id="${room['id']}" href="">${room['name']}</a></li>`;
        });
        return roomsDropDown;
    }



    function populateRoomList(dataResult) {
        var roomList = "";
        var formatRight = true;
        dataResult.roomList.forEach(room => {
            if (formatRight) {
                roomList += `<div id="room-${room['id']}" class="card-room col-lg-12 col-md-12 portfolio-item filter-3 wow fadeInUp mb-4" data-wow-delay="0.2s">
                                    <div class="p-5 portfolio-wrap">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <figure>
                                                    <img src="${room['image']}" class="img-fluid" alt="">
                                                </figure>    
                                            </div>

                                            <div class="col-lg-8">
                                                <div class="portfolio-info">
                                                    <h4>
                                                        <a href="" data-bs-toggle="modal" data-bs-target="#modal-event">${room['name']}</a>
                                                    </h4>
                                                    
                                                    <h5>${room['price']} PHP</h5>
                                                    ${room['description']}
                                                    
                                                    
                                                </div>
                                                <button class="btn btn-primary align-bottom book-now-room" data-bs-toggle="modal" data-bs-target="#modal-book-room" data-id="${room['id']}">Book Now</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>`;
                formatRight = false;
            } else {
                roomList += `<div id="room-${room['id']}" class="card-room col-lg-12 col-md-12 portfolio-item filter-3 wow fadeInUp mb-4" data-wow-delay="0.2s">
                                    <div class="p-5 portfolio-wrap">
                                        <div class="row">

                                            <div class="col-lg-8">
                                                <div class="portfolio-info">
                                                    <h4>
                                                        <a href="" data-bs-toggle="modal" data-bs-target="#modal-event">${room['name']}</a>
                                                    </h4>
                                                    
                                                    <h5>${room['price']}</h5>
                                                    ${room['description']}                                                        
                                                </div>
                                                <button class="mb-4 btn btn-primary align-bottom book-now-room" data-bs-toggle="modal" data-bs-target="#modal-book-room" data-id="${room['id']}">Book Now</button>
                                            </div>

                                            <div class="col-lg-4">
                                                <figure>
                                                    <img src="${room['image']}" class="img-fluid" alt="">
                                                </figure>    
                                            </div>

                                        </div>
                                    </div>
                                </div>`;
                formatRight = true;
            }
        });
        return roomList;
    }


    function retrieveUserInformation() {
        $.ajax({
            url: adminRequest(GET_LOGIN_URL + "retrieve-admin-information"),
            type: GET,
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200) {
                    $("#admin-username").html(dataResult.accountDetail.username);
                } else {}
            }
        });
    }

    function retrieveUserReservationList() {
        if (logged_in_user == null || logged_in_user[id]) {
            $('#dataTable').DataTable();
            return;
        }
        var id = logged_in_user['id'];
        $.ajax({
            url: GET_ROOM_URL + GET_USER_ROOM_RESERVATION + populateQueryParameter("accountID", id),
            type: GET,
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode != 404) {
                    var reservationList = "";
                    dataResult.reservationList.forEach(reservation => {
                        reservationList += `<tr>
                            <td>${reservation['id']}</td>
                            <td><a class="">${reservation['name']}</a></td>
                            <td><a class="">${reservation['checkinDate']}  ${reservation['checkinTime']}</a></td>
                            <td>${reservation['status'] == 1? "Unpaid" : "Paid"}</td>
                            <td>
                                <a class='btn-select btn btn-xs btn-primary' disabled>Pay</a>
                                <a class='btn-select btn btn-xs btn-success' disabled>Reschedule</a>
                            </tr>`;
                    });
                    $("#tableBody").html(reservationList);
                }
                $('#dataTable').DataTable();
            }
        });
    }


    // Start of FAQ section

    $(".faqs-container .faq-singular:first-child").addClass("active").children(".faq-answer").slideDown(); //Remove this line if you dont want the first item to be opened automatically.

    $(".faq-question").on("click", function() {
        if ($(this).parent().hasClass("active")) {
            $(this).next().slideUp();
            $(this).parent().removeClass("active");
        } else {
            $(".faq-answer").slideUp();
            $(".faq-singular").removeClass("active");
            $(this).parent().addClass("active");
            $(this).next().slideDown();
        }
    });
    // End of FAQ section

    $('.textarea-auto-height').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

});