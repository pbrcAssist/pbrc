<?php
require_once('BasicDM.php');
class PackageDM extends BasicDM {
    public $packageID;
    public $packageName;
    public $packageDescription;
    public $packagePrice;
    public $packageDuration;

    function set_packageID($packageID) {
      $this->packageID = $packageID;
    }
    function get_packageID() {
      return $this->packageID;
    }

    function set_packageName($packageName) {
      $this->packageName = $packageName;
    }
    function get_packageName() {
      return $this->packageName;
    }

    function set_packageDescription($packageDescription) {
      $this->packageDescription = $packageDescription;
    }
    function get_packageDescription() {
      return $this->packageDescription;
    }

    function set_packagePrice($packagePrice) {
      $this->packagePrice = $packagePrice;
    }
    function get_packagePrice() {
      return $this->packagePrice;
    }

    function set_packageDuration($packageDuration) {
      $this->packageDuration = $packageDuration;
    }
    function get_packageDuration() {
      return $this->packageDuration;
    }
}


?>