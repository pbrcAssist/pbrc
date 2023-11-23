<?php
class ReferenceDM {
    public $name;
    public $value;
    public $subReference;

    function set_name($name) {
      $this->name = $name;
    }
    function get_name() {
      return $this->name;
    }
    
    function set_value($value) {
      $this->value = $value;
    }
    function get_value() {
      return $this->value;
    }

    function set_subReference($subReference) {
      $this->subReference = $subReference;
    }
    function get_subReference() {
      return $this->subReference;
    }
}
?>