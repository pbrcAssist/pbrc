var SYMMETRIC_KEY = "hKGYOgnTVR8bOP0ViW7FFmX0q1x6ag6B";
var DIR_RESOURCE = "./../resource/profile/";

// class ErrorMessage {
//     static EXISTING_USERNAME = "This username was already been used!";
//     static INCORRECT_CREDENTIAL = "Incorrect Email Address or Password!";
//     static EMAIL_FAILED = "Unable to send en email, please try again later!";
//     static SCAN_FAILED = "Unable to scan the QR code, please try again later!";
//     static EXISTING_STUDENT_ID = "This student id was already been used!";
//     static EXISTING_EMAIL = "This email was already been used!";
// }

// class InvalidMessage {
//     static PASSWORD = "Invalid Password!";
//     static EMAIL_FORMAT = "Invalid email format, please us your BULSU email";
//     static EMAIL = "Invalid Email Address!";
// }

function updateUserMenu(hasLoggedInUser) {
    if (hasLoggedInUser) {
        $('.menu-user').removeClass("d-none");
        $('.menu-visitor').addClass("d-none");
    } else {
        $('.menu-user').addClass("d-none");
        $('.menu-visitor').removeClass("d-none");
    }
}

function populateTableData(data) {
    return "<td>" + data + "</td>";
}

function addCookie(key, value) {
    $.cookie(key, value);
}

function removeCookie(key, value) {
    $.removeCookie(key, value);
}

function removeCookieKey(key) {
    $.removeCookie(key);
}

function getCookie(key) {
    $.cookie(key);
}

function encrypt(value) {
    return CryptoJS.AES.encrypt(value, SYMMETRIC_KEY).toString();
}

function decrypt(value) {
    var bytes = CryptoJS.AES.decrypt(value, SYMMETRIC_KEY);
    return bytes.toString(CryptoJS.enc.Utf8);
}


function getUserProfile() {
    $.ajax({
        url: "../../common/db.php",
        type: "POST",
        data: {
            action: 'retrieve-user-profile'
        },
        cache: false,
        success: function(dataResult) {
            var dataResult = JSON.parse(dataResult);
            if (dataResult.statusCode == 200) {
                $("#profile-name").html(dataResult.studentInformation.name);
                $("#profile-user-name").html(dataResult.studentInformation.username);
                $("#profile-year-level").html(dataResult.studentInformation.yearLevel);
                $("#profile-specialization").html(dataResult.studentInformation.specialization);
                $("#profile-ranking").html(dataResult.studentInformation.ranking);
            }
        }
    });
}

function getNotificationCount() {
    $.ajax({
        url: "../../common/db.php",
        type: "POST",
        data: {
            action: 'retrieve-notification-count'
        },
        cache: false,
        success: function(dataResult) {
            var dataResult = JSON.parse(dataResult);
            if (dataResult.statusCode == 200) {
                if (dataResult.notificationCount != 0) {
                    $('#notification-count').html(dataResult.notificationCount);
                }
            }
        }
    });
}

// Covert database time to 12 hour format
function formatTime(timeString) {
    const [hourString, minute] = timeString.split(":");
    const hour = +hourString % 24;
    return (hour % 12 || 12) + ":" + minute + (hour < 12 ? " AM" : " PM");
}

// Will add day on date
function plusDay(date, addValue) {
    const newDate = new Date(date);
    newDate.setDate(newDate.getDate() + addValue);
    var a = moment(newDate);
    return a.format("YYYY-MM-DD");
}

function minusDay(date, minusValue) {
    const newDate = new Date(date);
    newDate.setDate(newDate.getDate() - minusValue);
    var a = moment(newDate);
    return a.format("YYYY-MM-DD");
}

function formatDate(date) {
    const newDate = new Date(date);
    newDate.setDate(newDate.getDate());
    var a = moment(newDate);
    return a.format("YYYY-MM-DD");
}

// Calendar it Room and Service Reservation -> configuration
function populateDatepickerConfiguration(disabledDates) {
    return {
        format: MM_DD_YYYY_FORMAT,
        autoclose: true,
        todayHighlight: true,
        datesDisabled: disabledDates,
        minDate: 0,
        format: YYYY_MM_DD_FORMAT,
        startDate: new Date()
    };
}

function populateQueryParameter(key, value) {
    return '&' + key + "=" + value;
};

