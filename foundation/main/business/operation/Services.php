<?php


// Service Front end and admin

require_once('../../configuration/Configuration.php');
require_once('../model/ServiceDM.php');
require_once('../model/AccountDM.php');
require_once('../model/PackageDM.php');
class Services extends DatabaseConfiguration {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}

	public function __destruct(){
		parent::__destruct();
	}

	public function retrieveServiceList(){
        $sql = "SELECT * FROM service where status='1'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $serviceList = array();
            while($row = $result->fetch_assoc()) {
                $basicDM = new BasicDM();
                $basicDM->set_id($row["service_id"]);
                $basicDM->set_name($row["name"]);
                $basicDM->set_description($row["description"]);
				$basicDM->set_image($row["image"]);
				$basicDM->set_price($row["price"]);
				$basicDM->set_time($row["duration_hours"]);
				$basicDM->set_status($row["status"]);
                array_push($serviceList, $basicDM);
            }
        }
        return json_encode(array("statusCode"=>200, "serviceList"=>$serviceList));
        $this->conn->close();
	}

	public function retrieveAllServiceList(){
        $sql = "SELECT * FROM service ";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $serviceList = array();
            while($row = $result->fetch_assoc()) {
                $basicDM = new BasicDM();
				$serviceID = $row["service_id"];
                $basicDM->set_id($serviceID);
                $basicDM->set_name($row["name"]);
                $basicDM->set_description($row["description"]);
				$basicDM->set_image($row["image"]);
				$basicDM->set_price($row["price"]);
				$basicDM->set_time($row["duration_hours"]);
				$basicDM->set_status($row["status"]);
				
				$packageList = array();
				$packageSQL = "SELECT 
					package.id AS package_id,
					package.package_name,
					package.package_description,
					package.package_price,
					package.duration AS package_duration
					FROM service_package 
					INNER JOIN package ON package.id = service_package.package_id 
					WHERE service_package.service_id = '$serviceID'";
				
				$packageResult = $this->conn->query($packageSQL);
				if ($packageResult->num_rows > 0) {
					while($row = $packageResult->fetch_assoc()) {
						$packageDM = new PackageDM();
						$packageDM->set_packageID($row['package_id']);
						$packageDM->set_packageName($row['package_name']);
						$packageDM->set_packageDescription($row['package_description']);
						$packageDM->set_packagePrice($row['package_price']);
						$packageDM->set_packageDuration($row['package_duration']);	
						array_push($packageList, $packageDM);
					}
				}
				$basicDM->set_additionalInformation($packageList);
                array_push($serviceList, $basicDM);
            }
        }
        return json_encode(array("statusCode"=>200, "serviceList"=>$serviceList));
        $this->conn->close();
	}

	function retrieveServiceReservationList(){
		if(isset($_GET['serviceID'])){
			$serviceID = $_GET['serviceID'];
			$sql = "SELECT 
			service_reservation.status AS reservation_status,
			service_reservation.price AS reservation_price,
			service_reservation.*,
			service.*,
			account_information.*
			FROM service_reservation 
			INNER JOIN service 
			ON service.service_id = service_reservation.service_id
			INNER JOIN account_information ON service_reservation.account_id=account_information.account_id
			WHERE service_reservation.service_id = '$serviceID' AND date(date) >= '".date("Y-m-d")."' 
			AND service_reservation.status != 0 order by date(date) ASC";
		} else {
			$sql = "SELECT 
			service_reservation.status AS reservation_status,
			service_reservation.price AS reservation_price,
			service_reservation.*,
			service.*,
			account_information.*,
			package.id AS package_id,
			package.package_name,
			package.package_description,
			package.package_price,
			package.duration AS package_duration
			FROM service_reservation 
			INNER JOIN service ON service.service_id = service_reservation.service_id 
			INNER JOIN account_information ON service_reservation.account_id=account_information.account_id
			LEFT JOIN package ON service_reservation.package_id = package.id
			WHERE service_reservation.status != 0";
		}
		$reservationList = array();        
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $reservationDM = new ServiceReservationDM();
                $reservationDM->set_id($row["service_id"]);
				$reservationDM->set_reservationID($row["id"]);
				$reservationDM->set_date($row["date"]);
                $reservationDM->set_time($row["time"]);
				$reservationDM->set_duration($row["duration_hours"]);
				$reservationDM->set_name($row["name"]);
				$reservationDM->set_description($row["description"]);
				$reservationDM->set_status($row["reservation_status"]);
				$reservationDM->set_receipt($row["receipt"]);
				$reservationDM->set_price($row["reservation_price"]);

				$accountDM = new AccountDM();
				$accountDM->set_id($row['account_id']);
				$accountDM->set_email($row['email']);
				$accountDM->set_phone($row['phone']);
				$accountDM->set_firstName($row['first_name']);
				$accountDM->set_middleName($row['middle_name']);
				$accountDM->set_lastName($row['last_name']);
				$accountDM->set_province($row['province']);
				$accountDM->set_municipality($row['municipality']);
				$accountDM->set_barangay($row['barangay']);
				$reservationDM->set_accountInformation($accountDM);

				$packageDM = new PackageDM();
				$packageDM->set_packageID($row['package_id']);
				$packageDM->set_packageName($row['package_name']);
				$packageDM->set_packageDescription($row['package_description']);
				$packageDM->set_packagePrice($row['package_price']);
				$packageDM->set_packageDuration($row['package_duration']);
				$reservationDM->set_packageInformation($packageDM);

                array_push($reservationList, $reservationDM);
            }
        }
		return json_encode(array("statusCode"=>200, "reservationList"=>$reservationList));
	}

	function retrieveArchiveService(){
		if(isset($_GET['serviceID'])){
			$serviceID = $_GET['serviceID'];
			$sql = "SELECT 
			*
			FROM service_reservation 
			INNER JOIN service 
			ON service.service_id = service_reservation.service_id
			INNER JOIN account_information ON service_reservation.account_id=account_information.account_id
			WHERE service_reservation.service_id = '$serviceID' AND date(date) >= '".date("Y-m-d")."' 
			AND service_reservation.status = 0 order by date(date) ASC";
		} else {
			$sql = "SELECT * 
			FROM service_reservation 
			INNER JOIN service ON service.service_id = service_reservation.service_id 
			INNER JOIN account_information ON service_reservation.account_id=account_information.account_id
			WHERE service_reservation.status = 0";
		}
		$reservationList = array();        
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $reservationDM = new ServiceReservationDM();
                $reservationDM->set_id($row["service_id"]);
				$reservationDM->set_reservationID($row["id"]);
				$reservationDM->set_date($row["date"]);
                $reservationDM->set_time($row["time"]);
				$reservationDM->set_name($row["name"]);
				$reservationDM->set_description($row["description"]);
				$reservationDM->set_status($row["status"]);

				$accountDM = new AccountDM();
				$accountDM->set_id($row['account_id']);
				$accountDM->set_email($row['email']);
				$accountDM->set_phone($row['phone']);
				$accountDM->set_firstName($row['first_name']);
				$accountDM->set_middleName($row['middle_name']);
				$accountDM->set_lastName($row['last_name']);
				$accountDM->set_province($row['province']);
				$accountDM->set_municipality($row['municipality']);
				$accountDM->set_barangay($row['barangay']);
				$reservationDM->set_accountInformation($accountDM);
                array_push($reservationList, $reservationDM);
            }
        }
		return json_encode(array("statusCode"=>200, "reservationList"=>$reservationList));
	}


	function retrieveUserServiceReservationList(){
		$accountID = $_GET['accountID'];
        $sql = "SELECT 
		service_reservation.status AS reservation_status, 
		service_reservation.*,
		service_reservation.price AS reservation_price,
		service.*,
		package.package_name
		FROM service_reservation
		LEFT JOIN service ON service_reservation.service_id=service.service_id
		LEFT JOIN package ON package.id=service_reservation.package_id 
		WHERE account_id = '$accountID' AND service_reservation.status != 0 ";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $reservationList = array();
            while($row = $result->fetch_assoc()) {
                $serviceReservationDM = new ServiceReservationDM();
                $serviceReservationDM->set_reservationID($row["id"]);
                $serviceReservationDM->set_serviceID($row["service_id"]);
				$serviceReservationDM->set_name($row["name"]);
				$serviceReservationDM->set_date($row["date"]);
                $serviceReservationDM->set_time($row["time"]);
				$serviceReservationDM->set_price($row["reservation_price"]);
                $serviceReservationDM->set_status($row["reservation_status"]);
				$serviceReservationDM->set_image($row["receipt"]);
				$serviceReservationDM->set_duration($row["duration_hours"]);

				$packageDM = new PackageDM();
				$packageDM->set_packageName($row["package_name"]);
				$serviceReservationDM->set_packageInformation($packageDM);

                array_push($reservationList, $serviceReservationDM);
            }
			return json_encode(array("statusCode"=>200, "reservationList"=>$reservationList));
        }
		return json_encode(array("statusCode"=>404));
	}

	function pay(){
		$id = $_POST['id'];
        $sql = "UPDATE service_reservation
		SET status = '3'
		WHERE id = '$id'";

		if (mysqli_query($this->conn, $sql)) {
			return json_encode(array("statusCode"=>200));	
		} else {
			return json_encode(array("statusCode"=>500));
		}
	}

	function createService(){
		$name=$_POST['name'];
		$packageIDs= explode(',', $_POST['package-ids']);
		$description=$_POST['description'];
		$duration=$_POST['duration'];
		$price=$_POST['price'];
		$status="1";

		$img_path = "";
		if($_FILES != null){
			$fileName = "";
			$imageName = rand(0,1000) . time();
			$imageName = str_replace(":","",$imageName);
			$imageName = str_replace("-","",$imageName);
			$target_dir = "./../../../../web/resources/images/";
			$fileName = $this->modifyImageName($imageName);
			$location = $target_dir . $fileName;
			$uploadStatus = $this->uploadImage($location);
			if($uploadStatus != 200) {
				return json_encode(array("statusCode"=>$uploadStatus));
			} 
			$img_path = "web/resources/images/{$fileName}";
		}

		$sql = "INSERT INTO 
				`service`(
				  `name`, 
				  `description`, 
				  `duration_hours`, 
				  `image`,
				  `status`,
				  `price`) 
				VALUES (
				  '$name',
				  '$description',
				  '$duration',
				  '$img_path',
				  '$status',
				  '$price'
				)";

		if (mysqli_query($this->conn, $sql)) {
			$last_id = $this->conn->insert_id;
			foreach ($packageIDs as $value) {
				$sql = "INSERT INTO service_package (service_id, package_id) VALUES ('$last_id', '$value')";
				mysqli_query($this->conn, $sql);
			}
			return json_encode(array("statusCode"=>200, "serviceID"=>$last_id));
		} else {
			return json_encode(array("statusCode"=>500));
		}
	}

	function updateService(){
		$serviceID=$_POST['id'];
		$name=$_POST['name'];
		$description=$_POST['description'];
		$duration=$_POST['duration'];
		$status=$_POST['status'];
		$price=$_POST['price'];	
		$packageIDs= explode(',', $_POST['package-ids']);

		$statusCode = array();
		$img_path = "";
		
		if($_FILES != null){
			$fileName = "";
			$imageName = rand(0,1000) . time();
			$imageName = str_replace(":","",$imageName);
			$imageName = str_replace("-","",$imageName);
			$target_dir = "./../../../../web/resources/images/";
			$fileName = $this->modifyImageName($imageName);
			$location = $target_dir . $fileName;
			$uploadStatus = $this->uploadImage($location);
			if($uploadStatus != 200) {
				return json_encode(array("statusCode"=>$uploadStatus));
			} 
			array_push($statusCode, $uploadStatus); 
			$img_path = "web/resources/images/{$fileName}";
		}

		$sql = "UPDATE `service` SET
			`name`='$name',
			`description`='$description',
			`price`='$price',
			`duration_hours`='$duration',
			`status`='$status'
			WHERE service_id=$serviceID";
		
		if($img_path != ""){
			$sql = "UPDATE `service` SET
			`name`='$name',
			`description`='$description',
			`price`='$price',
			`duration_hours`='$duration',
			`image`='$img_path',
			`status`='$status'
			WHERE service_id=$serviceID";
		} 

		if (mysqli_query($this->conn, $sql)) {
			array_push($statusCode, 200);
			$sql = "DELETE FROM `service_package` WHERE service_id = '$serviceID'";
			mysqli_query($this->conn, $sql);
			foreach ($packageIDs as $value) {
				if($value != null && $value != ""){
					$sql = "INSERT INTO service_package (service_id, package_id) VALUES ('$serviceID', '$value')";
					mysqli_query($this->conn, $sql);
				}
			}
			
			return json_encode(array("statusCode"=>200));
		} else {
			return json_encode(array("statusCode"=>200));
		}
	}

	function deleteService(){
		$id = $_POST['id'];
		$sql = "DELETE from service where service_id='$id'";
		if (mysqli_query($this->conn, $sql)) {
			return json_encode(array("statusCode"=>200));
		} else {
			return json_encode(array("statusCode"=>500));
		}
	}

	function serviceReservation(){
		$serviceID=$_POST['serviceID'];
		$packageID=$_POST['packageID'];
		$date=$_POST['date'];
		$time=$_POST['time'];
		$accountID=$_POST['accountID'];
		$status = "1";
		$price=$_POST['price'];

		$statusCode = array();
		
		$sql = "INSERT INTO 
		  `service_reservation`( 
			`service_id`,
			`package_id`, 
			`date`, 
			`time`,
			`account_id`, 
			`status`,
			`price`) 
		  VALUES (
			'$serviceID',
			'$packageID',
			'$date',
			'$time',
			'$accountID',
			'$status',
			'$price')";

		if (mysqli_query($this->conn, $sql)) {
			$last_id = $this->conn->insert_id;
			return json_encode(array("statusCode"=>200, "serviceReservationID"=>$last_id));
		} else {
			array_push($statusCode, 500);
		}
		return json_encode(array("statusCode"=>$statusCode));
	}

	function modifyImageName($customName){
		$name = explode('.', $_FILES['file']['name']);
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

	function uploadServicePayment(){
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
        $sql = "UPDATE service_reservation
		SET status = '4', receipt = '$img_path'
		WHERE id = '$id'";

		if (mysqli_query($this->conn, $sql)) {
			return json_encode(array("statusCode"=>"200"));
		} else {
			return json_encode(array("statusCode"=>"500"));
		}
	}

	function updateServiceStatus(){
		$id = $_POST['id'];
		$status = $_POST['status'];
        $sql = "UPDATE service_reservation
		SET status = '$status'
		WHERE id = '$id'";

		if (mysqli_query($this->conn, $sql)) {
			return json_encode(array("statusCode"=>"200"));
		} else {
			return json_encode(array("statusCode"=>"500"));		
		}
	}

	function modifyImageNameRefund($customName){
		$name = explode('.', $_FILES['refund']['name']);
		return $customName . (count($name) > 1 ? '.' . $name[1] : '');
	}

	function updateServiceReservation(){
		$id = $_POST['id'];
		$status = $_POST['status'];
		$date = $_POST['date'];
		$time = $_POST['time'];
		$price = $_POST['price'];
	
		// Additional Info and Reason for Cancellation
		$additionalInfo = !empty($_POST['additional_info']) ? $_POST['additional_info'] : null;
		$reasonForCancellation = !empty($_POST['reasonForCancellation']) ? $_POST['reasonForCancellation'] : null;
	
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
	
		// Use prepared statements to prevent SQL injection
		$sql = "UPDATE service_reservation SET 
			`status` = ?,
			`date` = ?,
			`time` = ?,
			`price` = ?,
			`additionalInfo` = ?,
			`cancelledReason` = ?,
			`cancelledRefund` = ?
			WHERE id = ?";
	
		$stmt = $this->conn->prepare($sql);
	
		if ($stmt) {
			$bindParams = [
				$status, $date, $time, $price, $additionalInfo,
				$reasonForCancellation, $fileName, $id
			];
	
			$types = str_repeat('s', count($bindParams));
			$stmt->bind_param($types, ...$bindParams);
	
			// Execute the statement
			if ($stmt->execute()) {
				$stmt->close();
				return json_encode(array("statusCode" => "200", "fileName" => $fileName, "reasonForCancellation" => $reasonForCancellation));
			} else {
				$stmt->close();
				return json_encode(array("statusCode" => "500", "error" => $stmt->error));
			}
		} else {
			return json_encode(array("statusCode" => "500"));
		}
	}
	
}

$services = new Services();
if(isset($_GET['action'])){
	switch ($_GET['action']) {
		case 'retrieve-service-list':
			echo $services->retrieveServiceList();
			break;
		case 'retrieve-all-service-list':
			echo $services->retrieveAllServiceList();
			break;
		case 'retrieve-service-reservation-list':
			echo $services->retrieveServiceReservationList();
			break;
		case 'retrieve-user-service-reservation-list':
			echo $services->retrieveUserServiceReservationList();
			break;
		case 'retrieve-archive-service':
			echo $services->retrieveArchiveService();
			break;
		default:
			break;
	}
}

if(isset($_POST['action'])){
	switch ($_POST['action']) {
		case 'service-reservation':
			echo $services->serviceReservation();
			break;
		case 'create-service':
			echo $services->createService();
			break;
		case 'update-service':
			echo $services->updateService();
			break;
		case 'delete-service':
			echo $services->deleteService();
			break;
		case 'pay':
			echo $services->pay();
			break;
		case 'upload-service-payment':
			echo $services->uploadServicePayment();
			break;
		case 'update-service-status':
			echo $services->updateServiceStatus();
			break;
		case 'update-service-reservation':
			echo $services->updateServiceReservation();
			break;
		default:
			break;
	}
}

?>