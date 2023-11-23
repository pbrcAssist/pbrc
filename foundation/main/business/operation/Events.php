<?php
require_once('../../configuration/Configuration.php');
require_once('../model/EventDM.php');
class Events extends DatabaseConfiguration {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}

	public function __destruct(){
		parent::__destruct();
	}

	public function retrieveUpcomingEvents(){
		$sql = "SELECT * FROM event WHERE status = 1 AND date(date) >= '".date("Y-m-d")."' order by date(date) ASC";
        $result = $this->conn->query($sql);
        $eventList = array();
		if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $eventDM = new EventDM();
                $eventDM->set_id($row["id"]);
                $eventDM->set_name($row["title"]);
                $eventDM->set_description($row["description"]);
				$eventDM->set_date($row["date"]);
				$eventDM->set_time($row["time"]);
				$eventDM->set_image($row["img_path"]);
				$eventDM->set_status($row["status"]);
                array_push($eventList, $eventDM);
            }
        }
        return json_encode(array("statusCode"=>200, "eventList"=>$eventList));
        $this->conn->close();
	}

	public function retrieveEvents(){
		$sql = "SELECT * FROM event ORDER BY date(date) ASC";
        $result = $this->conn->query($sql);
        $eventList = array();
		if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $eventDM = new EventDM();
                $eventDM->set_id($row["id"]);
                $eventDM->set_name($row["title"]);
                $eventDM->set_description($row["description"]);
				$eventDM->set_date($row["date"]);
				$eventDM->set_time($row["time"]);
				$eventDM->set_image($row["img_path"]);
				$eventDM->set_status($row["status"]);
                array_push($eventList, $eventDM);
            }
        }
        return json_encode(array("statusCode"=>200, "eventList"=>$eventList));
        $this->conn->close();
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

	function createEvent(){
		$date = $_POST['date'];
		$time = $_POST['time'];
		$title = $_POST['title'];
		$description = $_POST['description'];
		$imageName = rand(0,1000) . $date . $time;
		$imageName = str_replace(":","",$imageName);
		$imageName = str_replace("-","",$imageName);
		$status = "1";
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
		
		$sql = "INSERT INTO 
		  `event`( 
			`title`, 
			`description`, 
			`date`,
			`time`, 
			`img_path`,
			`status`) 
		  VALUES (
			'$title',
			'$description',
			'$date',
			'$time',
			'$img_path',
			'$status'
			)";

		if (mysqli_query($this->conn, $sql)) {
			$last_id = $this->conn->insert_id;
			array_push($statusCode, 200);
			return json_encode(array("statusCode"=>200, "eventID"=>$last_id));
		} else {
			array_push($statusCode, 5004);
		}
		return json_encode(array("statusCode"=>$statusCode));
	}

	function updateEvent(){
		$id = $_POST['id'];
		$date = $_POST['date'];
		$time = $_POST['time'];
		$title = $_POST['title'];
		$description = $_POST['description'];
		$status = $_POST['status'];
		$statusCode = array();
		$img_path = "";
		
		if($_FILES != null){
			$fileName = "";
			$imageName = rand(0,1000) . $date . $time;
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

		$sql = "UPDATE `event` SET
			`title`='$title',
			`description`='$description',
			`date`='$date',
			`status`='$status',
			`time`='$time'
			WHERE id=$id";
		if($img_path != ""){
			$sql = "UPDATE `event` SET
			`title`='$title',
			`description`='$description',
			`date`='$date',
			`time`='$time',
			`status`='$status',
			`img_path`='$img_path'
			WHERE id=$id";	
		}

		if (mysqli_query($this->conn, $sql)) {
			array_push($statusCode, 200);
			return json_encode(array("statusCode"=>200));
		} else {
			array_push($statusCode, 5004);
		}
		return json_encode(array("statusCode"=>$statusCode));
	}

	function deleteEvent(){
		$id = $_POST['id'];
		$sql = "DELETE from event where id='$id'";
		if (mysqli_query($this->conn, $sql)) {
			return json_encode(array("statusCode"=>200));
		} else {
			return json_encode(array("statusCode"=>500));
		}
	}
}

$events = new Events();

if(isset($_GET['action'])){
	switch ($_GET['action']) {
		case 'retrieve-upcoming-event':
			echo $events->retrieveUpcomingEvents();
			break;
		case 'retrieve-events':
			echo $events->retrieveEvents();
			break;
		default:
			break;
	}
}

if(isset($_POST['action'])){
	switch ($_POST['action']) {
		case 'create-event':
			echo $events->createEvent();
			break;
		case 'update-event':
			echo $events->updateEvent();
			break;
		case 'delete-event':
			echo $events->deleteEvent();
			break;
		default:
			break;
	}
}

?>