<?php

require_once('../../configuration/Configuration.php');
require_once('../model/RoomDM.php');
require_once('../model/AccountDM.php');
class Rooms extends DatabaseConfiguration {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}

	public function __destruct(){
		parent::__destruct();
	}

	public function retrieveRoomCategoryList() {
		$sql = "SELECT * FROM room_category WHERE status='1'";
		$result = $this->conn->query($sql);
	
		if ($result->num_rows > 0) {
			$roomList = array();
	
			while ($row = $result->fetch_assoc()) {
				$roomDM = new RoomDM();
				$roomDM->set_id($row["id"]);
				$roomDM->set_name($row["name"]);
				$roomDM->set_description($row["description"]);
				$roomDM->set_image($row["image"]);
				$roomDM->set_price($row["price"]);
				$roomDM->set_pax($row["pax"]);
				$roomDM->set_maxPax($row["max_pax"]);
				$roomDM->set_amenities($row["amenities"]);
				$roomDM->set_status($row["status"]);
	
				// Get the list of image file names
				$imageFileNames = explode(',', $row["image"]);
				$roomDM->set_image($imageFileNames);
	
				array_push($roomList, $roomDM);
			}
		}
	
		// Return the JSON response with the list of image file names
		return json_encode(array("statusCode" => 200, "roomList" => $roomList));
		$this->conn->close();
	}

	public function retrieveAllRooms(){
        $sql = "SELECT 
		room.*,
		room.name AS room_name,
		room.status AS room_status,
		room_category.*,
		room_category.name AS room_category_name 
		FROM room 
		LEFT JOIN room_category ON room.room_category_id = room_category.id ";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $roomList = array();
            while($row = $result->fetch_assoc()) {
                $roomDM = new RoomDM();
                $roomDM->set_id($row["room_id"]);
                $roomDM->set_name($row["room_name"]);
                $roomDM->set_description($row["description"]);
				$roomDM->set_image($row["image"]);
				$roomDM->set_price($row["price"]);
				$roomDM->set_pax($row["pax"]);
				$roomDM->set_maxPax($row["max_pax"]);
				$roomDM->set_amenities($row["amenities"]);
				$roomDM->set_roomCategoryName($row["room_category_name"]);
				$roomDM->set_roomCategoryID($row["id"]);
				$roomDM->set_status($row["room_status"]);
                array_push($roomList, $roomDM);
            }
        }
        return json_encode(array("statusCode"=>200, "roomList"=>$roomList));
        $this->conn->close();
	}

	public function searchRoom(){
		$search = $_POST['search'];
        $sql = "SELECT * FROM room_category WHERE MATCH(name) AGAINST ('$search') AND status='1'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $roomList = array();
            while($row = $result->fetch_assoc()) {
                $roomDM = new RoomDM();
                $roomDM->set_id($row["id"]);
                $roomDM->set_name($row["name"]);
                $roomDM->set_description($row["description"]);
				$roomDM->set_image($row["image"]);
				$roomDM->set_price($row["price"]);
				$roomDM->set_pax($row["pax"]);
				$roomDM->set_maxPax($row["max_pax"]);
                array_push($roomList, $roomDM);
            }
			return json_encode(array("statusCode"=>200, "roomList"=>$roomList));
        }
		return json_encode(array("statusCode"=>404));
        $this->conn->close();
	}

	function roomReservation(){
		$roomIDs = json_decode($_POST['roomID'], true);

		$roomCategoryID=$_POST['roomCategoryID'];
		$accountID=$_POST['accountID'];
		$checkinDate=$_POST['checkinDate'];
		$checkinTime=$_POST['checkinTime'];
		$checkoutDate=$_POST['checkoutDate'];
		$checkoutTime=$_POST['checkoutTime'];

		$children=$_POST['children'];
		$adult=$_POST['adult'];

		$breakfast=$_POST['breakfast'];
		$breakfastServing=$_POST['breakfastServing'];

		$lunch=$_POST['lunch'];
		$lunchServing=$_POST['lunchServing'];

		$snack=$_POST['snack'];
		$snackServing=$_POST['snackServing'];

		$dinner=$_POST['dinner'];
		$dinnerServing=$_POST['dinnerServing'];

		$towel=$_POST['towel'];

		$pillow=$_POST['pillow'];
		$blanket=$_POST['blanket'];
		$bed=$_POST['bed'];

		$totalPrice=$_POST['totalPrice'];

		$specialInstruction=$_POST['foodSpecialInstruction'];
		
		$status="1";

		foreach ($roomIDs as $index => $roomID) {
			if($index == 0){
				$sql = "INSERT INTO 
				`room_reservation`(
				  `room_id`, 
				  `room_category_id`, 
				  `account_id`, 
				  `checkin_date`,
				  `checkin_time`,
				  `checkout_date`,
				  `checkout_time`,
	  
				  `additional_pax_adult_number`,
				  `additional_pax_children_number`,
	  
				  `additional_food_breakfast`,
				  `additional_food_breakfast_serving`,

				  `additional_food_lunch`,
				  `additional_food_lunch_serving`,

				  `additional_food_snack`,
				  `additional_food_snack_serving`,

				  `additional_food_dinner`,
				  `additional_food_dinner_serving`,

				  `additional_food_special_instruction`,
	  
				  `additional_item_towel`,
				  `additional_item_pillow`,
				  `additional_item_blanket`,
				  `additional_item_bed`,
	  
				  `total_price`,
	  
				  `status`) 
				VALUES (
				  '$roomID',
				  '$roomCategoryID',
				  '$accountID',
				  '$checkinDate',
				  '$checkinTime',
				  '$checkoutDate',
				  '$checkoutTime',
	  
				  '$children',
				  '$adult',
	  
				  '$breakfast',
				  '$breakfastServing',

				  '$lunch',
				  '$lunchServing',

				  '$snack',
				  '$snackServing',

				  '$dinner',
				  '$dinnerServing',

				  '$specialInstruction',
	  
				  '$towel',
				  '$pillow',
				  '$blanket',
				  '$bed',
				  
				  '$totalPrice',
	  
				  '$status'
				  )";

					if (mysqli_query($this->conn, $sql)) {
						$last_id = $this->conn->insert_id;
						// return json_encode(array("statusCode"=>200, "roomRequestID"=>$last_id));
					}
			} else {
				$sql = "INSERT INTO 
				`room_reservation`(
				  `room_id`, 
				  `room_category_id`, 
				  `account_id`, 
				  `checkin_date`,
				  `checkin_time`,
				  `checkout_date`,
				  `checkout_time`,

				  `status`) 
				VALUES (
				  '$roomID',
				  '$roomCategoryID',
				  '$accountID',
				  '$checkinDate',
				  '$checkinTime',
				  '$checkoutDate',
				  '$checkoutTime',
	  
				  '$status'
				  )";

				if (mysqli_query($this->conn, $sql)) {
					$last_id = $this->conn->insert_id;
					// return json_encode(array("statusCode"=>200, "roomRequestID"=>$last_id));
				}
			}
		}

		// if (mysqli_query($this->conn, $sql)) {
		// 	$last_id = $this->conn->insert_id;
		// 	return json_encode(array("statusCode"=>200, "roomRequestID"=>$last_id));
		// }
		return json_encode(array("statusCode"=>200));
	}
	

	function retrieveTotalRoomNumber($roomCategoryID){
        $sql = "SELECT * FROM room WHERE room_category_id = '$roomCategoryID' ";
        $result = $this->conn->query($sql);
        return $result->num_rows;
	}

	// Retrieve the list of room reservation for each room
	// 1) Retrieve list of room based on category that has been requested
	// 2) Retrieve room reservation data for each room
	function retrieveRoomReservationList(){
		$roomCategoryID = $_GET['roomCategoryID'];
		$sql = "SELECT room_id, name FROM room WHERE room_category_id = '$roomCategoryID' AND status=1";
        $result = $this->conn->query($sql);
		$roomReservation = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
				$roomReservationListDM = new RoomReservationListDM();
				$roomReservationListDM->set_roomID($row['room_id']);
				$roomReservationListDM->set_roomName($row['name']);
				$roomReservationListDM->set_reservationList($this->retrieveRoomReservation($row['room_id']));
                array_push($roomReservation, $roomReservationListDM);
            }
			return json_encode(array("statusCode"=>200, "roomList"=>$roomReservation, "totalRoomNumber"=>$this->retrieveTotalRoomNumber($roomCategoryID)));
        }
		return json_encode(array("statusCode"=>404));
	}

	function retrieveRoomReservation($roomID){
		$sql = "SELECT room_id, checkin_date, checkin_time, checkout_date, checkout_time FROM room_reservation where room_id=$roomID";
        $result = $this->conn->query($sql);
        $roomSchedule = array();
		if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $ReservationScheduleDM = new ReservationScheduleDM();
				$ReservationScheduleDM->set_checkinDate($row["checkin_date"]);
                $ReservationScheduleDM->set_checkinTime($row["checkin_time"]);
				$ReservationScheduleDM->set_checkoutDate($row["checkout_date"]);
                $ReservationScheduleDM->set_checkoutTime($row["checkout_time"]);
                array_push($roomSchedule, $ReservationScheduleDM);
            }
        }
		return $roomSchedule;
	}

	function retrieveAllRoomReservation(){
		$sql = "SELECT 
		room_category.name AS room_category_name,
		room_category.price AS room_price,
		room.name AS room_name,
		room_reservation.status AS reservation_status,
		room_reservation.*,
		account_information.*

		FROM room_reservation 
		LEFT JOIN room_category 
		ON room_reservation.room_category_id = room_category.id 
		LEFT JOIN room 
		ON room.room_id = room_reservation.room_id 
		LEFT JOIN account_information 
		ON room_reservation.account_id = account_information.account_id 
		WHERE room_reservation.status != 0
		ORDER BY `room_reservation`.`checkin_date` DESC";
        $result = $this->conn->query($sql);
        $reservationList = array();
		if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $roomReservationDM = new RoomReservationDM();
				$roomReservationDM->set_reservationID($row["id"]);
				$roomReservationDM->set_status($row["reservation_status"]);

				$accountDM = new AccountDM();
				$accountDM->set_id($row['account_id']);
				$accountDM->set_firstName($row['first_name']);
				$accountDM->set_middleName($row['middle_name']);
				$accountDM->set_lastName($row['last_name']);

				$accountDM->set_province($row['province']);
				$accountDM->set_municipality($row['municipality']);
				$accountDM->set_barangay($row['barangay']);

				$accountDM->set_phone($row['phone']);
				$accountDM->set_email($row['email']);

				$roomReservationDM->set_accountInformation($accountDM);

				$roomDM = new RoomDM();
				$roomDM->set_roomCategoryName($row["room_category_name"]);
				$roomDM->set_price($row["room_price"]);


				$roomReservationDM->set_roomInformation($roomDM);

				$roomReservationDM->set_checkinDate($row["checkin_date"]);
                $roomReservationDM->set_checkinTime($row["checkin_time"]);
				$roomReservationDM->set_checkoutDate($row["checkout_date"]);
                $roomReservationDM->set_checkoutTime($row["checkout_time"]);
				
				$roomReservationDM->set_createdDate($row["date_created"]);

				$roomReservationDM->set_additionalAdult($row["additional_pax_adult_number"]);
				$roomReservationDM->set_additionalChildren($row["additional_pax_children_number"]);
				
				$roomReservationDM->set_additionalBreakFast($row["additional_food_breakfast"]);
				$roomReservationDM->set_additionalBreakFastServing($row["additional_food_breakfast_serving"]);

				$roomReservationDM->set_additionalLunch($row["additional_food_lunch"]);
				$roomReservationDM->set_additionalLunchServing($row["additional_food_lunch_serving"]);

				$roomReservationDM->set_additionalSnack($row["additional_food_snack"]);
				$roomReservationDM->set_additionalSnackServing($row["additional_food_snack_serving"]);
				
				$roomReservationDM->set_additionalDinner($row["additional_food_dinner"]);
				$roomReservationDM->set_additionalDinnerServing($row["additional_food_dinner_serving"]);

				$roomReservationDM->set_additionalFoodInstruction($row["additional_food_special_instruction"]);
				$roomReservationDM->set_additionalTowel($row["additional_item_towel"]);

				$roomReservationDM->set_additionalPillow($row["additional_item_pillow"]);
				$roomReservationDM->set_additionalBlanket($row["additional_item_blanket"]);
				$roomReservationDM->set_additionalBed($row["additional_item_bed"]);

				$roomReservationDM->set_totalAmount($row["total_price"]);
				$roomReservationDM->set_receipt($row["receipt"]);

				$roomReservationDM->set_additionalInfoRequest($row["additionalInfo"]);
				$roomReservationDM->set_reasonForCancellation($row["cancelledReason"]);
				$roomReservationDM->set_refund($row["cancelledRefund"]);

                array_push($reservationList, $roomReservationDM);
            }
        }
		return json_encode(array("statusCode"=>200, "reservationList"=>$reservationList));
	}

	function retrieveArchiveRoomReservation(){
		$sql = "SELECT 
		room_category.name AS room_category_name,
		room.name AS room_name,
		room_reservation.status AS reservation_status,
		room_reservation.*,
		account_information.*

		FROM room_reservation 
		LEFT JOIN room_category 
		ON room_reservation.room_category_id = room_category.id 
		LEFT JOIN room 
		ON room.room_id = room_reservation.room_id 
		LEFT JOIN account_information 
		ON room_reservation.account_id = account_information.account_id 
		WHERE room_reservation.status = 0
		ORDER BY `room_reservation`.`checkin_date` DESC";
        $result = $this->conn->query($sql);
        $reservationList = array();
		if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $roomReservationDM = new RoomReservationDM();
				$roomReservationDM->set_reservationID($row["id"]);
				$roomReservationDM->set_status($row["reservation_status"]);

				$accountDM = new AccountDM();
				$accountDM->set_id($row['account_id']);
				$accountDM->set_firstName($row['first_name']);
				$accountDM->set_middleName($row['middle_name']);
				$accountDM->set_lastName($row['last_name']);

				$accountDM->set_province($row['province']);
				$accountDM->set_municipality($row['municipality']);
				$accountDM->set_barangay($row['barangay']);

				$accountDM->set_phone($row['phone']);
				$accountDM->set_email($row['email']);

				$roomReservationDM->set_accountInformation($accountDM);

				$roomDM = new RoomDM();
				$roomDM->set_roomCategoryName($row["room_category_name"]);
				$roomDM->set_name($row["room_name"]);

				$roomReservationDM->set_roomInformation($roomDM);

				$roomReservationDM->set_checkinDate($row["checkin_date"]);
                $roomReservationDM->set_checkinTime($row["checkin_time"]);
				$roomReservationDM->set_checkoutDate($row["checkout_date"]);
                $roomReservationDM->set_checkoutTime($row["checkout_time"]);
				
				$roomReservationDM->set_createdDate($row["date_created"]);

				$roomReservationDM->set_additionalAdult($row["additional_pax_adult_number"]);
				$roomReservationDM->set_additionalChildren($row["additional_pax_children_number"]);

				$roomReservationDM->set_additionalBreakFast($row["additional_food_breakfast"]);
				$roomReservationDM->set_additionalBreakFastServing($row["additional_food_breakfast_serving"]);

				$roomReservationDM->set_additionalLunch($row["additional_food_lunch"]);
				$roomReservationDM->set_additionalLunchServing($row["additional_food_lunch_serving"]);

				$roomReservationDM->set_additionalSnack($row["additional_food_snack"]);
				$roomReservationDM->set_additionalSnackServing($row["additional_food_snack_serving"]);

				$roomReservationDM->set_additionalDinner($row["additional_food_dinner"]);
				$roomReservationDM->set_additionalDinnerServing($row["additional_food_dinner_serving"]);

				$roomReservationDM->set_additionalFoodInstruction($row["additional_food_special_instruction"]);
				$roomReservationDM->set_additionalTowel($row["additional_item_towel"]);

				$roomReservationDM->set_additionalPillow($row["additional_item_pillow"]);
				$roomReservationDM->set_additionalBlanket($row["additional_item_blanket"]);
				$roomReservationDM->set_additionalBed($row["additional_item_bed"]);

				$roomReservationDM->set_totalAmount($row["total_price"]);

                array_push($reservationList, $roomReservationDM);
            }
        }
		return json_encode(array("statusCode"=>200, "reservationList"=>$reservationList));
	}


	function retrieveUserRoomReservationList(){
		$accountID = $_GET['accountID'];
        $sql = "SELECT room_reservation.*, room.name AS room_name, room_category.name AS room_category_name
		FROM room_reservation
		LEFT JOIN room ON room_reservation.room_id=room.room_id 
		LEFT JOIN room_category ON room_category.id=room.room_category_id 
		WHERE account_id = '$accountID'  ";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $reservationList = array();
            while($row = $result->fetch_assoc()) {
                $roomRequestDM = new RoomRequestDM();
                $roomRequestDM->set_id($row["id"]);
                $roomRequestDM->set_roomID($row["room_id"]);
				$roomRequestDM->set_name($row['room_category_name'] . ' - ' . $row["room_name"]);
				$roomRequestDM->set_checkinDate($row["checkin_date"]);
                $roomRequestDM->set_checkinTime($row["checkin_time"]);
				$roomRequestDM->set_checkoutDate($row["checkout_date"]);
                $roomRequestDM->set_checkoutTime($row["checkout_time"]);
				$roomRequestDM->set_price($row["total_price"]);
				$roomRequestDM->set_status($row["status"]);
				$roomRequestDM->set_image($row["receipt"]);
                array_push($reservationList, $roomRequestDM);
            }
			return json_encode(array("statusCode"=>200, "reservationList"=>$reservationList));
        }
		return json_encode(array("statusCode"=>404));
	}

	function pay(){
		$id = $_POST['id'];
        $sql = "UPDATE room_reservation
		SET status = '3'
		WHERE id = '$id'";

		if (mysqli_query($this->conn, $sql)) {
			return json_encode(array("statusCode"=>200));	
		} else {
			return json_encode(array("statusCode"=>500));
		}
	}

	function modifyImageName($customName){
		$name = explode('.', $_FILES['file']['name']);
		return $customName . (count($name) > 1 ? '.' . $name[1] : '');
	}

	function modifyImageNameRefund($customName){
		$name = explode('.', $_FILES['refund']['name']);
		return $customName . (count($name) > 1 ? '.' . $name[1] : '');
	}

	function validateImageExtension($extension){
		$allowed_extensions = array("jpg","jpeg","png");
		if(in_array(strtolower($extension), $allowed_extensions)) {
		  return true;
		}
		return false;
	}
	  
	function uploadImage($location){
		$extension = strtolower(pathinfo($location,PATHINFO_EXTENSION));
		if($this->validateImageExtension($extension)){
		  if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
			return 200;
		  }
		  return 5000;
		}
		return 5001;
	  }

	function UploadRoomPayment(){
		$imageName = rand(0,1000) + time();
		$imageName = str_replace(":","",$imageName);
		$imageName = str_replace("-","",$imageName);
		$statusCode = array();
		$fileName = "";
		if($_FILES != null){
		  $target_dir = "./../../../../web/resources/images/";
		  $fileName = $this->modifyImageName($imageName);
		  $location = $target_dir . $fileName;
		  $uploadStatus = $this->uploadImage($location);
		  if($uploadStatus != 200) {
			return json_encode(array("statusCode"=>$uploadStatus));
		  } 
		  array_push($statusCode, $uploadStatus); 
		}

		$img_path = "web/resources/images/{$fileName}";

		$id = $_POST['id'];
        $sql = "UPDATE room_reservation
		SET status = '4', receipt = '$img_path'
		WHERE id = '$id'";

		if (mysqli_query($this->conn, $sql)) {
			array_push($statusCode, 200);
		} else {
			array_push($statusCode, 5004);
		}
		return json_encode(array("statusCode"=>$statusCode));
	}

	function createRoomCategory() {
		// Check if the form is submitted
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			// Extract form data
			$name = $_POST['name'];
			$description = $_POST['description'];
			$price = $_POST['price'];
			$pax = $_POST['pax'];
			$maxPax = $_POST['max-pax'];
			$amenities = $_POST['amenities'];
			$action = $_POST['action'];
	
			// Check if the form action is for creating a room category
			if ($action == 'create-room-category') {
				// Check if the 'images' field is set in the uploaded files
				if (isset($_FILES['images'])) {
					$uploadedImages = $_FILES['images'];
					$imageNames = [];
	
					// Loop through each uploaded image
					foreach ($uploadedImages['tmp_name'] as $key => $tmp_name) {
						$file_name = $uploadedImages['name'][$key];
						$file_tmp = $tmp_name;
	
						// Generate a unique name for each image
						$unique_name = time() . '-' . uniqid() . '-' . $file_name;
	
						// Specify the folder where you want to save the images
						$upload_path = "../../../../web/resources/images/rooms/" . $unique_name;

						// Create the directory if it doesn't exist
						if (!file_exists("../../../../web/resources/images/rooms/")) {
							mkdir("../../../../web/resources/images/rooms/", 0777, true);
						}
	
						// Move the uploaded file to the specified folder
						move_uploaded_file($file_tmp, $upload_path);
	
						// Add the unique name to the list
						$imageNames[] = $unique_name;
					}
	
					// Combine the image names into a comma-separated string
					$imageList = implode(',', $imageNames);
	
					// Now you can save $imageList to your database in the 'image' field
					// Use proper SQL queries or your preferred method for database interaction
	
					// Example SQL query using mysqli_query
					$sql = "INSERT INTO room_category (name, description, price, pax, max_pax, amenities, image) 
							VALUES ('$name', '$description', '$price', '$pax', '$maxPax', '$amenities', '$imageList')";
	
					// Assuming $this->conn is your database connection
					$result = mysqli_query($this->conn, $sql);
	
					if ($result) {
						// Respond with success status
						$response['statusCode'] = 200;
						$response['message'] = 'Room category has been successfully saved!';
					} else {
						// Respond with an error status if the query fails
						$response['statusCode'] = 5003;
						$response['message'] = 'Database error: ' . mysqli_error($this->conn);
					}
				} else {
					// Respond with an error status if 'images' field is not set
					$response['statusCode'] = 5001;
					$response['message'] = 'You\'re trying to upload an invalid File Type!';
				}
			} else {
				// Respond with an error status if the action is not recognized
				$response['statusCode'] = 5002;
				$response['message'] = 'Invalid action specified!';
			}
	
			// Return the JSON response
			header('Content-Type: application/json');
			echo json_encode($response);
		}
	}

	function createRoom(){
		$categoryID=$_POST['roomCategoryID'];
		$name=$_POST['name'];
		$status="1";

		$sql = "INSERT INTO 
				`room`(
				  `room_category_id`, 
				  `name`, 
				  `status`) 
				VALUES (
				  '$categoryID',
				  '$name',
				  '$status'
				)";

		if (mysqli_query($this->conn, $sql)) {
			$last_id = $this->conn->insert_id;
			return json_encode(array("statusCode"=>200, "roomID"=>$last_id));
		} else {
			return json_encode(array("statusCode"=>500));
		}
	}

	function updateRoomCategory(){
		$roomCategoryID = $_POST['roomCategoryID'];
		$name = $_POST['name'];
		$description = $_POST['description'];
		$price = $_POST['price'];
		$pax = $_POST['pax'];
		$maxPax = $_POST['max-pax'];
		$amenities = $_POST['amenities'];
		$status = "1";
	
		$statusCode = array();
		$img_path = "";
	
		// Check if new images are uploaded
		if (!empty($_FILES['images']['name'][0])) {
			$imageNames = [];
			foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
				$file_name = $_FILES['images']['name'][$key];
				$file_tmp = $tmp_name;
	
				// Generate a unique name for each image
				$unique_name = time() . '-' . uniqid() . '-' . $file_name;
	
				// Specify the folder where you want to save the images
				$upload_path = "./../../../../web/resources/images/rooms/" . $unique_name;
	
				// Create the directory if it doesn't exist
				if (!file_exists("../../../../web/resources/images/rooms/")) {
					mkdir("../../../../web/resources/images/rooms/", 0777, true);
				}
	
				// Move the uploaded file to the specified folder
				move_uploaded_file($file_tmp, $upload_path);
	
				// Add the unique name to the list
				$imageNames[] = $unique_name;
			}
	
			// Combine the image names into a comma-separated string
			$img_path = implode(',', $imageNames);
		}
	
		// Update the database with the new data
		$sql = "UPDATE `room_category` SET
				`name`=?,
				`description`=?,
				`price`=?,
				`pax`=?,
				`max_pax`=?,
				`amenities`=?";
	
		// Only include the image field in the update if new images are uploaded
		if (!empty($img_path)) {
			$sql .= ", `image`=?";
		}
	
		$sql .= ", `status`=?
				WHERE id=?";
	
		$stmt = mysqli_prepare($this->conn, $sql);
	
		if ($stmt === false) {
			// Handle the error
			return json_encode(array("statusCode" => 500));
		}
	
		// Bind the parameters
		if (!empty($img_path)) {
			mysqli_stmt_bind_param($stmt, "sssssssii", $name, $description, $price, $pax, $maxPax, $amenities, $img_path, $status, $roomCategoryID);
		} else {
			mysqli_stmt_bind_param($stmt, "sssssssi", $name, $description, $price, $pax, $maxPax, $amenities, $status, $roomCategoryID);
		}
	
		// Execute the statement
		if (mysqli_stmt_execute($stmt)) {
			array_push($statusCode, 200);
			return json_encode(array("statusCode" => 200));
		} else {
			return json_encode(array("statusCode" => 500));
		}
	}

	// function updateRoomCategory(){
	// 	$roomCategoryID=$_POST['roomCategoryID'];
	// 	$name=$_POST['name'];
	// 	$description=$_POST['description'];
	// 	$price=$_POST['price'];
	// 	$pax=$_POST['pax'];
	// 	$maxPax=$_POST['max-pax'];
	// 	$amenities=$_POST['amenities'];	
	// 	$status="1";

	// 	$statusCode = array();
	// 	$img_path = "";
		
	// 	if($_FILES != null){
	// 		$fileName = "";
	// 		$imageName = rand(0,1000) . time();
	// 		$imageName = str_replace(":","",$imageName);
	// 		$imageName = str_replace("-","",$imageName);
	// 		$target_dir = "./../../../../web/resources/images/";
	// 		$fileName = $this->modifyImageName($imageName);
	// 		$location = $target_dir . $fileName;
	// 		$uploadStatus = $this->uploadImage($location);
	// 		if($uploadStatus != 200) {
	// 			return json_encode(array("statusCode"=>$uploadStatus));
	// 		} 
	// 		array_push($statusCode, $uploadStatus); 
	// 		$img_path = "web/resources/images/{$fileName}";
	// 	}

	// 	$sql = "UPDATE `room_category` SET
	// 		`name`='$name',
	// 		`description`='$description',
	// 		`price`='$price',
	// 		`pax`='$pax',
	// 		`max_pax`='$maxPax',
	// 		`amenities`='$amenities',
	// 		`status`='$status'
	// 		WHERE id=$roomCategoryID";
		
	// 	if($img_path != ""){
	// 		$sql = "UPDATE `room_category` SET
	// 		`name`='$name',
	// 		`description`='$description',
	// 		`price`='$price',
	// 		`pax`='$pax',
	// 		`max_pax`='$maxPax',
	// 		`amenities`='$amenities',
	// 		`image`='$img_path',
	// 		`status`='$status'
	// 		WHERE id=$roomCategoryID";
	// 	}

	// 	if (mysqli_query($this->conn, $sql)) {
	// 		array_push($statusCode, 200);
	// 		return json_encode(array("statusCode"=>200));
	// 	} else {
	// 		return json_encode(array("statusCode"=>200));
	// 	}
	// }

	function updateRoom(){
		$roomID=$_POST['roomID'];
		$roomCategoryID=$_POST['roomCategoryID'];
		$name=$_POST['name'];
		$status=$_POST['roomStatus'];
		
		$statusCode = array();
		
		$sql = "UPDATE `room` SET
			`room_category_id`='$roomCategoryID',
			`name`='$name',
			`status`='$status'
			WHERE room_id=$roomID";

		if (mysqli_query($this->conn, $sql)) {
			return json_encode(array("statusCode"=>200));
		} else {
			return json_encode(array("statusCode"=>500));
		}
	}

	function deleteRoomCategory(){
		$id = $_POST['id'];

		$this->conn->begin_transaction();

		$sql1 = "DELETE FROM room_category WHERE id='$id'";
		if ($this->conn->query($sql1) !== TRUE) {
			$this->conn->rollback();
			return json_encode(array("statusCode"=>500));
		} else {
			$sql2 = "DELETE FROM room WHERE room_category_id='$id'";
			if ($this->conn->query($sql2) !== TRUE) {
				$this->conn->rollback();
				return json_encode(array("statusCode"=>500));
			} else {
				$this->conn->commit();
				return json_encode(array("statusCode"=>200));
			}
		}
	}

	function deleteRoom(){
		$id = $_POST['id'];
		$sql = "DELETE from room where room_id='$id'";
		if (mysqli_query($this->conn, $sql)) {
			return json_encode(array("statusCode"=>200));
		} else {
			return json_encode(array("statusCode"=>500));
		}
	}

	function updateRoomStatus(){
		$id = $_POST['id'];
		$status = $_POST['status'];
        $sql = "UPDATE room_reservation
		SET status = '$status'
		WHERE id = '$id'";

		if (mysqli_query($this->conn, $sql)) {
			return json_encode(array("statusCode"=>"200"));
		} else {
			return json_encode(array("statusCode"=>"500"));		
		}
	}
	
	function updateRoomReservation() {
		$id = $_POST['id'];
		$status = $_POST['status'];

		$formFields = [
			'checkinDate', 'checkinTime', 'checkoutDate', 'checkoutTime',
			'additionalGuestAdult', 'additionalGuestChildren',
			'additionalFoodBreakfast', 'additionalFoodBreakfastServing',
			'additionalFoodLunch', 'additionalFoodLunchServing',
			'additionalFoodSnack', 'additionalFoodSnackServing',
			'additionalFoodDinner', 'additionalFoodDinnerServing',
			'additionalFoodSpecialInstruction',
			'additionalItemTowel', 'additionalItemPillow',
			'additionalItemBlanket', 'additionalItemBed',
			'total_price'
		];

		$imageFileName = rand(0, 1000) . '-' . $id;
		$originalImageFileName = $imageFileName; // Preserve the original image file name

		$fileName = "";

		// Handle File Uploads (refund)
		if (!empty($_FILES['refund']['name'])) {
			$refundFile = $_FILES['refund'];
			$fileExtension = pathinfo($refundFile['name'], PATHINFO_EXTENSION);
			$fileName = $this->modifyImageNameRefund($originalImageFileName);
			$filePath = "./../../../../web/resources/images/" . $fileName;

			if (move_uploaded_file($refundFile['tmp_name'], $filePath)) {
				// File uploaded successfully
			} else {
				return json_encode(array("statusCode" => "500", "error" => "Failed to upload image"));
			}
		}

		// Additional Info and Reason for Cancellation
		$additionalInfo = !empty($_POST['additional_info']) ? $_POST['additional_info'] : null;
		$reasonForCancellation = !empty($_POST['reasonForCancellation']) ? $_POST['reasonForCancellation'] : null;
		$cancelledRefund = !empty($fileName) ? $fileName : null;

		// Use prepared statements to prevent SQL injection
		$sql = "UPDATE room_reservation
				SET status = ?,
					checkin_date = ?,
					checkin_time = ?,
					checkout_date = ?,
					checkout_time = ?,
					additional_pax_adult_number = ?,
					additional_pax_children_number = ?,
					additional_food_breakfast = ?,
					additional_food_breakfast_serving = ?,
					additional_food_lunch = ?,
					additional_food_lunch_serving = ?,
					additional_food_snack = ?,
					additional_food_snack_serving = ?,
					additional_food_dinner = ?,
					additional_food_dinner_serving = ?,
					additional_food_special_instruction = ?,
					additional_item_towel = ?,
					additional_item_pillow = ?,
					additional_item_blanket = ?,
					additional_item_bed = ?,
					total_price = ?,
					additionalInfo = COALESCE(NULLIF(?, ''), additionalInfo),
					cancelledReason = COALESCE(NULLIF(?, ''), cancelledReason),
					cancelledRefund = COALESCE(NULLIF(?, ''), cancelledRefund)
				WHERE id = ?";

		$stmt = $this->conn->prepare($sql);

		if ($stmt) {
			$bindParams = [$status];

			foreach ($formFields as $field) {
				$bindParams[] = !empty($_POST[$field]) ? $_POST[$field] : null;
			}

			$bindParams[] = $additionalInfo;
			$bindParams[] = $reasonForCancellation;
			$bindParams[] = $cancelledRefund;
			$bindParams[] = $id;

			$types = str_repeat('s', count($bindParams));
			$stmt->bind_param($types, ...$bindParams);

			// Execute the statement
			if ($stmt->execute()) {
				$stmt->close();
				return json_encode(array("statusCode" => "200", "fileName"=> $cancelledRefund, "reasonForCancellation" => $reasonForCancellation));
			} else {
				$stmt->close();
				return json_encode(array("statusCode" => "500", "error" => $stmt->error));
			}
		} else {
			return json_encode(array("statusCode" => "500"));
		}
	} 
}



