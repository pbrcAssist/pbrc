<?php

require_once('../../configuration/Configuration.php');
require_once('../model/BasicDM.php');
class Gallery extends DatabaseConfiguration {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}

	public function __destruct(){
		parent::__destruct();
	}

	public function retrieveGallery(){
        $sql = "SELECT * FROM gallery";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $gallery = array();
            while($row = $result->fetch_assoc()) {
                array_push($gallery, populateBasicDM($row["id"],$row["name"],$row["description"],$row["image_path"],"1"));
            }
        }
        return json_encode(array("statusCode"=>200, "gallery"=>$gallery));
        $this->conn->close();
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

	  function modifyImageName($customName){
		$name = explode('.', $_FILES['file']['name']);
		return $customName . (count($name) > 1 ? '.' . $name[1] : '');
	}

	function updateGallery(){
		$galleryID = $_POST['id'];
		$name = $_POST['name'];
		$description = $_POST['description'];
		$statusCode = array();
		$img_path = "";
	
		if ($_FILES != null) {
			$fileName = "";
			$imageName = rand(0, 1000) . time();
			$imageName = str_replace(":", "", $imageName);
			$imageName = str_replace("-", "", $imageName);
			$target_dir = "./../../../../web/resources/images/";
			$fileName = $this->modifyImageName($imageName);
			$location = $target_dir . $fileName;
			$uploadStatus = $this->uploadImage($location);
			if ($uploadStatus != 200) {
				return json_encode(array("statusCode" => $uploadStatus));
			}
			array_push($statusCode, $uploadStatus);
			$img_path = "web/resources/images/{$fileName}";
		}
	
		// Using prepared statements to handle user input
		$sql = "UPDATE `gallery` SET
			`name`=?,
			`description`=?
			WHERE id=?";
	
		if ($img_path != "") {
			$sql = "UPDATE `gallery` SET
				`name`=?,
				`description`=?,
				`image_path`=?
				WHERE id=?";
		}
	
		$stmt = mysqli_prepare($this->conn, $sql);
	
		if ($stmt) {
			if ($img_path != "") {
				mysqli_stmt_bind_param($stmt, "sssi", $name, $description, $img_path, $galleryID);
			} else {
				mysqli_stmt_bind_param($stmt, "ssi", $name, $description, $galleryID);
			}
	
			if (mysqli_stmt_execute($stmt)) {
				array_push($statusCode, 200);
				return json_encode(array("statusCode" => 200));
			} else {
				array_push($statusCode, 500);  // Use a different status code for database errors
				return json_encode(array("statusCode" => 500));
			}
		} else {
			array_push($statusCode, 500);  // Use a different status code for database errors
			return json_encode(array("statusCode" => 500));
		}
	}

	function saveGallery(){
		$name = $_POST['name'];
		$description = $_POST['description'];
		$statusCode = array();
		$img_path = "";
	
		if ($_FILES != null) {
			$fileName = "";
			$imageName = rand(0, 1000) . time();
			$imageName = str_replace(":", "", $imageName);
			$imageName = str_replace("-", "", $imageName);
			$target_dir = "./../../../../web/resources/images/";
			$fileName = $this->modifyImageName($imageName);
			$location = $target_dir . $fileName;
			$uploadStatus = $this->uploadImage($location);
			if ($uploadStatus != 200) {
				return json_encode(array("statusCode" => $uploadStatus));
			}
			array_push($statusCode, $uploadStatus);
			$img_path = "web/resources/images/{$fileName}";
		}
	
		// Using prepared statements to handle user input
		$sql = "INSERT INTO `gallery` (`name`, `description`, `image_path`) VALUES (?, ?, ?)";
	
		$stmt = mysqli_prepare($this->conn, $sql);
	
		if ($stmt) {
			mysqli_stmt_bind_param($stmt, "sss", $name, $description, $img_path);
	
			if (mysqli_stmt_execute($stmt)) {
				array_push($statusCode, 200);
				return json_encode(array("statusCode" => 200));
			} else {
				array_push($statusCode, 500);  // Use a different status code for database errors
				return json_encode(array("statusCode" => 500));
			}
		} else {
			array_push($statusCode, 500);  // Use a different status code for database errors
			return json_encode(array("statusCode" => 500));
		}
	}
	
	function deleteGallery(){
		$id = $_POST['id'];
		$sql = "DELETE from gallery where id='$id'";
		if (mysqli_query($this->conn, $sql)) {
			return json_encode(array("statusCode"=>200));
		} else {
			return json_encode(array("statusCode"=>500));
		}
	}
}



$gallery = new Gallery();
if(isset($_GET['action'])){
	switch ($_GET['action']) {
		case 'retrieve-gallery':
			echo $gallery->retrieveGallery();
			break;
		default:
			break;
	}
}

if(isset($_POST['action'])){
	switch ($_POST['action']) {
		case 'update-gallery':
			echo $gallery->updateGallery();
			break;
		case 'save-gallery':
			echo $gallery->saveGallery();
			break;
		case 'delete-gallery':
			echo $gallery->deleteGallery();
			break;
		default:
			break;
	}
}


?>