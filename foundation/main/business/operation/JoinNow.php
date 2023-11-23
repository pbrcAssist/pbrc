<?php
// Join Now, save application
require_once('../../configuration/Configuration.php');
require_once('../model/AccountDM.php');
class JoinNow extends DatabaseConfiguration {
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
	

	function joinNow(){
		$name=$_POST['name'];
		$address=$_POST['address'];
		$email=$_POST['email'];
		$phone=$_POST['phone'];
		$birthDate=$_POST['birthDate'];
		$status='1';
		$statusCode = array();

		$sql = "INSERT INTO 
		  `applicant`( 
			`name`, 
			`address`, 
			`phone`,
			`email`, 
			`birthdate`,
			`status`) 
		  VALUES (
			'$name',
			'$address',
			'$email',
			'$phone',
			'$birthDate',
			'$status')";

		if (mysqli_query($this->conn, $sql)) {
			return json_encode(array("statusCode"=>200));			
		}
		return json_encode(array("statusCode"=>500));
	}

	public function retrieveEmailAddress(){
		$email = $_GET['email'];
		$statusCode = array();
		$existing=mysqli_query($this->conn,"SELECT * from account_information where email='$email'");
		if (mysqli_num_rows($existing)==0){
			return json_encode(array("statusCode"=>5006));
		} else {
			$accountID = '';
			while($row = $existing->fetch_assoc()) {
				$accountID = $row['account_id'];
			}
			return json_encode(array("statusCode"=>200, "accountID"=>$accountID));
		}
	}


}
$action = !isset($_POST['action']) ? 'none' : strtolower($_POST['action']);
$auth = new JoinNow();

if(isset($_POST['action'])){
	switch ($action) {
		case 'join-now':
			echo $auth->joinNow();
			break;
		default:
			break;
	}
}

if(isset($_GET['action'])){
	$action = $_GET['action'];
	switch ($action) {
		case 'login':
			echo $auth->login();
			break;
		case 'logout-user':
			echo $auth->logoutUser();
			break;
		case 'retrieve-user-information':
			echo $auth->retrieveUserInformation();
			break;
		case 'retrieve-admin-information':
			echo $auth->retrieveAdminInformation();
			break;
		case 'retrieve-email-address':
			echo $auth->retrieveEmailAddress();
			break;
		case 'change-password':
			echo $auth->changePassword();
			break;
		case 'create-account':
			echo $auth->createAccount();
			break;
		case 'email-check':
			echo $auth->emailCheck();
			break;
		default:
			break;
	}
}
?>