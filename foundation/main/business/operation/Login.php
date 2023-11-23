<?php

// Create Account, Update Account, Update Password, Delete Account, Retrieve Account, Login

require_once('../../configuration/Configuration.php');
require_once('../model/AccountDM.php');
class Login extends DatabaseConfiguration {
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
	
	public function login(){
		$type = $_POST["type"];
		$password = md5($_POST["password"]);

		if($type == '1'){
			$username = $_POST["username"];
			$sql = "SELECT * FROM account_information WHERE username = '$username' and password = '$password' AND account_type='$type' AND status='1'";
		} else if ($type == '2'){
			$email = $_POST["email"];
			$sql = "SELECT * FROM account_information WHERE email = '$email' and password = '$password' AND account_type='$type' AND status='1'";
		}
		
		$result = $this->conn->query($sql);
		
		if ($result->num_rows > 0) {
			
			while($row = $result->fetch_assoc()) {
			  if($row["status"] == 0){
				return json_encode(array("statusCode"=>5000));
			  } else {
				$accountDM = new AccountDM();
				$accountDM->set_id($row['account_id']);
				$accountDM->set_email($row['email']);
				$accountDM->set_username($row['username']);
				$accountDM->set_phone($row['phone']);
				$accountDM->set_firstName($row['first_name']);
				$accountDM->set_middleName($row['middle_name']);
				$accountDM->set_lastName($row['last_name']);
				$accountDM->set_province($row['province']);
				$accountDM->set_municipality($row['municipality']);
				$accountDM->set_barangay($row['barangay']);
				$accountDM->set_status($row['status']);
				$accountDM->set_image($row['picture']);
				$accountDM->set_type($row['account_type']);
				if($row["account_type"] == 2){
					$_SESSION['user_detail'] = $accountDM;
				} else {
					$_SESSION['admin_detail'] = $accountDM;
					$_SESSION['admin_id'] = $row['account_id'];
				}

				return json_encode(array("statusCode"=>200, "accountDetail"=>$accountDM));
			  }
			}
		  } else {
			return json_encode(array("statusCode"=>201));
		  }	
	}

	public function retrieveUserList(){
		$sql = "SELECT * FROM account_information WHERE account_type='1'";
		$result = $this->conn->query($sql);
		$userList = array();
		if ($result->num_rows > 0) {
			
			while($row = $result->fetch_assoc()) {
			  if($row["status"] == 0){
				return json_encode(array("statusCode"=>5000));
			  } else {
				$accountDM = new AccountDM();
				$accountDM->set_id($row['account_id']);
				$accountDM->set_email($row['email']);
				$accountDM->set_phone($row['phone']);
				$accountDM->set_username($row['username']);
				$accountDM->set_firstName($row['first_name']);
				$accountDM->set_middleName($row['middle_name']);
				$accountDM->set_lastName($row['last_name']);
				$accountDM->set_province($row['province']);
				$accountDM->set_municipality($row['municipality']);
				$accountDM->set_barangay($row['barangay']);
				$accountDM->set_status($row['status']);
				$accountDM->set_image($row['picture']);
				$accountDM->set_type($row['account_type']);
				array_push($userList, $accountDM);
			  }
			}
			return json_encode(array("statusCode"=>200, "userList"=>$userList));
		  } else {
			return json_encode(array("statusCode"=>201));
		  }	
	}

	public function retrieveApplicantList(){
		$sql = "SELECT * FROM applicant WHERE status='1'";
		$result = $this->conn->query($sql);
		$userList = array();
		if ($result->num_rows > 0) {
			
			while($row = $result->fetch_assoc()) {
			  if($row["status"] == 0){
				return json_encode(array("statusCode"=>5000));
			  } else {
				$accountDM = new AccountDM();
				$accountDM->set_id($row['id']);
				$accountDM->set_email($row['email']);
				$accountDM->set_phone($row['phone']);
				$accountDM->set_firstName($row['name']);
				$accountDM->set_middleName($row['name']);
				$accountDM->set_lastName($row['name']);
				$accountDM->set_province($row['address']);
				$accountDM->set_municipality($row['address']);
				$accountDM->set_barangay($row['address']);
				$accountDM->set_status($row['status']);
				array_push($userList, $accountDM);
			  }
			}
			return json_encode(array("statusCode"=>200, "userList"=>$userList));
		  } else {
			return json_encode(array("statusCode"=>201));
		  }	
	}

	function isEmailExist($email, $id){
		$sql = "SELECT email FROM account_information WHERE email = '$email'";
		if($id != ""){
			$sql = "SELECT email FROM account_information WHERE account_id != '$id' AND email = '$email'";
		}

		$result = $this->conn->query($sql);
		if ($result->num_rows > 0) {
			return true;
		}
		return false;
	}

	function isUsernameExist($username, $id){
		$sql = "SELECT username FROM account_information WHERE username = '$username'";

		if($id != ""){
			$sql = "SELECT username FROM account_information WHERE account_id != '$id' AND username = '$username'";
		}
		$result = $this->conn->query($sql);
		if ($result->num_rows > 0) {
			return true;
		}
		return false;
	}