// Todo: Check the most later available dates to disable
function getNextReserveDate(checkinDate, datesForDisable) {
    var earlierNextDate = "";
    // Sort the disable date before checking the end date
    datesForDisable.sort();
    var checkinDate = new Date(checkinDate);

    datesForDisable.forEach(occupiedDate => {
        var disableDate = new Date(occupiedDate);
        if (earlierNextDate == "" && disableDate > checkinDate) {
            earlierNextDate = occupiedDate;
        } else {
            var date1 = new Date(earlierNextDate);
            var date2 = new Date(occupiedDate);

            // Compare the dates
            if (date1 > date2) {
                earlierNextDate = occupiedDate;
            }
        }
    });
    return earlierNextDate;
}

// Checking if a value exists in the hashmap
function valueExists(hashmap, valueToCheck) {
    for (var key in hashmap) {
        if (hashmap.hasOwnProperty(key) && hashmap[key] === valueToCheck) {
            return true; // Value exists in the hashmap
        }
    }
    return false; // Value does not exist in the hashmap
}

// Checking if a value exists in the hashmap
function valueInKeyExists(hashmap, key, valueToCheck) {
    var isDateExist = false;
    hashmap[key].forEach(value => {
        if (value === valueToCheck) {
            isDateExist = true;
        }
    });
    return isDateExist;
}

// Checking if a value exists in the hashmap
function isDateOccupied(hashmap, occupiedDate, valueToCheck, totalRoomNumber) {

    for (var key in hashmap) {
        hashmap[key].forEach(date => {
            if (date === valueToCheck) {

                if (!keyExists(occupiedDate, valueToCheck)) {
                    occupiedDate[valueToCheck] = [key];
                } else {
                    if (!valueInKeyExists(occupiedDate, valueToCheck, key)) {
                        addValueToKey(occupiedDate, valueToCheck, key);
                    }
                }
                return;
            }
        });
    }

    if (occupiedDate[valueToCheck].length === totalRoomNumber) {
        return true;
    } else {
        return false;
    }
}

// Function to get dates between two dates
function getDates(startDate, endDate) {
    let dates = [];
    let currentDate = new Date(startDate);
    endDate = new Date(endDate);

    while (currentDate <= endDate) {
        dates.push(currentDate);
        currentDate = new Date(currentDate);
        currentDate.setDate(currentDate.getDate() + 1);
    }

    return dates;
}

function keyExists(hashmap, keyToCheck) {
    return hashmap.hasOwnProperty(keyToCheck);
}

function addValueToKey(hashmap, keyToAddTo, valueToAdd) {
    if (!hashmap[keyToAddTo]) {
        hashmap[keyToAddTo] = []; // Initialize an empty array if the key doesn't exist
    }
    hashmap[keyToAddTo].push(valueToAdd);
}

function getNumberOfValuesForKey(hashmap, keyToCheck) {
    if (hashmap[keyToCheck]) {
        return hashmap[keyToCheck].length;
    } else {
        return 0; // Return 0 if the key doesn't exist
    }
}

function addValueToMap(hashmap, key, value) {
    if (!keyExists(hashmap, key)) {
        hashmap[key] = [value];
    } else {
        if (!valueExists(hashmap, value)) {
            addValueToKey(hashmap, key, value);
        }
    }
}

// Return true if time1 is earlier
// Return false if time2 is earlier
function compareTwoTime(time1, time2) {
    // Parse the time strings using moment.js
    var moment1 = moment(time1, "h:mm");
    var moment2 = moment(time2, "h:mm");

    // Compare the moments
    if (moment1.isBefore(moment2)) {
        console.log(time1 + " is earlier than " + time2);
        return true;
    } else if (moment1.isAfter(moment2)) {
        console.log(time1 + " is later than " + time2);
        return false;
    } else {
        console.log(time1 + " is the same as " + time2);
        return;
    }
}

// Return true if text is blank
function isNotBlank(text) {
    console.log("Blank " + text);
    if (text != null && text != "" && text != "0" && text != undefined) {
        return true;
    }
    return false;
}

function getDateDifference(date1Str, date2Str) {
    // Parse the date strings into Date objects
    var date1 = new Date(date1Str);
    var date2 = new Date(date2Str);

    // Check if the input is valid
    if (isNaN(date1) || isNaN(date2)) {
        $("#result").text("Invalid date format. Please use YYYY-MM-DD.");
    } else {
        // Calculate the time difference in milliseconds
        var difference = Math.abs(date1 - date2);

        // Convert the time difference to days
        var daysDifference = Math.floor(difference / (1000 * 60 * 60 * 24));

        return daysDifference;
    }
}

