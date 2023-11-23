<?php
class AccountDM {
    public $id;
    public $email;
    public $username;
    public $phone;
    public $firstName;
    public $middleName;
    public $lastName;
    public $province;
    public $municipality;
    public $barangay;
    public $type;
    public $status;
    public $image;
    public $address;
  
    function set_id($id) {
      $this->id = $id;
    }
    function get_id() {
      return $this->id;
    }

    function set_email($email) {
        $this->email = $email;
    }
    function get_email() {
        return $this->email;
    }

    function set_username($username) {
      $this->username = $username;
    }
    function get_username() {
        return $this->username;
    }

    function set_phone($phone) {
        $this->phone = $phone;
    }
    function get_phone() {
        return $this->phone;
    }
    
    function set_name($name) {
      $this->name = $name;
    }
    function get_name() {
      return $this->name;
    }

    function set_firstName($firstName) {
      $this->firstName = $firstName;
    }
    function get_firstName() {
      return $this->firstName;
    }

    function set_middleName($middleName) {
      $this->middleName = $middleName;
    }
    function get_middleName() {
      return $this->middleName;
    }

    function set_lastName($lastName) {
      $this->lastName = $lastName;
    }
    function get_lastName() {
      return $this->lastName;
    }

    function set_address($address) {
      $this->address = $address;
    }
    function get_address() {
        return $this->address;
    }

    function set_province($province) {
        $this->province = $province;
    }
    function get_province() {
        return $this->province;
    }

    function set_municipality($municipality) {
      $this->municipality = $municipality;
    }
    function get_municipality() {
        return $this->municipality;
    }

    function set_barangay($barangay) {
      $this->barangay = $barangay;
    }
    function get_barangay() {
        return $this->barangay;
    }

    function set_type($type) {
        $this->type = $type;
    }
    function get_type() {
        return $this->type;
    }
    
    function set_image($image) {
        $this->image = $image;
    }
    function get_image() {
        return $this->image;
    }
  
    function set_status($status) {
      $this->status = $status;
    }
    function get_status() {
      return $this->status;
    }
}

?>