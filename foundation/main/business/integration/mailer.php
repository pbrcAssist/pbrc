<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../resources/library/phpmailer/src/Exception.php';
require '../../../resources/library/phpmailer/src/PHPMailer.php';
require '../../../resources/library/phpmailer/src/SMTP.php';
require 'mailer-support.php';

function sendEmail($sendTo, $subject, $body){
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pbrc.assist@gmail.com';
        $mail->Password = 'nmvhxdsjikkmztus';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        
        $mail->setFrom('pbrc.assist@gmail.com');
        $mail->addAddress($sendTo);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();

        echo json_encode(array("statusCode"=>200));
    } catch (phpmailerException $e) {
        echo json_encode(array("statusCode"=>500, "error"=>$e->errorMessage()));
    } catch (Exception $e) {
        echo json_encode(array("statusCode"=>500, "error"=>$e->errorMessage()));
    }
}


if(isset($_POST["action"])){
    if($_POST["action"] == 'send'){
        $email = $_POST["email"];
        sendEmail($email, "PBRC - Forgot Password", generateForgotPasswordLink($email, $_POST["key"]));
    }

    if($_POST["action"] == 'send-booking-notification'){
        $email = $_POST["email"];
        $body = populateGenerateEmailNotificationBody(
            $_POST["reservationID"],
            $_POST["roomName"],
            $_POST["roomPrice"],
            $_POST["roomDescription"], 
            $_POST["roomAmenities"],
            $_POST["checkinDate"],
            $_POST["checkinTime"],
            $_POST["checkoutDate"],
            $_POST["checkoutTime"],
            $_POST["name"]
        );

        sendEmail($email, "PBRC - Room Reservation Confirmation", $body);
    }

    if($_POST["action"] == 'send-service-booking-notification'){
        $email = $_POST["email"];
        $body = populateGenerateServiceEmailNotificationBody(
            $_POST["reservationID"],
            $_POST["serviceName"],
            $_POST["servicePrice"],
            $_POST["serviceDescription"], 
            $_POST["checkinDate"],
            $_POST["checkinTime"],
            $_POST["name"]
        );

        sendEmail($email, "PBRC - ". $_POST["serviceName"] ." Reservation Confirmation", $body);
    }

    if($_POST["action"] == 'send-payment-confirmation-email'){
        $email = $_POST["email"];
        $body = generatePaymentConfirmationEmail(
            $_POST["name"],
            $_POST["reservation"],
            $_POST["phone"],
            $_POST["id"]
        );

        sendEmail($email, "Payment Confirmation and Review in Progress", $body);
    }
    
} 

?>