function populateOrderedList(list) {
    return `<ol class="list-group list-group-numbered">${list}</o>`;
}

function populateAdditionalCheckoutRow(key, value, text, price) {
    return `<li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">${key}</div>
                    ${text}: ${value}
                </div>
                <span class="badge bg-primary rounded-pill px-3">${price} PHP</span>
            </li>`;
}

function populateAdditionalFoodCheckoutRow(key, pax, serving, price) {
    return `<li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">${key}</div>
                    Pax: ${pax}<br>
                    Serving/s: ${serving}
                </div>
                <span class="badge bg-primary rounded-pill px-3">${price} PHP</span>
            </li>`;
}

function populateAdditionalRow(key, value, text) {
    return `<li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">${key}</div>
                    ${text}: ${value}
                </div>
            </li>`;
}

function populateAdditionalFoodRow(key, pax, serving) {
    return `<li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">${key}</div>
                    Pax: ${pax}<br>
                    Serving: ${serving}
                </div>
            </li>`;
}

function isDateRangeWithinDateRange(startDateToCheck, endDateToCheck, startDate, endDate) {
    startDateToCheck = new Date(startDateToCheck);
    endDateToCheck = new Date(endDateToCheck);
    startDate = new Date(startDate);
    endDate = new Date(endDate);
    if ((startDateToCheck >= startDate && startDateToCheck <= endDate) || (endDateToCheck >= startDate && startDateToCheck <= endDate)) {
        return true;
    }
    return false;
}

function adminRequest(url) {
    return "./../../../" + url;
}

function valueExistInArray(arrayList, valueToCheck) {
    if (arrayList.indexOf(valueToCheck) !== -1) {
        return true;
    } else {
        return false;
    }
}

function valueRemoveInArray(arrayList, valueToRemove) {
    return $.grep(arrayList, function(element) {
        return element !== valueToRemove;
    });
}



function populateDropDownValue(name, value, selected) {
    if (selected) {
        return `<option selected value="${name}">${value}</option>`;
    } else {
        return `<option value="${name}">${value}</option>`;
    }
}

function summernote(id, value) {
    if (!$(id).hasClass('summernote')) {
        $(id).summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ol', 'ul', 'paragraph', 'height']],
                ['table', ['table']],
                ['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
            ]
        });
    }

    $(id).summernote('code', value);
}

function formatMoney(amount) {
    // Convert the amount to a number, if it's not already
    amount = parseFloat(amount);

    // Check if the input is a valid number
    if (isNaN(amount)) {
        return "Invalid input";
    }

    // Round the amount to two decimal places
    amount = amount.toFixed(2);

    // Convert the number to a string for further formatting
    amount = amount.toString();

    // Split the string into whole and decimal parts
    let [wholePart, decimalPart] = amount.split('.');

    // Add commas to the whole part
    wholePart = wholePart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    // Check if the decimal part is .00 and remove it
    decimalPart = (decimalPart === '00') ? '' : `.${decimalPart}`;

    // Combine the whole and decimal parts with the PHP symbol
    let formattedAmount = `${wholePart}${decimalPart} PHP`;

    return formattedAmount;
}

function formatStatus(status) {
    if (status == 0) {
        return `<div class="text-center"><span class="badge bg-danger">Inactive</span></div>`;
    } else if (status == 1) {
        return `<div class="text-center"><span class="badge bg-success">Active</span></div>`;
    }
}

function disableMinusSign(input) {
    input.value = input.value.replace(/[^-0-9]/g, '');
}


/**
 * 
 * @param {*} statusCode
 * @description 
 * statusCode = 1 -> Pending
 * statusCode = 2 -> Unpaid
 * statusCode = 3 -> Payment Confirmation
 * statusCode = 4 -> Confirmed
 * statusCode = 5 ->  Cancelled
 */
function getReservationStatus(statusCode) {
    if (statusCode == 1) {
        return "Pending";
    } else if (statusCode == 2) {
        return "Approved";
    } else if (statusCode == 3) {
        return "Payment Review";
    } else if (statusCode == 4) {
        return "Confirmed";
    } else if (statusCode == 5) {
        return "Cancelled";
    } else if (statusCode == 6) {
        return "Done";
    }
}