	function emailCheck(){
		$email = $_GET['email'];
		if($this->isEmailExist($email, "")){
			return json_encode(array("statusCode"=>5001));
		}
		return json_encode(array("statusCode"=>200));
	}


	function createAccount(){
		$firstName=$_POST['firstName'];
		$middleName=$_POST['middleName'];
		$lastName=$_POST['lastName'];

		$email=$_POST['email'];
		$phone=$_POST['phone'];

		$province=$_POST['province'];
		$municipality=$_POST['municipality'];
		$barangay=$_POST['barangay'];

		$password=md5($_POST['password']);

		$accountType = "2";
		$status = "1";

		$statusCode = array();

		if($this->isEmailExist($email, "")){
			return json_encode(array("statusCode"=>5001));
		}
		
		$sql = "INSERT INTO 
		  `account_information`( 
			`first_name`, 
			`middle_name`, 
			`last_name`,
			`account_type`, 
			`email`, 
			`phone`, 
			`province`,
			`municipality`, 
			`barangay`, 
			`password`,
			`status`) 
		  VALUES (
			'$firstName',
			'$middleName',
			'$lastName',
			'$accountType',
			'$email',
			'$phone',
			'$province', 
			'$municipality', 
			'$barangay', 
			'$password',
			'$status'
			)";

		if (mysqli_query($this->conn, $sql)) {
			$last_id = $this->conn->insert_id;
			$accountDM = new AccountDM();
			$accountDM->set_id($last_id);
			$accountDM->set_email($email);
			$accountDM->set_phone($phone);
			$accountDM->set_firstName($firstName);
			$accountDM->set_middleName($middleName);
			$accountDM->set_lastName($lastName);
			$accountDM->set_province($province);
			$accountDM->set_municipality($municipality);
			$accountDM->set_barangay($barangay);
			$accountDM->set_status($status);
			$accountDM->set_type($accountType);
			$_SESSION['user_detail'] = $accountDM;
			return json_encode(array("statusCode"=>200, "accountDetail"=>$accountDM));
		} else {
			array_push($statusCode, 5004);
		}
		return json_encode(array("statusCode"=>$statusCode));

	}

	function createAdminAccount(){
		$firstName=$_POST['firstName'];
		$middleName=$_POST['middleName'];
		$lastName=$_POST['lastName'];
		$email=$_POST['email'];
		$username=$_POST['username'];
		$password=md5($_POST['password']);
		$accountType = "1";
		$status = "1";

		$statusCode = array();

		if($this->isEmailExist($email, "")){
			return json_encode(array("statusCode"=>5001));
		}

		if($this->isUsernameExist($username, "")){
			return json_encode(array("statusCode"=>5002));
		}
		
		$sql = "INSERT INTO 
		  `account_information`( 
			`first_name`, 
			`middle_name`, 
			`last_name`,
			`account_type`, 
			`email`,
			`username`,  
			`password`,
			`status`) 
		  VALUES (
			'$firstName',
			'$middleName',
			'$lastName',
			'$accountType',
			'$email',
			'$username',
			'$password',
			'$status'
			)";

		if (mysqli_query($this->conn, $sql)) {
			$last_id = $this->conn->insert_id;
			$accountDM = new AccountDM();
			$accountDM->set_id($last_id);
			$accountDM->set_email($email);
			$accountDM->set_username($username);
			$accountDM->set_firstName($firstName);
			$accountDM->set_middleName($middleName);
			$accountDM->set_lastName($lastName);
			$accountDM->set_status($status);
			$accountDM->set_type($accountType);
			return json_encode(array("statusCode"=>200, "accountDetail"=>$accountDM));
		} else {
			array_push($statusCode, 5004);
		}
		return json_encode(array("statusCode"=>$statusCode));

	}

	public function logout(){
		if($this->settings->sess_des()){
			redirect('public/admin/login.php');
		}
	}

	public function logoutUser(){
		unset($_SESSION['user_detail']);
		return json_encode(array("statusCode"=>isset($_SESSION['user_detail'])? 201 : 200));
	}

	public function logoutAdmin(){
		unset($_SESSION['admin_detail']);
		return json_encode(array("statusCode"=>isset($_SESSION['admin_detail'])? 201 : 200));
	}

	public function retrieveUserInformation(){
		if(isset($_SESSION['user_detail'])){
			return json_encode(array("statusCode"=>200,"accountDetail"=>$_SESSION['user_detail']));
		} else {
			return json_encode(array("statusCode"=>201));
		}
	}

