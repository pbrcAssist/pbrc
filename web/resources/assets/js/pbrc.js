/**
 * Javascript code during onload event
 */
$("#about").load("web/main/user/section/about-us.html");
$("#events").load("web/main/user/section/events.html");
populateWebsiteInformation();


function checkChangePasswordStrength() {
    var number = /([0-9])/;
    var alphabets = /([a-zA-Z])/;
    var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
    var password = $("#new-password").val().trim();
    if (password.length < 6) {
        $('#change-password-strength-status').removeClass();
        $('#change-password-strength-status').addClass('weak-password');
        $('#change-password-strength-status').html("Weak (should be atleast 6 characters.)");
        $('#new-password').addClass('is-invalid');
        return false;
    } else {
        if (password.match(number) && password.match(alphabets) && password.match(special_characters)) {
            $('#change-password-strength-status').removeClass();
            $('#change-password-strength-status').addClass('strong-password');
            $('#change-password-strength-status').html("Strong");
            $('#new-password').removeClass('is-invalid');
            return true;
        } else {
            $('#change-password-strength-status').removeClass();
            $('#change-password-strength-status').addClass('medium-password');
            $('#change-password-strength-status').html("Medium (should include alphabets, numbers and special characters.)");
            $('#new-password').addClass('is-invalid');
            return false;
        }
    }
}

function isValidProfilePhoneInput() {
    const phoneInput = $("#input-profile-phone");
    const phoneValue = phoneInput.val().trim();
    const phonePattern = /^(09\d{9}|(\+639|09)\d{9}|0[1-9]\d{6,7})$/;
    if (!phonePattern.test(phoneValue)) {
        phoneInput.addClass("is-invalid");
        // phoneInput.focus();
        return false;
    } else {
        phoneInput.removeClass("is-invalid");
        return true;
    }
}

/**
 * Retrieve website information on database and display it on the UI side
 */