function getReservationDropdownValue(statusCode) {
    var dropdown = "";
    dropdown += populateDropDownValue("1", getReservationStatus(1), statusCode == 1, "warning");
    dropdown += populateDropDownValue("2", getReservationStatus(2), statusCode == 2);
    dropdown += populateDropDownValue("3", getReservationStatus(3), statusCode == 3);
    dropdown += populateDropDownValue("4", getReservationStatus(4), statusCode == 4);
    dropdown += populateDropDownValue("5", getReservationStatus(5), statusCode == 5);
    dropdown += populateDropDownValue("6", getReservationStatus(6), statusCode == 6);
    return dropdown;
}


/**
 * 
 * @param {*} statusCode
 * @description 
 * statusCode = 1 -> Pending
 * statusCode = 2 -> Unpaid
 * statusCode = 3 -> Payment Confirmation
 * statusCode = 4 -> Confirmed
 * statusCode = 5 ->  Cancelled
 */
function getReservationStatus(statusCode) {
    if (statusCode == 1) {
        return "Pending";
    } else if (statusCode == 2) {
        return "Approved";
    } else if (statusCode == 3) {
        return "Payment Review";
    } else if (statusCode == 4) {
        return "Confirmed";
    } else if (statusCode == 5) {
        return "Cancelled";
    } else if (statusCode == 6) {
        return "Done";
    }
}

function populateReservationStatus(status, receiptID, receiptLink) {
    console.log(status + " status");
    var reservationStatus = "";
    var receipt = `<a data-id="${receiptID}" class="view_receipt text-center" data-toggle="tooltip" title="View Receipt">View Receipt</a>`;
    console.log(receiptLink);
    if (receiptLink != null && receiptLink != "" && receiptLink != undefined) {
        receipt = `<a data-id="${receiptID}" href="${receiptLink}" target="_blank" class="view_receipt text-center" data-toggle="tooltip" title="View Receipt">View Receipt</a>`;
    }
    switch (status) {
        case "1":
            reservationStatus = `<span class="badge badge-warning text-white" data-toggle="tooltip" title="${getReservationStatus(status)}">${getReservationStatus(status)}</span>`;
            break;
        case "2":
            reservationStatus = `<span class="badge badge-primary text-white" data-toggle="tooltip" title="${getReservationStatus(status)}">${getReservationStatus(status)}</span>`;
            break;
        case "3":
            reservationStatus = `<span class="badge badge-info text-white" data-toggle="tooltip" title="${getReservationStatus(status)}">
            ${getReservationStatus(status)}</span>
            <br>${receipt}`;
            break;
        case "4":
            reservationStatus = `<span class="badge badge-success text-white" data-toggle="tooltip" title="${getReservationStatus(status)}">
            ${getReservationStatus(status)}</span>
            <br>${receipt}`;
            break;
        case "5":
            reservationStatus = `<span class="badge badge-danger text-white" data-toggle="tooltip" title="${getReservationStatus(status)}">${getReservationStatus(status)}</span>`;
            break;
        case "6":
            reservationStatus = `<span class="badge badge-dark text-white" data-toggle="tooltip" title="${getReservationStatus(status)}">${getReservationStatus(status)}</span>`;
            break;
    }

    return reservationStatus;
}

function populateTimeValue() {
    return `
    <option value="08:00">8:00 AM</option>
    <option value="08:30">8:30 AM</option>
    <option value="09:00">9:00 AM</option>
    <option value="09:30">9:30 AM</option>
    <option value="10:00">10:00 AM</option>
    <option value="10:30">10:30 AM</option>
    <option value="11:00">11:00 AM</option>
    <option value="11:30">11:30 AM</option>
    <option value="12:00">12:00 PM</option>
    <option value="12:30">12:30 PM</option>
    <option value="13:00">1:00 PM</option>
    <option value="13:30">1:30 PM</option>
    <option value="14:00">2:00 PM</option>
    <option value="14:30">2:30 PM</option>
    <option value="15:00">3:00 PM</option>
    <option value="15:30">3:30 PM</option>
    <option value="16:00">4:00 PM</option>
    <option value="16:30">4:30 PM</option>
    <option value="17:00">5:00 PM</option>
    <option value="17:30">5:30 PM</option>
    <option value="18:00">6:00 PM</option>
    <option value="18:30">6:30 PM</option>`;
}

function addHours(date, time, plusHours) {
    var originalTime = new Date(date + "T" + time);
    originalTime.setHours(originalTime.getHours() + parseInt(plusHours, 10));
    return originalTime.toTimeString().split(" ")[0];
}