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


?>