<?php
require_once('BasicDM.php');
class RoomDM extends BasicDM {
    public $pax;
    public $maxPax;
    public $amenities;
    public $price;
    public $roomCategoryName;
    public $roomCategoryID;
    
    function set_roomCategoryID($roomCategoryID) {
      $this->roomCategoryID = $roomCategoryID;
    }
    function get_roomCategoryID() {
      return $this->roomCategoryID;
    }

    function set_roomCategoryName($roomCategoryName) {
      $this->roomCategoryName = $roomCategoryName;
    }
    function get_roomCategoryName() {
      return $this->roomCategoryName;
    }

    function set_pax($pax) {
      $this->pax = $pax;
    }
    function get_pax() {
      return $this->pax;
    }

    function set_maxPax($maxPax) {
      $this->maxPax = $maxPax;
    }
    function get_maxPax() {
      return $this->maxPax;
    }
    
    function set_amenities($amenities) {
      $this->amenities = $amenities;
    }
    function get_amenities() {
      return $this->amenities;
    }

    function set_price($price) {
      $this->price = $price;
    }
    function get_price() {
      return $this->price;
    }
}

class RoomReservationListDM {
  public $roomID;
  public $roomName;
  public $reservationList;

  function set_roomID($roomID) {
    $this->roomID = $roomID;
  }
  function get_roomID() {
    return $this->roomID;
  }

  function set_roomName($roomName) {
    $this->roomName = $roomName;
  }
  function get_roomName() {
    return $this->roomName;
  }

  function set_reservationList($reservationList) {
    $this->reservationList = $reservationList;
  }
  function get_reservationList() {
    return $this->reservationList;
  }
}

class ReservationScheduleDM {
  public $checkinDate;
  public $checkinTime;

  public $checkoutDate;
  public $checkoutTime;

  public $status;

  function set_checkinDate($checkinDate) {
    $this->checkinDate = $checkinDate;
  }
  function get_checkinDate() {
    return $this->checkinDate;
  }

  function set_checkinTime($checkinTime) {
    $this->checkinTime = $checkinTime;
  }
  function get_checkinTime() {
    return $this->checkinTime;
  }

  function set_checkoutDate($checkoutDate) {
    $this->checkoutDate = $checkoutDate;
  }
  function get_checkoutDate() {
    return $this->checkoutDate;
  }

  function set_checkoutTime($checkoutTime) {
    $this->checkoutTime = $checkoutTime;
  }
  function get_checkoutTime() {
    return $this->checkoutTime;
  }

  function set_status($status) {
    $this->status = $status;
  }
  function get_status() {
    return $this->status;
  }
}

class RoomRequestDM extends BasicDM {
  public $id;
  public $roomID;
  public $accountID;
  public $date;
  public $time;

  public $checkinDate;
  public $checkinTime;

  public $checkoutDate;
  public $checkoutTime;

  public $status;

  function set_id($id) {
    $this->id = $id;
  }
  function get_id() {
    return $this->id;
  }

  function set_roomID($roomID) {
    $this->roomID = $roomID;
  }
  function get_roomID() {
    return $this->roomID;
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

  function set_checkinDate($checkinDate) {
    $this->checkinDate = $checkinDate;
  }
  function get_checkinDate() {
    return $this->checkinDate;
  }

  function set_checkinTime($checkinTime) {
    $this->checkinTime = $checkinTime;
  }
  function get_checkinTime() {
    return $this->checkinTime;
  }

  function set_checkoutDate($checkoutDate) {
    $this->checkoutDate = $checkoutDate;
  }
  function get_checkoutDate() {
    return $this->checkoutDate;
  }

  function set_checkoutTime($checkoutTime) {
    $this->checkoutTime = $checkoutTime;
  }
  function get_checkoutTime() {
    return $this->checkoutTime;
  }

  function set_status($status) {
    $this->status = $status;
  }
  function get_status() {
    return $this->status;
  }
}

class RoomReservationDM extends BasicDM {
  public $reservationID;
  public $roomInformation;
  public $accountInformation;
  public $additionalInformation;
  
  public $createdDate;
  
  public $checkinDate;
  public $checkinTime;

  public $checkoutDate;
  public $checkoutTime;

  public $status;

  public $additionalAdult;
  public $additionalChildren;

  public $additionalBreakFast;
  public $additionalBreakFastServing;

  public $additionalLunch;
  public $additionalLunchServing;

  public $additionalSnack;
  public $additionalSnackServing;

  public $additionalDinner;
  public $additionalDinnerServing;

  public $additionalFoodInstruction;

  public $additionalTowel;
  public $additionalPillow;
  public $additionalBlanket;
  public $additionalBed;

  public $receipt;

  public $totalAmount;