	public function retrieveAdminInformation(){
		if(isset($_SESSION['admin_detail'])){
			return json_encode(array("statusCode"=>200,"accountDetail"=>$_SESSION['admin_detail']));
		} else {
			return json_encode(array("statusCode"=>201));
		}
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

	public function changePassword(){
		$accountID = $_POST['accountID'];
		if(isset($_POST['currentPassword'])){
			$currentPassword = md5($_POST['currentPassword']);
			$newPassword = $_POST['newPassword'];

			if($this->isPasswordCorrect($accountID, $currentPassword) == false){
				return json_encode(array("statusCode"=>5000));
			}

			$sql = "UPDATE `account_information` SET `password`='$newPassword' WHERE account_id=$accountID";
			if (mysqli_query($this->conn, $sql)) {
				return json_encode(array("statusCode"=>200));
			} else {
				return json_encode(array("statusCode"=>201));
			}
		} else {
			$password = md5($_POST['password']);
			$sql = "UPDATE `account_information` SET `password`='$password' WHERE account_id=$accountID";
			if (mysqli_query($this->conn, $sql)) {
				return json_encode(array("statusCode"=>200));
			} else {
				return json_encode(array("statusCode"=>201));
			}
		}
	}

	public function updateUserAccount(){
		$accountID = $_POST['accountID'];
		$firstName = $_POST['firstName'];
		$middleName = $_POST['middleName'];
		$lastName = $_POST['lastName'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$province = $_POST['province'];
		$municipality = $_POST['municipality'];
		$barangay = $_POST['barangay'];

		$sql = "UPDATE `account_information` SET 
		`first_name`='$firstName',
		`middle_name`='$middleName',
		`last_name`='$lastName',
		`email`='$email',
		`phone`='$phone',
		`province`='$province',
		`municipality`='$municipality',
		`barangay`='$barangay' 
		WHERE account_id=$accountID";

		if (mysqli_query($this->conn, $sql)) {
			$accountDM = new AccountDM();
				$accountDM->set_id($accountID);
				$accountDM->set_email($email);
				$accountDM->set_phone($phone);
				$accountDM->set_firstName($firstName);
				$accountDM->set_middleName($middleName);
				$accountDM->set_lastName($lastName);
				$accountDM->set_province($province);
				$accountDM->set_municipality($municipality);
				$accountDM->set_barangay($barangay);
				$accountDM->set_status('1');
				$_SESSION['user_detail'] = $accountDM;
			return json_encode(array("statusCode"=>200));
		} else {
			return json_encode(array("statusCode"=>201));
		}
	}

	public function updateAdminAccount(){
		$accountID = $_POST['id'];
		$firstName = $_POST['firstName'];
		$middleName = $_POST['middleName'];
		$lastName = $_POST['lastName'];
		$email = $_POST['email'];
		$username = $_POST['username'];
		$status = $_POST['status'];

		if($this->isEmailExist($email, $accountID)){
			return json_encode(array("statusCode"=>5001));
		}

		if($this->isUsernameExist($username, $accountID)){
			return json_encode(array("statusCode"=>5002));
		}

		$password = "";
		if(isset($_POST['password'])){
			if($_POST['password'] != ""){
				$password = "`password`=". "'".md5($_POST['password'])."',";
			}
		}
		
		$sql = "UPDATE `account_information` SET 
		`first_name`='$firstName',
		`middle_name`='$middleName',
		`last_name`='$lastName',
		`email`='$email',
		`username`='$username',
		$password
		`status`='$status' 
		WHERE account_id=$accountID";

		if (mysqli_query($this->conn, $sql)) {
				$accountDM = new AccountDM();
				$accountDM->set_id($accountID);
				$accountDM->set_email($email);
				$accountDM->set_firstName($firstName);
				$accountDM->set_middleName($middleName);
				$accountDM->set_lastName($lastName);
				$accountDM->set_username($username);
				$accountDM->set_status($status);

				if(isset($_POST['profile'])){
					$_SESSION['admin_detail'] = $accountDM;
				}

			return json_encode(array("statusCode"=>200));
		} else {
			return json_encode(array("statusCode"=>201));
		}
	}

	public function isPasswordCorrect($accountID, $password){
		$sql = "SELECT * FROM account_information WHERE account_id = '$accountID' AND password = '$password'";
		$result = $this->conn->query($sql);
		if ($result->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	function deleteAdminAccount(){
		$id = $_POST['id'];
		$sql = "DELETE from account_information where account_id='$id'";
		if (mysqli_query($this->conn, $sql)) {
			return json_encode(array("statusCode"=>200));
		} else {
			return json_encode(array("statusCode"=>500));
		}
	}
}
$action = !isset($_POST['action']) ? 'none' : strtolower($_POST['action']);
$auth = new Login();

if(isset($_POST['action'])){
	switch ($action) {
		case 'login':
			echo $auth->login();
			break;
		case 'logout-user':
			echo $auth->logoutUser();
			break;
		case 'logout-admin':
			echo $auth->logoutAdmin();
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
		case 'create-admin-account':
			echo $auth->createAdminAccount();
			break;
		case 'update-admin-account':
			echo $auth->updateAdminAccount();
			break;
		case 'delete-admin-account':
			echo $auth->deleteAdminAccount();
			break;
		case 'update-user-account':
			echo $auth->updateUserAccount();
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
		case 'retrieve-user-list':
			echo $auth->retrieveUserList();
			break;
		case 'retrieve-applicant-list':
			echo $auth->retrieveApplicantList();
			break;			
		default:
			break;
	}
}
?>