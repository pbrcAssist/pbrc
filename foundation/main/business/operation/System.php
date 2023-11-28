<?php
// Eto yung tinatawag ng settings 
require_once('../../configuration/Configuration.php');
require_once('../model/ContactDM.php');
require_once('../model/WebsiteDM.php');
require_once('../model/KeyValueDM.php');

class System extends DatabaseConfiguration {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}

	public function __destruct(){
		parent::__destruct();
	}

	public function retrieveSystemContactInfo(){
        $sql = "SELECT * FROM system_information";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $contactNumberList = array();
			$locationList = array();
			$emailList = array();
            while($row = $result->fetch_assoc()) {
				$value = $row["meta_value"];
				switch($row['meta_field']){
					case "contact":
						array_push($contactNumberList, $value);
						break;
					case "address":
						array_push($locationList, $value);
						break;
					case "email":
						array_push($emailList, $value);
						break;
				}
            }
			$contactDM = new contactDM();
			$contactDM->set_contactNumber($contactNumberList);
			$contactDM->set_location($locationList);
			$contactDM->set_emailAddress($emailList);
        }
        return json_encode(array("statusCode"=>200, "contactDetail"=>$contactDM));
        $this->conn->close();
	}

	public function retrieveWebsiteInfo(){
        // Will action sa database
		$sql = "SELECT * FROM system_information";

		// Execute query
        $result = $this->conn->query($sql);

		$data = array();

		// Data masaging
        if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				array_push($data, populateKeyValue($row['meta_field'], $row['meta_value'], $row['meta_type']));
			}
        }
        return json_encode(array("statusCode"=>200, "websiteInfo"=>$data));
        $this->conn->close();

	}

	public function updatePayment(){
		$number = $_POST['gcash-number'];
		$name = $_POST['gcash-name'];
		
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

		$sql = "UPDATE `system_information` SET
			`meta_value`='$number'
			WHERE meta_field='#gcash-number'";

		if (mysqli_query($this->conn, $sql)) {
			array_push($statusCode, 200);
		} else {
			array_push($statusCode, 5001);
		}

		$sql = "UPDATE `system_information` SET
			`meta_value`='$name'
			WHERE meta_field='#gcash-name'";

		if (mysqli_query($this->conn, $sql)) {
			array_push($statusCode, 200);
		} else {
			array_push($statusCode, 5001);
		}
		
		if($img_path != ""){
			$sql = "UPDATE `system_information` SET
			`meta_value`='$img_path'
			WHERE meta_field='#gcash-qr'";

			if (mysqli_query($this->conn, $sql)) {
				array_push($statusCode, 200);
			} else {
				array_push($statusCode, 5004);
			}
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

	function updateSystem($key, $value) {
		$sql = "UPDATE `system_information` SET `meta_value` = ? WHERE `meta_field` = ?";
		
		$stmt = mysqli_prepare($this->conn, $sql);
	
		if ($stmt) {
			mysqli_stmt_bind_param($stmt, "ss", $value, $key);
			
			if (mysqli_stmt_execute($stmt)) {
				return 200; // Success
			} else {
				return 500; // Internal Server Error
			}
	
			mysqli_stmt_close($stmt);
		} else {
			return 500; // Internal Server Error
		}
	}

	function updateSystemInfo(){
		$logoImagePath = "";
		$loginImagePath = "";
		$coverImagePath = "";
		$aboutUsImagePath = "";

		$websiteName = $_POST['website-name'];
		$this->updateSystem(".pbrc-name", $websiteName);

		$websiteShortName = $_POST['website-short-name'];
		$this->updateSystem(".pbrc-short-name", $websiteShortName);

		$websiteTagline = $_POST['website-tagline'];
		$this->updateSystem("#website-tagline", $websiteTagline);

		$websiteAboutUs = $_POST['website-about-us'];
		$this->updateSystem("#website-about-us-description", $websiteAboutUs);

		$phone = $_POST['phone'];
		$this->updateSystem(".pbrc-phone", $phone);

		$email = $_POST['email'];
		$this->updateSystem(".pbrc-email", $email);

		$address = $_POST['address'];
		$this->updateSystem(".pbrc-address", $address);

		$startDay = $_POST['start-day'];
		$this->updateSystem(".pbrc-operating-day-start", $startDay);

		$startTime = $_POST['start-time'];
		$this->updateSystem(".pbrc-operating-hour-start", $startTime);

		$endDay = $_POST['end-day'];

		$this->updateSystem(".pbrc-operating-day-end", $endDay);

		$endTime = $_POST['end-time'];
		$this->updateSystem(".pbrc-operating-hour-end", $endTime);

		$uploadedFiles = [];

		$uploadDir = "./../../../../web/resources/images/";

		foreach ($_FILES as $inputId => $fileInput) {
			// Check if $fileInput['name'] is an array
			if (is_array($fileInput['name'])) {
				// Iterate over each file in the array
				for ($i = 0; $i < count($fileInput['name']); $i++) {
					$tmp_name = $fileInput['tmp_name'][$i];
					$name = basename($fileInput['name'][$i]);
					$destination = $uploadDir . $name;
	
					if (move_uploaded_file($tmp_name, $destination)) {
						$uploadedFiles[$inputId][] = $destination;
						$path = "web/resources/images/{$name}";
						echo "THIS IS FIRST - " . $path;
						switch ($inputId) {
							case "pbrc-logo-file":
								$logoImagePath = $path;
								break;
							case "website-login-image-file":
								$loginImagePath = $path;
								break;
						
							case "website-cover-file":
								$coverImagePath = $path;
								break;
						
							case "website-about-us-image-file":
								$aboutUsImagePath = $path;
								break;
						}
					} else {
						$uploadedFiles[$inputId][] = "Failed to move $name to $uploadDir";
					}
				}
			} else {
				// Handle a single file
				$tmp_name = $fileInput['tmp_name'];
				$name = basename($fileInput['name']);
				$destination = $uploadDir . $name;
	
				if (move_uploaded_file($tmp_name, $destination)) {
					$uploadedFiles[$inputId][] = $destination;
					$path = "web/resources/images/{$name}";
					echo "THIS IS Second - " . $path;
						switch ($inputId) {
							case "pbrc-logo-file":
								$logoImagePath = $path;
								break;
							case "website-login-image-file":
								$loginImagePath = $path;
								break;
						
							case "website-cover-file":
								$coverImagePath = $path;
								break;
						
							case "website-about-us-image-file":
								$aboutUsImagePath = $path;
								break;
						}
				} else {
					$uploadedFiles[$inputId][] = "Failed to move $name to $uploadDir";
				}
			}
		}
		

		if($logoImagePath != ""){
			$this->updateSystem("#pbrc-logo", $logoImagePath);
		}

		if($loginImagePath != ""){
			$this->updateSystem("#website-login-image", $loginImagePath);
		}

		if($coverImagePath != ""){
			$this->updateSystem("#website-cover", $coverImagePath);
		}

		if($aboutUsImagePath != ""){
			$this->updateSystem("#website-about-us-image", $aboutUsImagePath);
		}
		return json_encode(array("statusCode"=>200));
	}
}

$system = new System();
if(isset($_GET['action'])){
	switch ($_GET['action']) {
		case 'retrieve-contact-info':
			echo $system->retrieveSystemContactInfo();
			break;
		case 'retrieve-website-info':
			echo $system->retrieveWebsiteInfo();
			break;
		default:
			break;
	}
}

if(isset($_POST['action'])){
	switch ($_POST['action']) {
		case 'update-payment':
			echo $system->updatePayment();
			break;
		case 'update-system-info':
			echo $system->updateSystemInfo();
			break;
		default:
			break;
	}
}