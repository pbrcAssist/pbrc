<?php
require_once('BasicDM.php');
class ServiceReservationDM extends BasicDM {
    public $serviceID;
    public $reservationID;
    public $name;
    public $price;
    public $accountID;
    public $date;
    public $time;
    public $status;
    public $accountInformation;
    public $packageInformation;
    public $receipt;
    public $duration;

    function set_receipt($receipt) {
      $this->receipt = $receipt;
    }
    function get_receipt() {
      return $this->receipt;
    }

    function set_reservationID($reservationID) {
      $this->reservationID = $reservationID;
    }
    function get_reservationID() {
      return $this->reservationID;
    }

    function set_serviceID($serviceID) {
      $this->serviceID = $serviceID;
    }
    function get_serviceID() {
      return $this->serviceID;
    }

    function set_name($name) {
      $this->name = $name;
    }
    function get_name() {
      return $this->name;
    }

    function set_price($price) {
      $this->price = $price;
    }
    function get_price() {
      return $this->price;
    }

    function set_accountID($accountID) {
      $this->accountID = $accountID;
    }
    function get_accountID() {
      return $this->accountID;
    }
  
    function set_date($date) {
      $this->date = $date;
    }
    function get_date() {
      return $this->date;
    }
    
    function set_time($time) {
      $this->time = $time;
    }
    function get_time() {
      return $this->time;
    }

    function set_duration($duration) {
      $this->duration = $duration;
    }
    function get_duration() {
      return $this->duration;
    }

    function set_accountInformation($accountInformation) {
      $this->accountInformation = $accountInformation;
    }
    function get_accountInformation() {
      return $this->accountInformation;
    }

    function set_packageInformation($packageInformation) {
      $this->packageInformation = $packageInformation;
    }
    function get_packageInformation() {
      return $this->packageInformation;
    }
}


?>