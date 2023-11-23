<?php
require_once('../../configuration/Configuration.php');
ini_set('memory_limit', '44M');
require_once('../model/ReferenceDM.php');
class Reference extends DatabaseConfiguration {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;

		parent::__construct();
		ini_set('display_error', 1);
	}
	public function __destruct(){
		parent::__destruct();
	}
	
	public function retrieveAddressReferenceData(){
		$provinceList = $this->retrieveReferenceDataProvince();
		$municipalityList = $this->retrieveReferenceDataMunicipality();
		$barangayList = $this->retrieveReferenceDataBarangay();

		return json_encode(array("statusCode"=>200, "provinceList"=>$provinceList, "municipalityList"=>$municipalityList, "barangayList"=>$barangayList));
	}

	public function retrieveReferenceDataProvince(){
		$sql = "SELECT * FROM reference_data_province";
		$result = $this->conn->query($sql);
		$referenceData = array();
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$referenceDM = new ReferenceDM();
				$referenceDM->set_name($row['provDesc']);
				$referenceDM->set_value($row['provCode']);
				array_push($referenceData, $referenceDM);
			}
		}
		return $referenceData;
	}

	public function retrieveReferenceDataMunicipality(){
		$sql = "SELECT * FROM reference_data_municipality";
		$result = $this->conn->query($sql);
		$referenceData = array();
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$referenceDM = new ReferenceDM();
				$referenceDM->set_name($row['citymunDesc']);
				$referenceDM->set_value($row['citymunCode']);
				$referenceDM->set_subReference($row['provCode']);
				array_push($referenceData, $referenceDM);
			}
		}
		return $referenceData;
	}

	public function retrieveReferenceDataBarangay(){
		$sql = "SELECT * FROM reference_data_barangay";
		$result = $this->conn->query($sql);
		$referenceData = array();
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$referenceDM = new ReferenceDM();
				$referenceDM->set_name($row['brgyDesc']);
				$referenceDM->set_value($row['brgyCode']);
				$referenceDM->set_subReference($row['citymunCode']);
				array_push($referenceData, $referenceDM);
			}
		}
		return $referenceData;
	}
}

$auth = new Reference();
if(isset($_GET['action'])){
	$action = $_GET['action'];
	switch ($action) {
		case 'retrieve-address-reference-data':
			echo $auth->retrieveAddressReferenceData();
			break;
		default:
			break;
	}
}
?>