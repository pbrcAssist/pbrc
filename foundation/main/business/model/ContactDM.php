<?php
class ContactDM {
    public $location;
    public $contactNumber;
    public $emailAddress;

    function set_location($location) {
      $this->location = $location;
    }
    function get_location() {
      return $this->location;
    }

    function set_contactNumber($contactNumber) {
      $this->contactNumber = $contactNumber;
    }
    function get_contactNumber() {
      return $this->contactNumber;
    }

    function set_emailAddress($emailAddress) {
      $this->emailAddress = $emailAddress;
    }
    function get_emailAddress() {
      return $this->emailAddress;
    }
}
?>