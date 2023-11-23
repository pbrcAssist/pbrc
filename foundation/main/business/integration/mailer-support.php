<?php

function generateForgotPasswordLink($email, $key){
    $body = '<div style="margin:0;padding:0" bgcolor="#FFFFFF">
                <table width="100%" height="100%" style="min-width:348px" border="0" cellspacing="0" cellpadding="0" lang="en">
                    <tbody>
                        <tr height="32" style="height:32px">
                            <td></td>
                        </tr>
                        <tr align="center">
                            <td>
                                <table border="0" cellspacing="0" cellpadding="0" style="padding-bottom:20px;max-width:516px;min-width:220px">
                                    <tbody>
                                        <tr>
                                            <td width="8" style="width:8px"></td>
                                            <td>
                                                <div style="border-style:solid;border-width:thin;border-color:#dadce0;border-radius:8px;padding:40px 20px" align="center" class="m_-7949188087747382486mdv2rw">
                                                    Poggio Bustone Renewal Center
                                                    <div style="font-family:\'Google Sans\',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;border-bottom:thin solid #dadce0;color:rgba(0,0,0,0.87);line-height:32px;padding-bottom:24px;text-align:center;word-break:break-word">
                                                    <div style="font-size:24px">Forgot Password</div>
                                                        <table align="center" style="margin-top:8px">
                                                            <tbody>
                                                                <tr style="line-height:normal">
                                                                </tr>
                                                            </tbody>
                                                        </table> 
                                                    </div>
                                                    <div style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:14px;color:rgba(0,0,0,0.87);line-height:20px;padding-top:20px;text-align:left">If you didn\'t generate this forgot password, someone might be using your account. Check and secure your account now.
                                                        <div style="padding-top:32px;text-align:center">
                                                            <a href="https://pbrc.pcbics.net/web/main/user/page/change-password.php?key='.$key.'" style="font-family:\'Google Sans\',Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://accounts.google.com/AccountChooser?Email%3Dforcemindshift@gmail.com%26continue%3Dhttps://myaccount.google.com/alert/nt/1689360587509?rfn%253D20%2526rfnc%253D1%2526eid%253D981215007565641449%2526et%253D0&amp;source=gmail&amp;ust=1689447870813000&amp;usg=AOvVaw1xnwhGPu8XwulukYZmQdv7">
                                                                Change Password
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="text-align:left">
                                                    <div style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.54);font-size:11px;line-height:18px;padding-top:12px;text-align:center">
                                                        <div style="direction:ltr">© PBRC, 
                                                            <a class="m_-7949188087747382486afal" style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;color:rgba(0,0,0,0.54);font-size:11px;line-height:18px;padding-top:12px;text-align:center"></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td width="8" style="width:8px"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr height="32" style="height:32px">
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>';
            return $body;
}

function populateGenerateEmailNotificationBody($reservationID,$roomName,$roomPrice,$roomDescription, $roomAmenities,$checkinDate,$checkinTime,$checkoutDate,$checkoutTime,$name){
    $body = '<div style="margin:0;padding:0" bgcolor="#FFFFFF">
                <b>H '.$name.'!</b>
                <br/>
                <br/>
                <b>Room reservation</b>
                <br/>
                <b>Room:</b>'.$roomName.'
                <br/>
                <b>Price:</b>'.$roomPrice.'
                <br/>
                <b>Description:</b> '.$roomDescription.'
                <br/>
                <b>Amenities:</b> '.$roomAmenities.'
                <br/>
                <b>Checkin Date:</b> '.$checkinDate.' 
                <br/>
                <b>Checkin Time:</b> '.$checkinTime.'
                <br/>
                <b>Checkin Date:</b> '.$checkoutDate.' 
                <br/>
                <b>Checkout Time:</b> '.$checkoutTime.'
                <br/>
                <br/>
                <b>Note: </b> Please pay your reservation within 12 hours or else your reservation will be cancelled.
            </div>';
    return $body;
}

function populateGenerateServiceEmailNotificationBody($reservationID,$serviceName,$servicePrice,$serviceDescription, $checkinDate,$checkinTime, $name){
    $body = '<div style="margin:0;padding:0" bgcolor="#FFFFFF">
                <b>Hi '.$name.'!</b>
                <br/>
                <br/>
                <b>' . $serviceName . ' reservation</b>
                <br/>
                <b>Service:</b>'.$serviceName.'
                <br/>
                <b>Price:</b>'.$servicePrice.'
                <br/>
                <b>Description:</b> '.$serviceDescription.'
                <br/>
                <b>Checkin Date:</b> '.$checkinDate.' 
                <br/>
                <b>Checkin Time:</b> '.$checkinTime.'
                <br/>
                <br/>
                <b>Note: </b> Please pay your reservation within 12 hours or else your reservation will be cancelled.
            </div>';
    return $body;
}

function generatePaymentConfirmationEmail($name, $reservation, $phone, $reservationID) {
    $message = '
        <div style="margin: 20px; padding: 20px; font-family: Arial, sans-serif; background-color: #FFFFFF; color: #333333;">
            <p><b>Hi ' . $name . '!</b></p>
            <p>We hope this message finds you well. We wanted to inform you that we have received your donation receipt for ' . $reservation . ' reservation. Our admin team is currently in the process of reviewing it. Please be patient, as this may take a little while.</p>
            <p>Once the payment has been confirmed, you will receive an email notification promptly. If you have any concerns or questions in the meantime, feel free to reach out to us by calling ' . $phone . '.</p>
            <p>Thank you for your donation and your trust in our services!</p>
            <br>
            Reservation ID: '.$reservationID.'
            <br>
            <p>Best regards,</p>
            <p>Poggio Bustone Renewal Center</p>
        </div>
    ';
    return $message;
}


?>