$rooms = new Rooms();
if(isset($_GET['action'])){
	switch ($_GET['action']) {
		case 'retrieve-room-list':
			echo $rooms->retrieveRoomCategoryList();
			break;
		case 'search-room':
			echo $rooms->searchRoom();
			break;
		case 'retrieve-room-reservation-list';
			echo $rooms->retrieveRoomReservationList();
			break;
		case 'retrieve-all-room-reservation-list';
			echo $rooms->retrieveAllRoomReservation();
			break;
		case 'retrieve-user-room-reservation-list';
			echo $rooms->retrieveUserRoomReservationList();
			break;
		case 'retrieve-all-rooms';
			echo $rooms->retrieveAllRooms();
			break;
		case 'retrieve-archive-room-reservation-list';
			echo $rooms->retrieveArchiveRoomReservation();
			break;
		default:
			break;
	}
}

if(isset($_POST['action'])){
	switch ($_POST['action']) {
		case 'room-reservation':
			echo $rooms->roomReservation();
			break;
		case 'pay':
			echo $rooms->pay();
			break;
		case 'upload-room-payment':
			echo $rooms->UploadRoomPayment();
			break;
		case 'create-room-category':
			echo $rooms->createRoomCategory();
			break;
		case 'create-room':
			echo $rooms->createRoom();
			break;
		case 'update-room-category':
			echo $rooms->updateRoomCategory();
			break;
		case 'update-room':
			echo $rooms->updateRoom();
			break;
		case 'delete-room-category':
			echo $rooms->deleteRoomCategory();
			break;
		case 'delete-room':
			echo $rooms->deleteRoom();
			break;
		case 'update-room-status':
			echo $rooms->updateRoomStatus();
			break;
		case 'update-room-reservation':
			echo $rooms->updateRoomReservation();
			break;
		default:
			break;
	}
}




?>