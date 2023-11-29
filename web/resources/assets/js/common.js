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

// Calendar it Room and Service Reservation -> configuration
function populateServiceDatepickerConfiguration() {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    return {
        format: MM_DD_YYYY_FORMAT,
        autoclose: true,
        todayHighlight: true,
        minDate: 0,
        format: YYYY_MM_DD_FORMAT,
        startDate: tomorrow
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

function formatMoneyRaw(amount) {
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
    let formattedAmount = `${wholePart}${decimalPart}`;

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



function getReservationDropdownValue(statusCode) {
    var dropdown = "";
    dropdown += populateDropDownValue("1", getReservationStatus(1), statusCode == 1);
    dropdown += populateDropDownValue("2", getReservationStatus(2), statusCode == 2);
    dropdown += populateDropDownValue("3", getReservationStatus(3), statusCode == 3);
    dropdown += populateDropDownValue("4", getReservationStatus(4), statusCode == 4);
    dropdown += populateDropDownValue("5", getReservationStatus(5), statusCode == 5);
    dropdown += populateDropDownValue("6", getReservationStatus(6), statusCode == 6);
    dropdown += populateDropDownValue("7", getReservationStatus(7), statusCode == 7);
    dropdown += populateDropDownValue("8", getReservationStatus(8), statusCode == 8);
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
        return "Requested";
    } else if (statusCode == 2) {
        return "Approved";
    } else if (statusCode == 3) {
        return "Rejected";
    } else if (statusCode == 4) {
        return "Pay Review";
    } else if (statusCode == 5) {
        return "Addl. Info";
    } else if (statusCode == 6) {
        return "Paid";
    } else if (statusCode == 7) {
        return "Cancelled";
    } else if (statusCode == 8) {
        return "Completed";
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
            reservationStatus = populateStatusBadge(getReservationStatus(status), "The reservation has been requested and is currently under review by our team.", "bg-primary", "#007bff");
            break;
        case "2":
            reservationStatus = populateStatusBadge(getReservationStatus(status), "Your reservation has been approved! You can now proceed with the payment.", "bg-success", "#198754");
            break;
        case "3":
            reservationStatus = populateStatusBadge(getReservationStatus(status), "Unfortunately, your reservation request has been rejected. Please check for rejection reasons.", "bg-danger", "#dc3545");
            break;
        case "4":
            reservationStatus = populateStatusBadge(getReservationStatus(status), "You've uploaded the payment receipt, and our team is currently reviewing it.", "bg-info", "#0dcaf0");
            break;
        case "5":
            reservationStatus = populateStatusBadge(getReservationStatus(status), "You've uploaded the payment receipt, and our team is currently reviewing it.", "bg-warning", "#ffc107");
            break;
        case "6":
            reservationStatus = populateStatusBadge(getReservationStatus(status), "Your payment has been reviewed, and your reservation is now confirmed.", "bg-success", "#198754");
            break;
        case "7":
            reservationStatus = populateStatusBadge(getReservationStatus(status), "The reservation has been cancelled as per your request.", "bg-secondary", "#rgb(108,117,125)");
            break;
        case "8":
            reservationStatus = populateStatusBadge(getReservationStatus(status), "Your reservation is successfully completed. Thank you for choosing us!", "bg-secondary", "#rgb(108,117,125)");
            break;
    }
    return reservationStatus;
}

function populateStatusBadge(text, message, badgeColor, tooltipColor) {
    return `<span data-bs-toggle="tooltip" data-bs-placement="top" title="${message}" class="tooltip-message">
                <span class="badge ${badgeColor} badge-with-icon">
                    ${text}
                    <span class="tooltip-icon bx bx-question-mark" style="color: ${tooltipColor} !important;"></span>
                </span>
            </span>`;
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

function populatePendingStatusEmailBody(name, service, phone, reservationDate, reservationTime) {
    // title:Reservation Request Received
    // <p>Additional details for your reservation:</p>
    //         <ul>
    //             <li><strong>Food:</strong> ${food}</li>
    //             <li><strong>Item:</strong> ${item}</li>
    //             <li><strong>Package:</strong> ${package}</li>
    //         </ul>
    return `<html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                padding: 20px;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            h1 {
                color: #4CAF50;
            }
            p {
                line-height: 1.6;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Reservation Request Received</h1>
            <p>Dear ${name},</p>
            <p>We hope this email finds you well. We wanted to inform you that we have received your reservation request at PBRC - Poggio Bustone Renewal Center. Our dedicated team is currently reviewing the details.</p>
            <p>You have requested to reserve a <strong>${service}</strong> for the following details:</p>
            <ul>
                <li><strong>Date:</strong> ${reservationDate}</li>
                <li><strong>Time:</strong> ${reservationTime}</li>
            </ul>
            
            <p>Our team is working diligently to provide you with the best service possible. Please be patient as our admin team carefully assesses your request.</p>
            <p>We will send you an update as soon as the review process is complete. In the meantime, you can check the status of your reservation on our website <strong><a href="https://pbrc.pcbics.net/">PBRC</a></strong> or reach out to us at <strong>${phone}</strong> for any immediate assistance.</p>
            <p>Thank you for choosing PBRC for your renewal needs. We appreciate your trust in us.</p>
            <p>Best Regards,<br>
            The PBRC Team</p>
        </div>
    </body>
    </html>`;
}

function populateApprovedStatusEmailBody(name, service, reservationDate, reservationTime, gcashNumber, gcashName, gcashQR) {
    // title: Reservation Approval and Payment Instructions
    return `
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                padding: 20px;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            h1 {
                color: #4CAF50;
            }
            p {
                line-height: 1.6;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Reservation Approval and Payment Instructions</h1>
            <p>Dear ${name},</p>
            <p>We are pleased to inform you that your reservation request for a <strong>${service}</strong> on ${reservationDate} at ${reservationTime} has been reviewed and approved by our team.</p>
            <p>You may now proceed with the payment using GCash. Here are the payment instructions:</p>
            <strong>Instructions for Uploading GCash Payment Receipt:</strong>
            <p><strong>Make Payment via GCash:</strong></p>
            <ul>
                <li>Open your GCash app and make the payment to our account.</li>
                <li><strong>GCash Number:</strong> ${gcashNumber} | <strong><a href="${gcashQR}" target="_blank">QR code</a></strong></li>
                <li><strong>GCash Name:</strong>${gcashName}</li>
            </ul>
            <p><strong>Receive Payment Receipt:</strong></p>
            <p>After making the payment, you\'ll receive a digital receipt from GCash.</p>
            <p><strong>Visit Our Website:</strong></p>
            <p>Go to our website <a href="https://pbrc.pcbics.net/">PBRC</a> where you need to upload the payment receipt.</p>
            <p><strong>Access Receipt Upload Section:</strong></p>
            <p>Look for the section or button on our website that allows you to upload the GCash payment receipt.</p>
            <p><strong>Upload Receipt:</strong></p>
            <p>Click to upload the receipt and follow the on-screen instructions.</p>
            <p><strong>Confirmation:</strong></p>
            <p>Wait for confirmation that your receipt has been successfully uploaded.</p>
            <p>Thank you for choosing PBRC for your renewal needs. We look forward to welcoming you!</p>
            <p>Best Regards,<br>
            The PBRC Team</p>
        </div>
    </body>
    </html>
`;
}

function populateRejectedStatusEmailBody(name, service, reservationDate, reservationTime, gcashNumber, gcashName, gcashQR) {
    // title: Reservation Approval and Payment Instructions
    return `<html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                padding: 20px;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            h1 {
                color: #e74c3c; /* Red color for rejection */
            }
            p {
                line-height: 1.6;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Reservation Rejected</h1>
            <p>Dear ${name},</p>
            <p>We regret to inform you that your reservation request at PBRC - Poggio Bustone Renewal Center has been reviewed by our admin team and unfortunately, it has been rejected for the following reason:</p>
            <p><strong>Rejection Reason:</strong> Date is not available</p>
            <p>We understand that this may be disappointing, and we apologize for any inconvenience caused. If you have any questions or would like further clarification, please feel free to reach out to us at <strong>(63) 95131 73124</strong>.</p>
            <p>We appreciate your understanding and hope to have the opportunity to serve you in the future.</p>
            <p>Thank you for considering PBRC for your renewal needs.</p>
            <p>Best Regards,<br>
            The PBRC Team</p>
        </div>
    </body>
    </html>
`;
}

function populateReviewStatusEmailBody(name, service, phone, reservationDate, reservationTime) {
    // title: Payment Receipt Received - Under Review
    return `
    <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    color: #333;
                    padding: 20px;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #fff;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                h1 {
                    color: #4CAF50;
                }
                p {
                    line-height: 1.6;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Payment Receipt Received - Under Review</h1>
                <p>Dear ${name},</p>
                <p>We hope this email finds you well. We wanted to inform you that we have received your payment receipt for ${service} reservation made on ${reservationDate} at ${reservationTime}.</p>
                <p>Our team is currently reviewing the payment details to ensure everything is in order. This process may take a short while, and we appreciate your patience.</p>
                <p>Once the review is complete, we will send you a confirmation email. In the meantime, feel free to check the status of your reservation on our website <a href="https://pbrc.pcbics.net/">PBRC</a> or reach out to us at <strong>${phone}</strong> for any immediate assistance.</p>
                <p>Thank you for choosing PBRC for your renewal needs. We value your trust in us.</p>
                <p>Best Regards,<br>
                The PBRC Team</p>
            </div>
        </body>
        </html>

`;
}

function populateConfirmedStatusEmailBody(name, service, phone, reservationDate, reservationTime) {
    // title: Reservation Confirmed
    return `
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                padding: 20px;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            h1 {
                color: #4CAF50;
            }
            p {
                line-height: 1.6;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Reservation Confirmed</h1>
            <p>Dear ${name},</p>
            <p>We are excited to share that your payment has been reviewed and your ${service} reservation for ${reservationDate} at ${reservationTime} has been confirmed!</p>
            <p>Your room is now reserved, and we look forward to providing you with an exceptional experience at PBRC - Poggio Bustone Renewal Center.</p>
            <p>Should you have any further questions or need additional assistance, feel free to reach out to us at <strong>${phone}</strong>.</p>
            <p>Thank you for choosing PBRC. We can't wait to welcome you!</p>
            <p>Best Regards,<br>
            The PBRC Team</p>
        </div>
    </body>
    </html>

`;
}



function populatePendingAdditionalInformationStatusEmailBody(name, service, phone, reservationDate, reservationTime, message, gcashNumber, gcashName, gcashQR) {
    // title: Payment Receipt Not Approved - Resubmission Required
    return `
    <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    color: #333;
                    padding: 20px;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #fff;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                h1 {
                    color: #4CAF50;
                }
                p {
                    line-height: 1.6;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Payment Receipt Not Approved - Resubmission Required</h1>
                <p>Dear ${name},</p>
                <p>We hope this email finds you well. Unfortunately, we encountered an issue with the payment receipt you submitted for your ${service} reservation on ${reservationDate} at ${reservationTime}.</p>
                <p>${message}</p>
                <strong>Instructions for Uploading GCash Payment Receipt:</strong>
                <p><strong>Make Payment via GCash:</strong></p>
                <ul>
                    <li>Open your GCash app and make the payment to our account.</li>
                    <li><strong>GCash Number:</strong> ${gcashNumber} | <strong><a href="${gcashQR}" target="_blank">QR code</a></strong></li>
                    <li><strong>GCash Name:</strong> ${gcashName}</li>
                </ul>
                <p><strong>Receive Payment Receipt:</strong></p>
                <p>After making the payment, you\'ll receive a digital receipt from GCash.</p>
                <p><strong>Visit Our Website:</strong></p>
                <p>Go to our website PBRC where you need to upload the payment receipt.</p>
                <p><strong>Access Receipt Upload Section:</strong></p>
                <p>Look for the section or button on our website that allows you to upload the GCash payment receipt.</p>
                <p><strong>Upload Receipt:</strong></p>
                <p>Click to upload the receipt and follow the on-screen instructions.</p>
                <p><strong>Confirmation:</strong></p>
                <p>Wait for confirmation that your receipt has been successfully uploaded.</p>
                <p>We appreciate your cooperation in ensuring a smooth processing of your reservation. If you have any questions, feel free to reach out to us at <strong>${phone}</strong>.</p>
                <p>Thank you for choosing PBRC for your renewal needs.</p>
                <p>Best Regards,<br>
                The PBRC Team</p>
            </div>
        </body>
        </html>

`;
}

function populateCancelledStatusEmailBody(name) {
    return `<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reservation Cancelled</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                padding: 20px;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            h1 {
                color: #d9534f; /* Bootstrap danger color */
            }
            p {
                line-height: 1.6;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Reservation Cancelled</h1>
            <p>Dear ${name},</p>
            <p>We regret to inform you that your reservation at PBRC - Poggio Bustone Renewal Center has been cancelled as per your request.</p>
            <p>If you have any questions or if there's anything we can assist you with, please feel free to contact us at 123456789 or visit our website <a href="https://pbrc.pcbics.net/">PBRC</a>.</p>
            <p>We appreciate your understanding, and we hope to serve you in the future.</p>
            <p>Best Regards,<br>
            The PBRC Team</p>
        </div>
    </body>
    </html>`;
}

function populateDoneStatusEmailBody(name) {
    // title: Thank You for Choosing PBRC
    return `
    <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    color: #333;
                    padding: 20px;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #fff;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                h1 {
                    color: #4CAF50;
                }
                p {
                    line-height: 1.6;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Thank You for Choosing PBRC</h1>
                <p>Dear ${name},</p>
                <p>On behalf of the entire team at PBRC - Poggio Bustone Renewal Center, we want to express our deepest gratitude for choosing us for your recent reservation on '.$reservationDate.' at '.$reservationTime.'.</p>
                <p>We hope your experience with us was nothing short of exceptional, and that you found the renewal you were seeking. Your presence was truly valued, and we look forward to welcoming you back in the future.</p>
                <p>If you have any feedback or suggestions for improvement, we would love to hear from you. Our commitment to providing the best possible service is unwavering, and your insights are invaluable.</p>
                <p>Thank you once again for making PBRC a part of your renewal journey. We wish you continued joy, peace, and rejuvenation.</p>
                <p>Warm Regards,<br>
                The PBRC Team</p>
            </div>
        </body>
        </html>`;
}