<?php
class BasicDM {
    public $id;
    public $name;
    public $description;
    public $image;
    public $status;
    public $price;
    public $time;
    public $additionalInformation;

  
    function set_id($id) {
      $this->id = $id;
    }
    function get_id() {
      return $this->id;
    }

    function set_time($time) {
      $this->time = $time;
    }
    function get_time() {
      return $this->time;
    }
  
    function set_name($name) {
      $this->name = $name;
    }
    function get_name() {
      return $this->name;
    }
    
    function set_description($description) {
      $this->description = $description;
    }
    function get_description() {
      return $this->description;
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

    function set_price($price) {
      $this->price = $price;
    }
    function get_price() {
      return $this->price;
    }

    function set_additionalInformation($additionalInformation) {
      $this->additionalInformation = $additionalInformation;
    }
    function get_additionalInformation() {
      return $this->additionalInformation;
    }
}

function populateBasicDM($id, $name, $description, $image, $status){
  $basicDM = new BasicDM();
  $basicDM->set_id($id);
  $basicDM->set_name($name);
  $basicDM->set_description($description);
  $basicDM->set_image($image);
  $basicDM->set_status($status);
  return $basicDM;
}

?>