const GET = "GET";
const POST = "POST";
const PATCH = "PATCH";
const DELETE = "DELETE";

const SYSTEM_URL = "foundation/main/business/operation/System.php";
const GET_SYSTEM_URL = "foundation/main/business/operation/System.php?action=";

const SERVICE_URL = "foundation/main/business/operation/Services.php";
const GET_SERVICE_URL = "foundation/main/business/operation/Services.php?action=";

const PACKAGE_URL = "foundation/main/business/operation/Package.php";
const GET_PACKAGE_URL = "foundation/main/business/operation/Package.php?action=";

const EVENT_URL = "foundation/main/business/operation/Events.php";
const GET_EVENT_URL = "foundation/main/business/operation/Events.php?action=";

const GALLERY_URL = "foundation/main/business/operation/Gallery.php";
const GET_GALLERY_URL = "foundation/main/business/operation/Gallery.php?action=";

const ROOM_URL = "foundation/main/business/operation/Rooms.php";
const GET_ROOM_URL = "foundation/main/business/operation/Rooms.php?action=";

const REFERENCE_URL = "foundation/main/business/operation/Reference.php";
const GET_REFERENCE_URL = "foundation/main/business/operation/Reference.php?action=";

const LOGIN_URL = "foundation/main/business/operation/Login.php";
const GET_LOGIN_URL = "foundation/main/business/operation/Login.php?action=";

const MAILER_URL = "foundation/main/business/integration/mailer.php";

const ADMIN_BASE_URL = "./../.";

const WEB_RESOURCE_URL = "./web/resources/";
const WEB_ROOM_IMAGE_URL = "./web/resources/images/rooms/";

const GET_SERVICE_LIST = "retrieve-service-list";
const GET_SERVICE_RESERVATION_LIST = "retrieve-service-reservation-list";
const GET_UPCOMING_EVENT = "retrieve-upcoming-event";
const GET_WEBSITE_INFO = "retrieve-website-info";
const GET_GALLERY = "retrieve-gallery";
const GET_CONTACT_INFO = "retrieve-contact-info";
const GET_ROOM_LIST = "retrieve-room-list";
const GET_ROOM_RESERVATION_LIST = "retrieve-room-reservation-list";
const GET_ALL_ROOM_RESERVATION_LIST = "retrieve-all-room-reservation-list";
const GET_EMAIL_ADDRESS = "retrieve-email-address";
const GET_EMAIL_CHECK = 'email-check';
const GET_USER_ROOM_RESERVATION = "retrieve-user-room-reservation-list";

const GET_USER_SERVICE_RESERVATION = "retrieve-user-service-reservation-list";

const CHANGE_PASSWORD = "change-password";

const GET_ADDRESS_REFERENCE = "retrieve-address-reference-data";

const MM_DD_YYYY_FORMAT = "mm-dd-yyyy";
const YYYY_MM_DD_FORMAT = 'yyyy-mm-dd';

const DESTROY = "destroy";

const LOGIN = "login";
const ROOM = "room";
const SERVICE = "service";

const WEAK = "weak";
const MEDIUM = "medium";
const STRONG = "strong";

const WEB_LOGIN_DIRECTORY = 'web/main/user/form/form-login.html';
const WEB_FORGOT_PASSWORD_DIRECTORY = 'web/main/user/form/form-forgot-password.html';

const PAX = "Pax";
const QUANTITY = "Quantity";

const BREAK_TIME = 2;
var operating_start_time = "08:00:00";
var operation_end_time = "17:00:00";

var upcoming_event;
var room_list;
var room_category_list;
var service_list;
var logged_in_user;
var room_reservation_list;
var service_reservation_list;
var address_reference;

var login_source;
var forgot_password_source;
var create_account_source;

var CHILDREN_PRICE = 250;
var ADULT_PRICE = 500;

var BREAKFAST_PRICE = 250;
var LUNCH_PRICE = 300;
var SNACK_PRICE = 75;
var DINNER_PRICE = 300;

var TOWEL_PRICE = 100;
var PILLOW_PRICE = 100;
var BLANKET_PRICE = 100;
var BED_PRICE = 300;

var VIDEOKE_PRICE = 500;
var SOUND_SYSTEM_PRICE = 500;
var CHAIR_PRICE = 100;
var TABLE_PRICE = 150;


var roomCategoryID;
var additionalGuestChildren = 0;
var additionalGuestAdult = 0;

var additionalFoodBreakfast = 0;
var additionalFoodBreakfastServing = 0;

var additionalFoodLunch = 0;
var additionalFoodLunchServing = 0;

var additionalFoodSnack = 0;
var additionalFoodSnackServing = 0;

var additionalFoodDinner = 0;
var additionalFoodDinnerServing = 0;

var additionalItemTowel = 0;
var additionalItemPillow = 0;
var additionalItemBlanket = 0;
var additionalItemBed = 0;
var additionalItemVideoke = 0;
var additionalItemSoundSystem = 0;
var additionalItemChair = 0;
var additionalItemTable = 0;
var foodSpecialInstruction = "";
var roomPrice = 0;
var totalDays = 0;

var totalDaysPrice = 0;

var totalPriceGuestChildrenPrice = 0;
var totalPriceGuestAdultPrice = 0;
var totalPriceBreakfast = 0;
var totalPriceLunch = 0;
var totalPriceSnack = 0;
var totalPriceDinner = 0;
var totalPriceTowel = 0;
var totalPricePillow = 0;
var totalPriceBlanket = 0;
var totalPriceBed = 0;
var totalPriceVideoke = 0;
var totalPriceSoundSystem = 0;
var totalPriceChair = 0;
var totalPriceTable = 0;

var totalAdditionalPrice = 0;

var totalAmount = 0;

var checkinRoomDate;
var checkinRoomTime;
var checkoutRoomDate;
var checkoutRoomTime;
var availableRooms;
var numberOfRoomToReserve = 0;

var service_id = "";
var package_id = "";

function initializeValue() {
    additionalGuestChildren = 0;
    additionalGuestAdult = 0;

    additionalFoodBreakfast = 0;
    additionalFoodBreakfastServing = 0;

    additionalFoodLunch = 0;
    additionalFoodLunchServing = 0;

    additionalFoodSnack = 0;
    additionalFoodSnackServing = 0;

    additionalFoodDinner = 0;
    additionalFoodDinnerServing = 0;

    additionalItemTowel = 0;
    additionalItemPillow = 0;
    additionalItemBlanket = 0;
    additionalItemBed = 0;
    additionalItemVideoke = 0;
    additionalItemSoundSystem = 0;
    additionalItemChair = 0;
    additionalItemTable = 0;
    foodSpecialInstruction = "";
    roomPrice = 0;
    totalDays = 0;
    availableRooms = [];

    totalDaysPrice = 0;

    totalPriceGuestChildrenPrice = 0;
    totalPriceGuestAdultPrice = 0;
    totalPriceBreakfast = 0;
    totalPriceLunch = 0;
    totalPriceSnack = 0;
    totalPriceDinner = 0;
    totalPriceTowel = 0;
    totalPricePillow = 0;
    totalPriceBlanket = 0;
    totalPriceBed = 0;
    totalPriceVideoke = 0;
    totalPriceSoundSystem = 0;
    totalPriceChair = 0;
    totalPriceTable = 0;

    totalAdditionalPrice = 0;

    totalAmount = 0;
    numberOfRoomToReserve = 0
}