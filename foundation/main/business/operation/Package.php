<?php
require_once('../../configuration/Configuration.php');
require_once('../model/BasicDM.php');
require_once('../model/PackageDM.php');
// require_once('../model/AccountDM.php');
class Package extends DatabaseConfiguration {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}

	public function __destruct(){
		parent::__destruct();
	}

	public function retrieveServicePackage(){
		$serviceID = $_GET['service_id'];
        $sql = "SELECT * FROM service_package INNER JOIN package ON service_package.package_id = package.id WHERE service_package.service_id = '$serviceID' AND package.status = '1'";
        $result = $this->conn->query($sql);
        $packageList = array();
		if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $basicDM = new BasicDM();
                $basicDM->set_id($row["package_id"]);
                $basicDM->set_name($row["package_name"]);
                $basicDM->set_description($row["package_description"]);
				$basicDM->set_price($row["package_price"]);
				array_push($packageList, $basicDM);
            }
        }
		return json_encode(array("statusCode"=>200, "packageList"=>$packageList));
	}

	public function retrievePackageList(){
        $sql = "SELECT * FROM package where status='1'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $packageList = array();
            while($row = $result->fetch_assoc()) {
                $basicDM = new BasicDM();
                $basicDM->set_id($row["id"]);
                $basicDM->set_name($row["name"]);
                $basicDM->set_description($row["description"]);
				// $basicDM->set_image($row["image"]);
				$basicDM->set_price($row["price"]);
				$basicDM->set_time($row["duration_hours"]);
				$basicDM->set_status($row["status"]);
                array_push($packageList, $basicDM);
            }
        }
        $this->conn->close();
		return json_encode(array("statusCode"=>200, "packageList"=>$packageList));
	}

	public function retrieveAllPackageList(){
        $sql = "SELECT * FROM package";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $packageList = array();
            while($row = $result->fetch_assoc()) {
                $basicDM = new BasicDM();
                $basicDM->set_id($row["id"]);
                $basicDM->set_name($row["package_name"]);
                $basicDM->set_description($row["package_description"]);
				$basicDM->set_price($row["package_price"]);
				$basicDM->set_status($row["status"]);
				$basicDM->set_time($row["duration"]);
                array_push($packageList, $basicDM);
            }
        }
	   return json_encode(array("statusCode"=>200, "packageList"=>$packageList));
	}

	function createPackage(){
		$name=$_POST['name'];
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
				`package`(
				  `package_name`, 
				  `package_description`, 
				  `duration`, 
				  `status`,
				  `package_price`) 
				VALUES (
				  '$name',
				  '$description',
				  '$duration',
				  '$status',
				  '$price'
				)";

		if (mysqli_query($this->conn, $sql)) {
			$last_id = $this->conn->insert_id;
			return json_encode(array("statusCode"=>200, "packageID"=>$last_id));
		} else {
			return json_encode(array("statusCode"=>500));
		}
	}

	function updatePackage(){
		$packageID=$_POST['id'];
		$name=$_POST['name'];
		$description=$_POST['description'];
		$duration=$_POST['duration'];
		$status=$_POST['status'];
		$price=$_POST['price'];	

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

		$sql = "UPDATE `package` SET
			`package_name`='$name',
			`package_description`='$description',
			`package_price`='$price',
			`duration`='$duration',
			`status`='$status'
			WHERE id=$packageID";
		
		if($img_path != ""){
			$sql = "UPDATE `package` SET
			`package_name`='$name',
			`package_description`='$description',
			`package_price`='$price',
			`duration`='$duration',
			`status`='$status'
			WHERE id=$packageID";
		}

		if (mysqli_query($this->conn, $sql)) {
			array_push($statusCode, 200);
			return json_encode(array("statusCode"=>200));
		} else {
			return json_encode(array("statusCode"=>200));
		}
	}

	function deletePackage(){
		$id = $_POST['id'];
		$sql = "DELETE from package where id='$id'";
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
}

$package = new Package();
if(isset($_GET['action'])){
	switch ($_GET['action']) {
		case 'retrieve-package-list':
			echo $package->retrievePackageList();
			break;
		case 'retrieve-all-package-list':
			echo $package->retrieveAllPackageList();
			break;
		case 'retrieve-service-package':
			echo $package->retrieveServicePackage();
			break;
		default:
			break;
	}
}

if(isset($_POST['action'])){
	switch ($_POST['action']) {
		case 'create-package':
			echo $package->createPackage();
			break;
		case 'update-package':
			echo $package->updatePackage();
			break;
		case 'delete-package':
			echo $package->deletePackage();
			break;
		default:
			break;
	}
}

?>