  function set_totalAmount($totalAmount) {
    $this->totalAmount = $totalAmount;
  }
  function get_totalAmount() {
    return $this->totalAmount;
  }

  function set_receipt($receipt) {
    $this->receipt = $receipt;
  }
  function get_receipt() {
    return $this->receipt;
  }

  function set_additionalAdult($additionalAdult) {
    $this->additionalAdult = $additionalAdult;
  }
  function get_additionalAdult() {
    return $this->additionalAdult;
  }

  function set_additionalChildren($additionalChildren) {
    $this->additionalChildren = $additionalChildren;
  }
  function get_additionalChildren() {
    return $this->additionalChildren;
  }

  function set_additionalBreakFast($additionalBreakFast) {
    $this->additionalBreakFast = $additionalBreakFast;
  }
  function get_additionalBreakFast() {
    return $this->additionalBreakFast;
  }

  function set_additionalBreakFastServing($additionalBreakFastServing) {
    $this->additionalBreakFastServing = $additionalBreakFastServing;
  }
  function get_additionalBreakFastServing() {
    return $this->additionalBreakFastServing;
  }

  function set_additionalLunch($additionalLunch) {
    $this->additionalLunch = $additionalLunch;
  }
  function get_additionalLunch() {
    return $this->additionalLunch;
  }

  function set_additionalLunchServing($additionalLunchServing) {
    $this->additionalLunchServing = $additionalLunchServing;
  }
  function get_additionalLunchServing() {
    return $this->additionalLunchServing;
  }

  function set_additionalSnack($additionalSnack) {
    $this->additionalSnack = $additionalSnack;
  }
  function get_additionalSnack() {
    return $this->additionalSnack;
  }

  function set_additionalSnackServing($additionalSnackServing) {
    $this->additionalSnackServing = $additionalSnackServing;
  }
  function get_additionalSnackServing() {
    return $this->additionalSnackServing;
  }

  function set_additionalDinner($additionalDinner) {
    $this->additionalDinner = $additionalDinner;
  }
  function get_additionalDinner() {
    return $this->additionalDinner;
  }

  function set_additionalDinnerServing($additionalDinnerServing) {
    $this->additionalDinnerServing = $additionalDinnerServing;
  }
  function get_additionalDinnerServing() {
    return $this->additionalDinnerServing;
  }

  function set_additionalFoodInstruction($additionalFoodInstruction) {
    $this->additionalFoodInstruction = $additionalFoodInstruction;
  }
  function get_additionalFoodInstruction() {
    return $this->additionalFoodInstruction;
  }

  function set_additionalTowel($additionalTowel) {
    $this->additionalTowel = $additionalTowel;
  }
  function get_additionalTowel() {
    return $this->additionalTowel;
  }

  function set_additionalPillow($additionalPillow) {
    $this->additionalPillow = $additionalPillow;
  }
  function get_additionalPillow() {
    return $this->additionalPillow;
  }

  function set_additionalBlanket($additionalBlanket) {
    $this->additionalBlanket = $additionalBlanket;
  }
  function get_additionalBlanket() {
    return $this->additionalBlanket;
  }

  function set_additionalBed($additionalBed) {
    $this->additionalBed = $additionalBed;
  }
  function get_additionalBed() {
    return $this->additionalBed;
  }

  function set_reservationID($reservationID) {
    $this->reservationID = $reservationID;
  }
  function get_reservationID() {
    return $this->reservationID;
  }

  function set_roomInformation($roomInformation) {
    $this->roomInformation = $roomInformation;
  }
  function get_roomInformation() {
    return $this->roomInformation;
  }

  function set_accountInformation($accountInformation) {
    $this->accountInformation = $accountInformation;
  }
  function get_accountInformation() {
    return $this->accountInformation;
  }
  
  function set_createdDate($createdDate) {
    $this->createdDate = $createdDate;
  }
  function get_createdDate() {
    return $this->createdDate;
  }

  function set_checkinDate($checkinDate) {
    $this->checkinDate = $checkinDate;
  }
  function get_checkinDate() {
    return $this->checkinDate;
  }

  function set_checkinTime($checkinTime) {
    $this->checkinTime = $checkinTime;
  }
  function get_checkinTime() {
    return $this->checkinTime;
  }

  function set_checkoutDate($checkoutDate) {
    $this->checkoutDate = $checkoutDate;
  }
  function get_checkoutDate() {
    return $this->checkoutDate;
  }

  function set_checkoutTime($checkoutTime) {
    $this->checkoutTime = $checkoutTime;
  }
  function get_checkoutTime() {
    return $this->checkoutTime;
  }

  function set_status($status) {
    $this->status = $status;
  }
  function get_status() {
    return $this->status;
  }
}

?>