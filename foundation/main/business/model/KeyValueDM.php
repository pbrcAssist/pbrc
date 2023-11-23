<?php
class KeyValueDM {
    public $key;
    public $value;
    public $type;
  
    function set_key($key) {
      $this->key = $key;
    }
    function get_key() {
      return $this->key;
    }

    function set_value($value) {
      $this->value = $value;
    }
    function get_value() {
      return $this->value;
    }

    function set_type($type) {
      $this->type = $type;
    }
    function get_type() {
      return $this->type;
    }
}

function populateKeyValue($key, $value, $type){
  $keyValueDM = new KeyValueDM();
  $keyValueDM->set_key($key);
  $keyValueDM->set_value($value);
  $keyValueDM->set_type($type);
  return $keyValueDM;
}

?>