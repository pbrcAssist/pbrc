<?php
class WebsiteDM {
    public $tagline;
    public $cover;
    public $about;
  
    function set_tagline($tagline) {
      $this->tagline = $tagline;
    }
    function get_tagline() {
      return $this->tagline;
    }

    function set_cover($cover) {
      $this->cover = $cover;
    }
    function get_cover() {
      return $this->cover;
    }
    function set_about($about) {
      $this->about = $about;
    }
    function get_about() {
      return $this->about;
    }
}

?>