function populateWebsiteInformation() {
    $.ajax({
        url: GET_SYSTEM_URL + GET_WEBSITE_INFO,
        type: GET,
        cache: false,
        success: function(dataResult) {
            var dataResult = JSON.parse(dataResult);
            dataResult.websiteInfo.forEach(map => {
                if ($(map.key).length) {
                    if (map.type == 1) {
                        -
                        $(map.key).html(map.value);
                    } else if (map.type == 2) {

                    } else if (map.type == 3) {
                        $(map.key).attr("src", map.value);
                    } else if (map.type == 4) {
                        $(map.key).attr("style", `background: url("${map.value}") center center; background-size: cover;`);
                    } else if (map.type == 5) {

                    } else if (map.type == 6) {
                        $(map.key).html(map.value);
                    } else if (map.type == 7) {
                        $(map.key).attr("href", "./" + map.value);
                    }
                }
            });
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

$("#additional-guest-adult").on("input", function() {
    if (isAdditionalPaxExceed()) {
        $(this).val("");
    }
});

$("#additional-guest-children").on("input", function() {
    if (isAdditionalPaxExceed()) {
        $(this).val("");
    }
})

function isAdditionalPaxExceed() {
    var paxChildren = $("#additional-guest-children").val();
    var paxAdult = $("#additional-guest-adult").val();
    var pax = parseInt($("#modal-book-room-pax").html(), 10) || 0; // Parse as integer or default to 0
    var maxPax = parseInt($("#modal-book-room-max-pax").html(), 10) || 0; // Parse as integer or default to 0
    var additionalPax = maxPax - pax;

    console.log(paxChildren + " - " + paxAdult);

    // Check if values are valid numbers before parsing
    var addedPax = (parseInt(paxChildren, 10) || 0) + (parseInt(paxAdult, 10) || 0);

    console.log(addedPax + " - " + additionalPax);

    if (addedPax > additionalPax) {
        console.log("exceed");
        var message = `You have exceeded the ${additionalPax} additional pax allowed`;
        Swal.fire({
            title: 'Info',
            text: message,
            icon: 'info'
        });
        return true;
    } else {
        console.log("fine");
        return false;
    }
}


$("#additional-food-breakfast").on("change", function() {
    var value = $(this).val();
    if (value != 0 && value != "") {
        $("#additional-food-breakfast-serving").attr("required", true);
    } else {
        $("#additional-food-breakfast-serving").removeAttr("required");
        $("#additional-food-breakfast-serving").val("");
    }
});

$("#additional-food-lunch").on("change", function() {
    var value = $(this).val();
    if (value != 0 && value != "") {
        $("#additional-food-lunch-serving").attr("required", true);
    } else {
        $("#additional-food-lunch-serving").removeAttr("required");
        $("#additional-food-lunch-serving").val("");
    }
});

$("#additional-food-snack").on("change", function() {
    var value = $(this).val();
    if (value != 0 && value != "") {
        $("#additional-food-snack-serving").attr("required", true);
    } else {
        $("#additional-food-snack-serving").removeAttr("required");
        $("#additional-food-snack-serving").val("");
    }
});

$("#additional-food-dinner").on("change", function() {
    var value = $(this).val();
    if (value != 0 && value != "") {
        $("#additional-food-dinner-serving").attr("required", true);
    } else {
        $("#additional-food-dinner-serving").removeAttr("required");
        $("#additional-food-dinner-serving").val("");
    }
});

// Start of checkout section
function populateRoomCheckoutUserInformation(userDetail) {
    initializeValue();
    $("#room-checkout-name").html(userDetail['firstName'] + " " + userDetail['middleName'] + " " + userDetail['lastName']);
    $("#room-checkout-address").html(userDetail['barangay'] + ", " + userDetail['municipality'] + ", " + userDetail['province']);
    $("#room-checkout-phone").html(userDetail['phone']);
    $("#room-checkout-email").html(userDetail['email']);

    var checkinDate = $("#modal-book-room-checkin-date").val();
    var checkinTime = formatTime($("#modal-book-room-checkin-time").val());

    var checkoutDate = $("#modal-book-room-checkout-date").val();
    var checkoutTime = checkinTime;

    checkinRoomDate = checkinDate;
    checkinRoomTime = $("#modal-book-room-checkin-time").val();
    checkoutRoomDate = checkoutDate;
    checkoutRoomTime = $("#modal-book-room-checkin-time").val();

    roomPrice = $("#modal-book-room-price").html();
    totalDays = getDateDifference(checkinDate, checkoutDate);
    console.log("totalDaysPrice: " + totalDays + " " + roomPrice);
    totalDaysPrice = totalDays * roomPrice.replace(/[^0-9]/g, '');

    var additionalGuest = $("input[type=radio][name='option-additional-guest']:checked").val();
    var additionalFood = $("input[type=radio][name='option-additional-food']:checked").val();
    var additionalItem = $("input[type=radio][name='option-additional-item']:checked").val();

    if (additionalGuest == 0) {
        $("#room-checkout-room-additional-guest").html("None");
    } else if (additionalGuest == 1) {
        var additionalGuestCheckoutText = "";
        additionalGuestChildren = $("#additional-guest-children").val();
        additionalGuestAdult = $("#additional-guest-adult").val();
        console.log((additionalGuestChildren * CHILDREN_PRICE) * totalDays);
        if (isNotBlank(additionalGuestChildren)) {
            totalPriceGuestChildrenPrice = (additionalGuestChildren * CHILDREN_PRICE) * totalDays;
            additionalGuestCheckoutText += populateAdditionalCheckoutRow("Children", additionalGuestChildren, PAX, totalPriceGuestChildrenPrice);
        }

        if (isNotBlank(additionalGuestAdult)) {
            totalPriceGuestAdultPrice = (additionalGuestAdult * ADULT_PRICE) * totalDays;
            additionalGuestCheckoutText += populateAdditionalCheckoutRow("Adult", additionalGuestAdult, PAX, totalPriceGuestAdultPrice);
        }

        if (additionalGuestCheckoutText != "") {
            $("#room-checkout-room-additional-guest").html(populateOrderedList(additionalGuestCheckoutText));
        } else {
            $("#room-checkout-room-additional-guest").html("None");
        }
    }

    if (additionalFood == 0) {
        $("#room-checkout-room-additional-food").html("None");
    } else if (additionalFood == 1) {
        var additionalFoodCheckoutText = "";
        additionalFoodBreakfast = $("#additional-food-breakfast").val();
        additionalFoodBreakfastServing = $("#additional-food-breakfast-serving").val();

        additionalFoodLunch = $("#additional-food-lunch").val();
        additionalFoodLunchServing = $("#additional-food-lunch-serving").val();

        additionalFoodSnack = $("#additional-food-snack").val();
        additionalFoodSnackServing = $("#additional-food-snack-serving").val();

        additionalFoodDinner = $("#additional-food-dinner").val();
        additionalFoodDinnerServing = $("#additional-food-dinner-serving").val();

        if (isNotBlank(additionalFoodBreakfast)) {
            totalPriceBreakfast = (additionalFoodBreakfast * BREAKFAST_PRICE) * additionalFoodBreakfastServing;
            additionalFoodCheckoutText += populateAdditionalFoodCheckoutRow("Breakfast", additionalFoodBreakfast, additionalFoodBreakfastServing, totalPriceBreakfast);
        }

        if (isNotBlank(additionalFoodLunch)) {
            totalPriceLunch = (additionalFoodLunch * LUNCH_PRICE) * additionalFoodLunchServing;
            additionalFoodCheckoutText += populateAdditionalFoodCheckoutRow("Lunch", additionalFoodLunch, additionalFoodLunchServing, totalPriceLunch);
        }

        if (isNotBlank(additionalFoodSnack)) {
            totalPriceSnack = (additionalFoodSnack * SNACK_PRICE) * additionalFoodSnackServing;
            additionalFoodCheckoutText += populateAdditionalFoodCheckoutRow("Snack", additionalFoodSnack, additionalFoodSnackServing, totalPriceSnack);
        }

        if (isNotBlank(additionalFoodDinner)) {
            totalPriceDinner = (additionalFoodDinner * DINNER_PRICE) * additionalFoodDinnerServing;
            additionalFoodCheckoutText += populateAdditionalFoodCheckoutRow("Dinner", additionalFoodDinner, additionalFoodDinnerServing, totalPriceDinner);
        }
        foodSpecialInstruction = $("#additional-food-special-instruction").val();
        if (foodSpecialInstruction != "") {
            additionalFoodCheckoutText += `<li class="list-group-item d-flex justify-content-between align-items-start">
                                                <div class="ms-2 me-auto">
                                                    <div class="fw-bold">Special Instruction</div>
                                                    ${foodSpecialInstruction}
                                                </div>
                                            </li>`;
        }

        if (additionalFoodCheckoutText != "") {
            $("#room-checkout-room-additional-food").html(populateOrderedList(additionalFoodCheckoutText));
        } else {
            $("#room-checkout-room-additional-food").html("None");
        }
    }

    if (additionalItem == 0) {
        $("#room-checkout-room-additional-item").html("None");
    } else if (additionalItem == 1) {
        var additionalItemCheckoutText = "";
        additionalItemTowel = $("#additional-item-towel").val();
        additionalItemPillow = $("#additional-item-pillow").val();
        additionalItemBlanket = $("#additional-item-blanket").val();
        additionalItemBed = $("#additional-item-bed").val();
        additionalItemVideoke = $("#additional-item-videoke").val();
        additionalItemSoundSystem = $("#additional-item-sound-system").val();
        additionalItemChair = $("#additional-item-chair").val();
        additionalItemTable = $("#additional-item-table").val();

        if (isNotBlank(additionalItemTowel)) {
            totalPriceTowel = (additionalItemTowel * TOWEL_PRICE) * totalDays;
            additionalItemCheckoutText += populateAdditionalCheckoutRow("Towel", additionalItemTowel, QUANTITY, totalPriceTowel);
        }

        if (isNotBlank(additionalItemPillow)) {
            totalPricePillow = (additionalItemPillow * PILLOW_PRICE) * totalDays;
            additionalItemCheckoutText += populateAdditionalCheckoutRow("Pillow", additionalItemPillow, QUANTITY, totalPricePillow);
        }

        if (isNotBlank(additionalItemBlanket)) {
            totalPriceBlanket = (additionalItemBlanket * BLANKET_PRICE) * totalDays;
            additionalItemCheckoutText += populateAdditionalCheckoutRow("Blanket", additionalItemBlanket, QUANTITY, totalPriceBlanket);
        }

        if (isNotBlank(additionalItemBed)) {
            totalPriceBed = (additionalItemBed * BED_PRICE) * totalDays;
            additionalItemCheckoutText += populateAdditionalCheckoutRow("Bed", additionalItemBed, QUANTITY, totalPriceBed);
        }

        if (additionalItemCheckoutText != "") {
            $("#room-checkout-room-additional-item").html(populateOrderedList(additionalItemCheckoutText));
        } else {
            $("#room-checkout-room-additional-item").html("None");
        }
    }


    $("#room-checkout-total-days").html(totalDays);
    $("#room-checkout-total-days-price").html(formatMoney(totalDaysPrice));
    $("#room-checkout-room-price-per-day").html(roomPrice);

    console.log("Total Days" + totalDays + "-" + totalDaysPrice);
    $("#room-checkin-datetime").html(checkinDate + " " + checkinTime);
    $("#room-checkout-datetime").html(checkoutDate + " " + checkoutTime);

    $("#room-checkout-room-name").html($("#modal-book-room-name").html());

    // $("#room-checkout-room-id").html($("#modal-book-room-id").html());
    $("#room-checkout-room-price").html($("#modal-book-room-price").html());
    $("#room-checkout-room-pax").html($("#modal-book-room-pax").html());
    $("#room-checkout-room-description").html($("#modal-book-room-description").html());
    $("#room-checkout-room-amenities").html($("#modal-book-room-amenities").html());

    totalGuestPrice = totalPriceGuestChildrenPrice + totalPriceGuestAdultPrice;

    // Times total days
    totalFoodPrice =
        totalPriceBreakfast +
        totalPriceLunch +
        totalPriceSnack +
        totalPriceDinner;

    totalItemPrice = totalPriceTowel +
        totalPricePillow +
        totalPriceBlanket +
        totalPriceBed;

    totalAmount = totalDaysPrice + totalGuestPrice + totalFoodPrice + totalItemPrice;
    totalAdditionalPrice = totalGuestPrice + totalFoodPrice + totalItemPrice;

    $("#room-checkout-additional-price").html(formatMoney(totalAdditionalPrice));
    $("#room-checkout-total-amount").html(formatMoney(totalAmount));
}

// Start of checkout section
function populateServiceCheckoutUserInformation(userDetail) {
    initializeValue();
    $("#service-checkout-name").html(userDetail['firstName'] + " " + userDetail['middleName'] + " " + userDetail['lastName']);
    $("#service-checkout-address").html(userDetail['barangay'] + ", " + userDetail['municipality'] + ", " + userDetail['province']);
    $("#service-checkout-phone").html(userDetail['phone']);
    $("#service-checkout-email").html(userDetail['email']);

    var checkinDate = $("#modal-book-service-date").val();
    var checkinTime = formatTime($("#modal-book-service-time").val());

    servicePrice = $("#modal-book-service-price").html();
    $("#service-checkin-date").html(checkinDate);
    $("#service-checkin-time").html(checkinTime);

    $("#service-checkout-service-name").html($("#modal-book-service-name").html());
    $("#service-checkout-service-price").html($("#modal-book-service-price").html());
    $("#service-checkout-service-description").html($("#modal-book-service-description").html());

    $("#service-checkout-service-hour").html($("#modal-book-service-hour").html());

    $("#service-checkout-total-amount").html(servicePrice + " PHP");
}


/**
 * JS will be executed once HTML has been loaded
 */
$(document).ready(function() {
    retrieveUserInformation();
    retrieveRoomList();
    retrieveServiceList();
    retrieveUpcomingEvents();
    retrieveGallery();

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
                    $("#container-service-package").html("");
                    $("#modal-book-package-id").html("");
                    var id = $(this).data('id');
                    service_id = "";
                    package_id = "";
                    service_id = id;
                    service_list.forEach(service => {
                        if (id == service['id']) {
                            $("#modal-book-service-login").addClass("d-none");
                            $("#modal-book-service-create-account").addClass("d-none");
                            $("#modal-book-service-change-password").addClass("d-none");
                            $("#modal-book-service-checkout").addClass("d-none");
                            retrieveAppointmentReservationDetail(service['time']);
                            $("#modal-book-service-name").html(service['name']);
                            $("#modal-book-service-id").html(service['id']);
                            $("#modal-book-service-price").html(service['price']);
                            $("#modal-book-service-description").html(service['description']);
                            $("#modal-book-service-hour").html(service['time']);

                            $("#container-service-package").append(populatePackageCard("basic", service['name'], service['description'], service['price']))
                        }
                    });
                    retrieveServicePackage(id);
                });
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


    function retrieveServicePackage(serviceID) {
        $.ajax({
            url: GET_PACKAGE_URL + "retrieve-service-package" + populateQueryParameter('service_id', serviceID),
            type: GET,
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                service_reservation_list = dataResult;
                if (dataResult.statusCode == 200) {
                    dataResult.packageList.forEach(package => {
                        $("#container-service-package").append(populatePackageCard(package.id, package.name, package.description, package.price));
                    });
                    if (dataResult.packageList !== null && typeof dataResult.packageList !== 'undefined' && dataResult.packageList.length > 0) {
                        $("#container-service-package").removeClass("d-none");
                        $("#modal-book-service-detail").addClass("d-none");
                    } else {
                        $("#container-service-package").addClass("d-none");
                        $("#modal-book-service-detail").removeClass("d-none");
                    }
                }

                $(".btn-select-package").on("click", function() {
                    var id = $(this).data("id");
                    package_id = id;
                    if (id != "basic") {
                        dataResult.packageList.forEach(package => {
                            if (id == package.id) {
                                $("#modal-book-service-name").html(package.name);
                                $("#modal-book-service-id").html(serviceID);
                                $("#modal-book-package-id").html(package.id);
                                $("#modal-book-service-price").html(package.price);
                                $("#modal-book-service-description").html(package.description);
                                $("#modal-book-service-hour").html(package.duration);
                            }
                            $("#container-service-package").append(populatePackageCard(package.id, package.name, package.description, package.price));
                        });
                    }
                    $("#container-service-package").addClass("d-none");
                    $("#modal-book-service-detail").removeClass("d-none");
                });
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

    function populatePackageCard(id, name, description, price) {
        return `<div class="col-lg-4 mb-4">
        <div class="container h-100">
            <div class="card shadow d-flex flex-column h-100 my-card">
                <div class="card-body d-flex flex-column">
                    <h3 class="card-title">${name}</h3>
                    <ul class="list-group list-group-flush flex-grow-1 d-flex flex-column align-items-stretch">
                        <li class="list-group-item"><strong>Description</strong>: <p>${description}</p></li>
                        <li class="list-group-item"><strong>Price</strong>: ${formatMoney(price)}</li>
                    </ul>
                    <div class="text-center mt-3">
                        <input type="button" data-id="${id}" class="btn btn-primary btn-block w-100 btn-select-package" value="Select">
                    </div>
                </div>
            </div>
        </div>
    </div>`;
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
        // return `
        // <div class="col-lg-6 col-md-6 portfolio-item filter-3 wow fadeInUp mb-4" data-wow-delay="0.2s">
        //     <div class="portfolio-wrap">
        //         <figure>
        //             <img loading="lazy" src="${image}" class="img-fluid" alt="">
        //         </figure>
        //         <div class="portfolio-info">
        //             <h4>
        //                 <a href="" class="book-now-service" data-id="${id}" data-bs-toggle="modal" data-bs-target="#modal-event">${name}</a>
        //             </h4>

        //             <p>${description}</p>
        //             <input type="button" data-bs-toggle="modal" data-id="${id}" data-bs-target="#modal-book-service" class="btn btn-primary btn-sm mt-3 book-now-service" value="Book Now">
        //         </div>
        //     </div>
        // </div>`;

        return `<div class="col-lg-6 col-md-6 portfolio-item filter-3 wow fadeInUp mb-4">
        <div class="portfolio-wrap h-100 d-flex flex-column">
            <figure>
                <img loading="lazy" style="height: 100% !important" src="${image}" class="img-fluid" alt="">
            </figure>
            <div class="portfolio-info flex-grow-1">
                <h4>
                    <a href="" class="book-now-service" data-id="${id}" data-bs-toggle="modal" data-bs-target="#modal-book-service">${name}</a>
                </h4>
                ${description}
                <div class="text-center" style="margin-top: auto !important;">
                    <input type="button" data-bs-toggle="modal" data-id="${id}" data-bs-target="#modal-book-service" class="btn btn-primary btn-sm mt-3 book-now-service" value="Book Now">
                </div>
            </div>
        </div>
    </div>`;
    }

    /**
     * Retrieve upcoming event on the database
     */
    function retrieveUpcomingEvents() {
        $.ajax({
            url: GET_EVENT_URL + GET_UPCOMING_EVENT,
            type: GET,
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                upcoming_event = dataResult.eventList;
                var eventList = "";

                dataResult.eventList.forEach(event => {
                    var description = event['description'].substring(0, 100);
                    eventList += populateUpcomingEvents(event['image'], event['id'], event['name'], description, event['date'], event['time']);
                });

                $("#portfolio-container").html(eventList);
                $("#portfolio-container").attr("style", "height: auto !important;");

                onClickUpcomingEvent();
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
        <div class="col-lg-6 col-md-6 mt-4 portfolio-item filter-3 wow fadeInUp" data-wow-delay="0.2s">
            <div class="portfolio-wrap">
                <figure class="container-image">
                    <img loading="lazy" src="${image}" class="img-fluid" alt="">
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
                                                    <img loading="lazy" src="${event['image']}" alt="">
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

    function populateGalleryIndicator(imageNumber) {
        return `<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="${imageNumber}" class="active" aria-current="true" aria-label="Slide 1"></button>`;
    }

    function populateGalleryImageAndCaption(image, name, description) {
        return `<div class="carousel-item active">
                    <img loading="lazy" src="${image}" class="d-block img-fluid" alt="..." loading="lazy">
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
                                                        <div class="swiper-slide container-image">
                                                            <img loading="lazy" src="" class="card-img-top img-fluid" alt="">
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

                            $("#modal-book-room-images").attr("src", room['image']);
                            $("#modal-book-room-name").html(room['name']);
                            $("#modal-book-room-id").html(room['id']);
                            $("#modal-book-room-price").html(formatMoney(room['price']));
                            $("#modal-book-room-pax").html(room['pax']);
                            $("#modal-book-room-max-pax").html(room['maxPax']);
                            $("#modal-book-room-description").html(room['description']);
                            $("#modal-book-room-amenities").html(room['amenities']);

                            var dynamicData = room['image'].map(function(fileName) {
                                return WEB_ROOM_IMAGE_URL + fileName;
                            });

                            console.log("Dynamic Data");
                            console.log(dynamicData);

                            // Function to dynamically load content into the Carousel
                            function loadCarouselContent() {
                                var carouselInner = $('#dynamicCarousel .carousel-inner');
                                carouselInner.empty(); // Clear existing content

                                dynamicData.forEach(function(image, index) {
                                    var activeClass = index === 0 ? 'active' : ''; // Add 'active' class to the first item
                                    var slide = $('<div class="carousel-item ' + activeClass + '"><img src="' + image + '" alt="Room Image" class="d-block w-100"></div>');
                                    carouselInner.append(slide);
                                });
                            }
                            // Call the function to load initial content
                            loadCarouselContent();
                        }
                    });
                });
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


    function retrieveAppointmentReservationDetail(duration) {
        $.ajax({
            url: GET_SERVICE_URL + GET_SERVICE_RESERVATION_LIST,
            type: GET,
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                service_reservation_list = dataResult;
                var datesForDisable = [];
                if (dataResult.statusCode == 200) {
                    dataResult.reservationList.forEach(reservation => {
                        datesForDisable.push(reservation['date']);
                    });
                }
                $('#modal-book-service-date').datepicker(DESTROY);
                $('#modal-book-service-date').datepicker(populateServiceDatepickerConfiguration());

                $('#modal-book-service-date').on('changeDate', function() {
                    var date = $(this).val();
                    if (dataResult.statusCode == 200) {
                        var reservations = [];
                        dataResult.reservationList.forEach(reservation => {
                            if (reservation['date'] == date) {
                                reservations.push({
                                    time: reservation['time'],
                                    duration: reservation['duration'],
                                });
                            }
                        });

                        console.log(reservations);
                        $("#modal-book-service-time").html(populateServiceTime(reservations, duration));
                    }
                });
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

    function populateServiceTime(reservations, duration) {
        var timeList = [];

        // disabledTime = time + duration + break_time
        for (const reservation of reservations) {
            var occupiedDuration = parseInt(reservation.duration) + parseInt(BREAK_TIME);
            var originalTime = new Date('1970-01-01T' + reservation.time + '+08:00');
            originalTime.setHours(originalTime.getHours() + occupiedDuration);
            var startTime = new Date('1970-01-01T' + reservation.time + '+08:00');
            var endTime = new Date('1970-01-01T' + originalTime.toTimeString().slice(0, 8) + '+08:00');
            var currentTime = new Date(startTime);
            while (currentTime <= endTime) {
                var formattedTime = currentTime.toTimeString().slice(0, 8);
                timeList.push(formattedTime);
                currentTime.setMinutes(currentTime.getMinutes() + 30);
            }
        }

        console.log(timeList);
        var dropdownTime = "";
        dropdownTime += `<option value="">- Please select -</option>`;
        var operationStartTime = new Date('1970-01-01T08:00:00+08:00');
        var operationEndTime = new Date('1970-01-01T17:00:00+08:00');
        var currentTime = new Date(operationStartTime);
        while (currentTime <= operationEndTime) {
            var formattedTime = currentTime.toTimeString().slice(0, 8);
            if (timeList.includes(formattedTime)) {
                dropdownTime += `<option value="${formattedTime}" style="color: red" disabled>${formatTime(formattedTime)} - Occupied</option>`;
            } else {
                if (isTimeOccupied(timeList, formattedTime, duration)) {
                    dropdownTime += `<option value="${formattedTime}">${formatTime(formattedTime)}</option>`;
                } else {
                    dropdownTime += `<option value="${formattedTime}" style="color: red" disabled>${formatTime(formattedTime)} - Occupied</option>`;
                }
            }
            currentTime.setMinutes(currentTime.getMinutes() + 30);
        }
        return dropdownTime;
    }

    function isTimeOccupied(timeList, time, duration) {
        var originalTime = new Date('1970-01-01T' + time + '+08:00');
        originalTime.setHours(originalTime.getHours() + (parseInt(duration) + parseInt(BREAK_TIME)));
        var startTime = new Date('1970-01-01T' + time + '+08:00');
        var endTime = new Date('1970-01-01T' + originalTime.toTimeString().slice(0, 8) + '+08:00');
        var currentTime = new Date(startTime);
        var isTimeAvailable = true;
        while (currentTime <= endTime) {
            var formattedTime = currentTime.toTimeString().slice(0, 8);
            if (timeList.includes(formattedTime)) {
                isTimeAvailable = false;
            }
            currentTime.setMinutes(currentTime.getMinutes() + 30);
        }
        return isTimeAvailable;
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
                console.log("Hashmmap");
                console.log(hashmap);
                console.log("Occupieds");
                console.log(occupiedDate);

                buildCheckinCalendar(datesForDisable);
                onChangeCheckinCalendar(occupiedDate, datesForDisable);
                onChangeCheckoutCalendar(occupiedDate, datesForDisable);
                onChangeCheckinTime(datesForDisable);
                populatePrice();
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

            $('#modal-book-room-checkout-date').val("");
            $('#modal-book-room-checkout-time').val("");

            if (!keyExists(occupiedDate, checkinDate)) {
                $("#modal-book-room-checkin-time").html(populateTime(checkoutTime, nextDayCheckinTime));
            } else {
                var unOccupiedRoom = getUnoccupiedRoom(occupiedDate[checkinDate]);
                populateAvailableTime(unOccupiedRoom, checkinDate);
            }
            populateAvailableRoom();
            buildCheckoutCalendar(datesForDisable, plusDay($('#modal-book-room-checkin-date').val(), 1), getNextReserveDate(checkinDate, datesForDisable));
        });
    }

    // Event handler for onChange event of check in date
    function onChangeCheckoutCalendar(occupiedDate, datesForDisable) {
        $('#modal-book-room-checkout-date').on('changeDate', function() {
            populateAvailableRoom();
        });
    }

    function populateAvailableRoom() {
        var checkoutDate = $('#modal-book-room-checkout-date').val();
        var checkinDate = $("#modal-book-room-checkin-date").val();

        if (checkinDate == "" || checkoutDate == "") {
            return;
        }

        var roomList = getAvailableRoomID();

        $("#modal-book-room-available-detail").removeClass("d-none");
        $("#modal-room-available-room-count").html(roomList.length);
        var availableRoomDropdown = "";
        for (x = 1; x <= roomList.length; x++) {
            availableRoomDropdown += `<option value="${x}">${x}</option>`;
        }
        $("#modal-room-available-room-dropdown").html(availableRoomDropdown);
    }


    function getAvailableRoomID() {
        var checkoutDate = $('#modal-book-room-checkout-date').val();
        var checkinDate = $("#modal-book-room-checkin-date").val();

        availableRoom = [];
        room_reservation_list.roomList.forEach(room => {
            availableRoom.push(room.roomID);
        });


        room_reservation_list.roomList.forEach(room => {
            room.reservationList.forEach(reservation => {
                console.log("roomID " + room.roomID);

                // Check if reserve date is available
                if (isDateRangeWithinDateRange(checkinDate, checkoutDate, reservation.checkinDate, reservation.checkoutDate)) {
                    availableRoom = valueRemoveInArray(availableRoom, room.roomID);
                    return false;
                }
            });
        });
        return availableRoom;
    }

    // Build bootstrap calendar for checkout calendar
    function buildCheckoutCalendar(datesForDisable, startDate, endDate) {
        $('#modal-book-room-checkout-date').datepicker("destroy");
        $('#modal-book-room-checkout-date').datepicker({
            format: 'mm-dd-yyyy',
            autoclose: true,
            todayHighlight: true,
            datesDisabled: datesForDisable,
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

    $('#form-booking-service').on("submit", function(event) {
        event.preventDefault();
        $("#modal-book-service-detail").addClass("d-none");
        if ($(".menu-user").hasClass("d-none")) {
            $(".form-login").html("");
            login_source = SERVICE;
            $("#modal-book-service-login").load(WEB_LOGIN_DIRECTORY);
            $("#modal-book-service-create-account").load("web/main/user/form/form-create-account.html");
            $("#modal-book-service-login").removeClass("d-none");
        } else {
            $("#modal-book-service-checkout").load("web/main/user/form/form-service-checkout.html", function() {
                populateServiceCheckoutUserInformation(logged_in_user);
            });
            $("#modal-book-service-checkout").removeClass("d-none");
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
        $(".room-max-pax").html($("#modal-book-room-max-pax").html());
    };

    // End of checkout section

    function populateTime(checkoutTime, nextDayCheckinTime) {
        console.log("checkoutTIme " + checkoutTime);
        var dropdownTime = "";
        var isDisabled = checkoutTime != "" ? "disabled" : "";
        var unavailable = checkoutTime != "" ? " (occupied)" : "";

        dropdownTime += `<option value="">- Please select -</option>`;
        for (x = 8; x <= 16; x++) {
            var hour = x < 10 ? "0".concat(x) : x;

            if (hour + ":00:00" == checkoutTime) {
                isDisabled = "";
                unavailable = "";
            }

            if (hour + ":00:00" == nextDayCheckinTime) {
                isDisabled = "disabled";
                unavailable = " (occupied)";
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
                roomList += `<div id="room-${room['id']}" class="p-1 card-room col-lg-12 col-md-12 portfolio-item filter-3 wow fadeInUp mb-4" data-wow-delay="0.2s">
                                    <div class="portfolio-wrap">
                                        <div class="row">
                                            <div class="col-lg-5">
                                                <figure class="container-image">
                                                    <img loading="lazy" src="${WEB_ROOM_IMAGE_URL + room['image'][0]}" class="img-fluid" alt="">
                                                </figure>    
                                            </div>

                                            <div class="col-lg-7 pr-4 pl-0 mx-0 py-lg-5 py-3" style="min-height: 325px;">
                                                <div class="portfolio-info">
                                                    <h4>
                                                        <a href="" data-bs-toggle="modal" data-bs-target="#modal-event">${room['name']}</a>
                                                    </h4>
                                                    
                                                    <h5>${formatMoney(room['price'])}</h5>
                                                    ${room['description']}                                                
                                                </div>
                                                <button class="btn btn-primary align-bottom book-now-room mb-4" data-bs-toggle="modal" data-bs-target="#modal-book-room" data-id="${room['id']}">Book Now</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>`;
                formatRight = false;
            } else {
                roomList += `<div id="room-${room['id']}" class="p-1 card-room col-lg-12 col-md-12 portfolio-item filter-3 wow fadeInUp mb-4" data-wow-delay="0.2s">
                                    <div class="portfolio-wrap">
                                        <div class="row">

                                            <div class="col-lg-7 order-lg-1 order-2 pr-4 pl-0 mx-0 py-lg-5 py-3" style="min-height: 325px;">
                                                <div class="portfolio-info">
                                                    <h4>
                                                        <a href="" data-bs-toggle="modal" data-bs-target="#modal-event">${room['name']}</a>
                                                    </h4>
                                                    <h5>${formatMoney(room['price'])}</h5>
                                                    ${room['description']}                                                        
                                                </div>
                                                <button class="mb-4 btn btn-primary align-bottom book-now-room" data-bs-toggle="modal" data-bs-target="#modal-book-room" data-id="${room['id']}">Book Now</button>
                                            </div>

                                            <div class="col-lg-5 order-lg-2 order-1">
                                                <figure class="container-image">
                                                    <img loading="lazy" src="${WEB_ROOM_IMAGE_URL + room['image'][0]}" class="img-fluid" alt="">
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
            url: "foundation/main/business/operation/Login.php",
            type: "POST",
            data: {
                action: 'retrieve-user-information'
            },
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200) {
                    updateUserMenu(true);
                    logged_in_user = dataResult.accountDetail;
                } else {
                    updateUserMenu(false);
                    logged_in_user = "";
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
                var reservationList = "";
                if (dataResult.statusCode != 404) {
                    room_reservation_list = dataResult.reservationList;
                    dataResult.reservationList.forEach(reservation => {
                        var status = populateReservationStatus(reservation['status'], reservation['id'], reservation['image']);
                        var buttonList = `<button class='btn-select-view btn btn-primary btn-view-room py-0' data-id="${reservation['id']}" data-type="2">View</button> | `;
                        if (reservation['status'] == 2) {
                            buttonList += `<button class='btn-select btn btn-xs btn-success btn-pay-room py-0' data-id="${reservation['id']}" data-type="1">Pay</button>`;
                        } else {
                            buttonList += `<button class='btn-select btn btn-xs btn-success py-0' disabled>Pay</button>`;
                        }
                        reservationList += `<tr>
                            <td>${reservation['id']}</td>
                            <td>${reservation['name']}</td>
                            <td>${reservation['checkinDate']}  ${formatTime(reservation['checkinTime'])}</td>
                            <td>${ formatMoney(reservation['price'])}</td>
                            <td class="text-center">${status}</td>
                            <td>${buttonList}</td>
                            </tr>`;
                    });
                }
                retrieveUserServiceReservation(reservationList);
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

    function retrieveUserServiceReservation(reservationList) {
        var id = logged_in_user['id'];
        $.ajax({
            url: GET_SERVICE_URL + GET_USER_SERVICE_RESERVATION + populateQueryParameter("accountID", id),
            type: GET,
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode != 404) {
                    service_reservation_list = dataResult.reservationList;
                    dataResult.reservationList.forEach(reservation => {
                        var status = populateReservationStatus(reservation['status'], reservation['reservationID'], reservation['image']);
                        var buttonList = `<button class='btn-select-view btn btn-primary btn-view-service py-0' data-id="${reservation['reservationID']}" data-type="2">View</button> | `;
                        if (reservation['status'] == 2) {
                            buttonList += `<button class='btn-select btn btn-xs btn-success btn-pay-service py-0' data-id="${reservation['reservationID']}" data-type="2">Pay</button>`;
                        } else {
                            buttonList += `<button class='btn-select btn btn-xs btn-success py-0' disabled>Pay</button>`;
                        }

                        var packageName = "";
                        if (reservation.packageInformation.packageName != null) {
                            packageName = " - " + reservation.packageInformation.packageName;
                        }
                        reservationList += `<tr>
                            <td>${reservation['reservationID']}</td>
                            <td>${reservation['name']} ${packageName}</td>
                            <td>${reservation['date']}  ${formatTime(reservation['time'])}</td>
                            <td>${formatMoney(reservation['price'])}</td>
                            <td class="text-center">${status}</td>
                            <td>${buttonList}</td>`;
                    });
                }
                $("#user-reservation-table-body").html(reservationList);

                $("#reservation-table").removeClass("d-none");
                $("#reservation-detail").addClass("d-none");
                $("#view-reservation-detail").addClass("d-none");

                if (!$.fn.DataTable.isDataTable('#dataTable')) {
                    $('#dataTable').DataTable({
                        order: [
                            [2, 'desc']
                        ]
                    });
                }

                $('.btn-pay-service').on("click", function() {
                    $("#reservation-table").addClass("d-none");
                    $("#reservation-detail").removeClass("d-none");
                    var id = $(this).data("id");
                    service_reservation_list.forEach(reservation => {
                        console.log("===============Start");
                        console.log(reservation);
                        console.log(reservation['reservationID'] + " " + id);
                        console.log("===============End");
                        if (reservation['reservationID'] == id) {
                            $("#reservation-reservationID").val(reservation['reservationID']);
                            $("#reservation-reservationType").val($(this).data("type"));
                            $("#reservation-reservationFor").val(reservation['name']);
                            $("#reservation-checkinDate").val(reservation['date'] + " " + formatTime(reservation['time']));
                            $("#reservation-checkout-container").addClass("d-none");
                            $("#reservation-totalAmount").val(reservation['price']);
                            $("#receiptFile").val("");
                        }
                    });
                });

                var receiptFile;

                $('#receiptFile').change(function() {
                    $("#reservation-receipt").attr("src", window.URL.createObjectURL(this.files[0]));
                    receiptFile = this.files[0];
                });

                $('#form-pay-reservation').on("submit", function(event) {
                    event.preventDefault();
                    var reservationID = $("#reservation-reservationID").val();
                    var type = $("#reservation-reservationType").val();
                    Swal.fire({
                        title: 'Please wait...',
                        text: 'We are processing your request.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        onBeforeOpen: () => {
                            Swal.showLoading();
                        },
                    });
                    if (type == "1") {
                        var formData = new FormData();
                        formData.append('file', receiptFile);
                        formData.append('action', 'upload-room-payment');
                        formData.append('id', reservationID);
                        $.ajax({
                            url: ROOM_URL,
                            type: "POST",
                            data: formData,
                            dataType: 'json',
                            contentType: false,
                            processData: false,
                            cache: false,
                            success: function(dataResult) {
                                if (dataResult.statusCode.includes(200)) {
                                    sendEmail();
                                } else if (dataResult.statusCode == 5001) {
                                    alert("You're trying to upload an invalid File Type!");
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
                    } else if (type == "2") {
                        var formData = new FormData();
                        formData.append('file', receiptFile);
                        formData.append('action', 'upload-service-payment');
                        formData.append('id', reservationID)
                        formData.append('status', "2");
                        $.ajax({
                            url: SERVICE_URL,
                            type: "POST",
                            data: formData,
                            dataType: 'json',
                            contentType: false,
                            processData: false,
                            cache: false,
                            success: function(dataResult) {
                                if (dataResult.statusCode.includes(200)) {
                                    sendEmail();
                                } else if (dataResult.statusCode == 5001) {
                                    alert("You're trying to upload an invalid File Type!");
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
                });

                function sendEmail() {
                    var title = "[PBRC] Payment Receipt Received - Under Review";
                    var requestor = logged_in_user.firstName;
                    var email = logged_in_user.email;
                    var phone = $(".pbrc-phone").html();
                    var name = $("#reservation-reservationFor").val();
                    var dateTime = $("#reservation-checkinDate").val();
                    var date = dateTime.substring(0, 10);
                    var time = dateTime.substring(11, 18);
                    $.ajax({
                        url: MAILER_URL,
                        type: POST,
                        data: {
                            action: 'send-email',
                            email: email,
                            subject: title,
                            body: populateReviewStatusEmailBody(requestor, name, phone, date, time)
                        },
                        cache: false,
                        success: function(dataResult) {
                            // location.reload();
                        },
                        beforeSend: function() {
                            $("#modal-reservation").modal("hide");
                            Swal.close();
                            console.log('Request is about to be sent.');
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Payment receipt has been uploaded!',
                                confirmButtonText: 'OK'
                            });
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

                $('.btn-pay-room').on("click", function() {
                    $("#reservation-checkout-container").removeClass("d-none");
                    $("#reservation-table").addClass("d-none");
                    $("#reservation-detail").removeClass("d-none");
                    var id = $(this).data("id");
                    console.log(dataResult.reservationList);
                    console.log(room_reservation_list);
                    room_reservation_list.forEach(reservation => {
                        console.log(reservation);
                        console.log(reservation['id'] + " " + id);
                        if (reservation['id'] == id) {
                            $("#reservation-reservationID").val(reservation['id']);
                            $("#reservation-reservationFor").val(reservation['name']);
                            $("#reservation-checkinDate").val(reservation['checkinDate'] + " " + formatTime(reservation['checkinTime']));
                            $("#reservation-checkoutDate").val(reservation['checkoutDate'] + " " + formatTime(reservation['checkoutTime']));
                            $("#reservation-totalAmount").val(reservation['price']);
                            $("#reservation-reservationType").val($(this).data("type"));
                            $("#receiptFile").val("");
                            $("#reservation-receipt").removeAttr("src");
                        }
                    });
                });

                $('.btn-view-room').on("click", function() {
                    $("#reservation-table").addClass("d-none");
                    $("#reservation-detail").addClass("d-none");
                    $("#view-reservation-detail").removeClass("d-none");
                    var id = $(this).data("id");
                    console.log(dataResult.reservationList);
                    console.log(room_reservation_list);
                    room_reservation_list.forEach(reservation => {
                        console.log(reservation);
                        console.log(reservation['id'] + " " + id);
                        if (reservation['id'] == id) {
                            $("#view-room-detail-type").val("Room");
                            $("#reservation-reservationID").val(reservation['id']);
                            $("#view-room-detail-name").val(reservation['name']);
                            $("#view-room-detail-checkinDate").val(reservation['checkinDate']);
                            $("#view-room-detail-checkinTime").val(formatTime(reservation['checkinTime']));
                            $("#view-room-detail-checkoutDate").val(reservation['checkoutDate']);
                            $("#view-room-detail-checkoutTime").val(formatTime(reservation['checkoutTime']));
                            $("#view-room-detail-status").val(getReservationStatus(reservation['status']));
                            $("#view-room-detail-price").val(formatMoney(reservation['price']));
                            if (reservation['image'] != null && reservation['image'] != "") {
                                $("#view-room-receipt").attr("src", reservation['image']);
                                $("#container-view-room-receipt").removeClass("d-none");
                            } else {
                                $("#container-view-room-receipt").addClass("d-none");
                            }
                        }
                    });
                });

                $('.btn-view-service').on("click", function() {
                    $("#reservation-table").addClass("d-none");
                    $("#reservation-detail").addClass("d-none");
                    $("#view-reservation-detail").removeClass("d-none");
                    var id = $(this).data("id");
                    console.log(service_reservation_list);
                    service_reservation_list.forEach(reservation => {
                        console.log("service-----------------------");
                        console.log(reservation['reservationID'] + " " + id);
                        if (reservation['reservationID'] == id) {
                            $("#view-room-detail-type").val("Service");
                            $("#view-room-detail-name").val(reservation['name']);
                            $("#view-room-detail-checkinDate").val(reservation['date']);
                            $("#view-room-detail-checkinTime").val(formatTime(reservation['time']));
                            $("#view-room-detail-checkoutDate").val(reservation['date']);
                            $("#view-room-detail-checkoutTime").val(formatTime(addHours(reservation.date, reservation.time, reservation.duration)));
                            $("#view-room-detail-status").val(getReservationStatus(reservation['status']));
                            $("#view-room-detail-price").val(formatMoney(reservation['price']));
                            if (reservation['image'] != null && reservation['image'] != "") {
                                $("#view-room-receipt").attr("src", reservation['image']);
                                $("#container-view-room-receipt").removeClass("d-none");
                            } else {
                                $("#container-view-room-receipt").addClass("d-none");
                            }
                        }
                    });
                });
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

    $('#modal-reservation').on('hidden.bs.modal', function(e) {
        // Remove the modal backdrop when the modal is hidden
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        $('body').css('overflow', 'auto');
    });

    $(".button-back-reservation-table").on("click", function() {
        $("#reservation-table").removeClass("d-none");
        $("#reservation-detail").addClass("d-none");
        $("#view-reservation-detail").addClass("d-none");
    });

    $("#form-join-now").on("submit", function(event) {
        event.preventDefault();
        var name = $("#input-join-now-name").val();
        var address = $("#input-join-now-address").val();
        var message = $("#input-join-now-message").val();
        var email = $("#input-join-now-email").val();
        var phone = $("#input-join-now-phone").val();
        var birthDate = $("#input-join-birth-date").val();

        $.ajax({
            url: "foundation/main/business/operation/JoinNow.php",
            type: "POST",
            data: {
                action: 'join-now',
                name: name,
                address: address,
                email: email,
                phone: phone,
                birthDate: birthDate,
                message: message
            },
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200) {
                    alert("Your application has been submitted");
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
    });


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

    $("#form-change-password").on("submit", function(event) {
        event.preventDefault();
        $("#info-message-change-password").addClass("d-none");
        $('#new-password').removeClass('is-invalid');
        $('#confirm-password').removeClass('is-invalid');
        var currentPassword = $("#current-password").val();
        var newPassword = $("#new-password").val();
        var confirmPassword = $("#confirm-password").val();
        var id = logged_in_user['id'];

        if (newPassword != confirmPassword) {
            $("#info-message-change-password").removeClass("d-none");
            $("#info-message-change-password").html("Password doesn't match!");
            $('#new-password').addClass('is-invalid');
            $('#confirm-password').addClass('is-invalid');
            return;
        }

        if (!checkChangePasswordStrength()) {
            return;
        }

        $.ajax({
            url: "foundation/main/business/operation/Login.php",
            type: "POST",
            data: {
                action: CHANGE_PASSWORD,
                accountID: id,
                currentPassword: currentPassword,
                newPassword: newPassword
            },
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Your password has been successfully changed!',
                        confirmButtonText: 'OK'
                    });
                    $('#modal-profile').modal("hide");
                    location.reload();
                } else if (dataResult.statusCode == 5000) {
                    $("#info-message-change-password").removeClass("d-none");
                    $("#info-message-change-password").html("Incorrect current password!");
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
    });
    $("#confirm-password").on('blur', function() {
        var newPassword = $("#new-password").val();
        var confirmPassword = $("#confirm-password").val();
        if (newPassword != confirmPassword) {
            $('#confirm-password').addClass('is-invalid');
        } else {
            $('#confirm-password').removeClass('is-invalid');
        }
    });

    $("#btn-menu-profile").on("click", function() {
        $("#input-profile-first-name").val(logged_in_user.firstName);
        $("#input-profile-middle-name").val(logged_in_user.middleName);
        $("#input-profile-last-name").val(logged_in_user.lastName);
        $("#input-profile-email").val(logged_in_user.email);
        $("#input-profile-phone").val(logged_in_user.phone);
        getProfileAddressReference();
    });

    function getProfileAddressReference() {
        $.ajax({
            url: GET_REFERENCE_URL + GET_ADDRESS_REFERENCE,
            type: GET,
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                var provinceList = `<option value=""></option>`;
                var municipalityList = `<option value=""></option>`;
                var barangayList = `<option value=""></option>`;
                if (dataResult.statusCode == 200) {
                    address_reference = dataResult;
                    var provinceCode = "";
                    var municipalityCode = "";

                    dataResult.provinceList.forEach(province => {
                        console.log(logged_in_user.province + " - " + province.name);
                        if (logged_in_user.province.toLowerCase() == province.name.toLowerCase()) {
                            provinceList += `<option value="${province.value}" selected>${province.name}</option>`;
                            provinceCode = province.value;
                        } else {
                            provinceList += `<option value="${province.value}">${province.name}</option>`;
                        }
                    });
                    $("#input-profile-province").html(provinceList);

                    dataResult.municipalityList.forEach(municipality => {
                        if (provinceCode == municipality['subReference']) {
                            if (logged_in_user.municipality.toLowerCase() == municipality.name.toLowerCase()) {
                                municipalityList += `<option value="${municipality.value}" selected>${municipality.name}</option>`;
                                municipalityCode = municipality.value;
                            } else {
                                municipalityList += `<option value="${municipality.value}">${municipality.name}</option>`;
                            }
                        }
                    });
                    $("#input-profile-municipality").html(municipalityList);

                    dataResult.barangayList.forEach(barangay => {
                        if (municipalityCode == barangay.subReference) {
                            if (logged_in_user.barangay.toLowerCase() == barangay.name.toLowerCase()) {
                                barangayList += `<option value="${barangay.value}" selected>${barangay.name}</option>`;
                            } else {
                                barangayList += `<option value="${barangay.value}">${barangay.name}</option>`;
                            }
                        }
                    });
                    $("#input-profile-barangay").html(barangayList);
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

    $("#input-profile-email").on("blur", function() {
        if (logged_in_user.email == $(this).val()) {
            return;
        }
        $.ajax({
            url: GET_LOGIN_URL + GET_EMAIL_CHECK + populateQueryParameter("email", $(this).val()),
            type: GET,
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200) {
                    $("#input-profile-email").removeClass("is-invalid");
                } else {
                    $("#input-profile-email").addClass("is-invalid");
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
    });

    $("#input-profile-province").on("change", function() {
        municipalityList = `<option value=""></option>`;
        address_reference.municipalityList.forEach(municipality => {
            if ($(this).val() == municipality.subReference) {
                if (logged_in_user.municipality == municipality.name) {
                    municipalityList += `<option value="${municipality.value}" selected>${municipality.name}</option>`;
                } else {
                    municipalityList += `<option value="${municipality.value}">${municipality.name}</option>`;
                }
            }
        });
        $("#input-profile-municipality").html(municipalityList);
    });

    $("#input-profile-municipality").on("change", function() {
        barangayList = `<option value=""></option>`;
        address_reference.barangayList.forEach(barangay => {
            if ($(this).val() == barangay.subReference) {
                if (logged_in_user.barangay == barangay.name) {
                    barangayList += `<option value="${barangay.value}" selected>${barangay.name}</option>`;
                } else {
                    barangayList += `<option value="${barangay.value}">${barangay.name}</option>`;
                }
            }
        });
        $("#input-profile-barangay").html(barangayList);
    });

    $("#form-update-profile").on("submit", function(event) {

        var firstName = $("#input-profile-first-name").val();
        var middleName = $("#input-profile-middle-name").val();
        var lastName = $("#input-profile-last-name").val();
        var email = $("#input-profile-email").val();
        var phone = $("#input-profile-phone").val();
        var province = $("#input-profile-province option:selected").text();
        var municipality = $("#input-profile-municipality option:selected").text();
        var barangay = $("#input-profile-barangay option:selected").text();
        event.preventDefault();
        $.ajax({
            url: "foundation/main/business/operation/Login.php",
            type: "POST",
            data: {
                action: 'update-user-account',
                accountID: logged_in_user.id,
                firstName: firstName,
                middleName: middleName,
                lastName: lastName,
                email: email,
                phone: phone,
                province: province,
                municipality: municipality,
                barangay: barangay
            },
            cache: false,
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200) {
                    // updateUserMenu(false);
                    // logged_in_user = "";
                    retrieveUserInformation();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Profile has been successfully updated!',
                        confirmButtonText: 'OK'
                    });
                    $("#modal-profile").modal("hide");
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
    });

    $('#modal-profile').on('hidden.bs.modal', function(e) {
        // Remove the modal backdrop when the modal is hidden
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        $('body').css('overflow', 'auto');
    });



});