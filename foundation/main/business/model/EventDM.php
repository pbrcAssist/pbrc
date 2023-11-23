<?php
require_once('BasicDM.php');
class EventDM extends BasicDM {
    public $date;
    public $time;

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
}
?>