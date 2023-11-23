<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer"
    />
    
    <style type="text/css">
    @page {
            size: 8.5in 14in;  /* width height */
            margin-top: 0.05in;
            margin-bottom: 0.8in;
            margin-left: 0.05in;
            margin-right: 0.05in;
        }

    .table tbody tr td, .table tbody tr th {
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
    }
    </style>
</head>
<body>
    

<body>
        <br>
        <div id="myDiv" width="950">
            <table class="table " style="width:100%;">
                <thead>
                  <tr>
                    <td align="center" width="25%">
                        <img style="height:80px; width:80px;" src="<?php echo validate_image($_settings->info('logo')); ?>">
                    </td>
                    <td align="center" width="50%">
                      <strong>Poggio Bustone Renewal Center</strong><br>
                      <strong>Beneg, Botolan, Zambales</strong>
                    </td>
                    <td width="25%">&nbsp;</td>

                  </tr>
                  <tr>
                    <td colspan="3" align="center">
                      <h3><?php echo $sched_type ?></h3> 
                    </td>
                  </tr>
                </thead>
                <tbody>
                </tbody>
               
            </table>
            <table class="table " style="width:30%;margin-left:250px">
                <thead>
                    <tr>
                      <td align="right"><b>Name:</b> </td>
                      <td colspan="2"><?php echo isset($Lname) ? $Lname.", ".$Fname.", ".$Mname : '' ?></td>
                    </tr>
                    <tr>
                      <td align="right"><b>Contact:</b> </td>
                      <td colspan="2"><?php echo isset($contact) ? $contact : '' ?></td>
                    </tr>
                    <tr>
                      <td align="right"><b>Address:</b> </td>
                      <td colspan="2"><?php echo isset($address) ? strtoupper($brgyDesc.", ".$citymunDesc.", ".$provDesc) : '' ?></td>
                    </tr>
                    <tr>
                      <td align="right"><b>Date:</b> </td>
                      <td colspan="2"><?php echo isset($schedule) ? date("Y-m-d",strtotime($schedule)) : '' ?></td>
                    </tr>
                    <tr>
                      <td align="right"><b>Time:</b> </td>
                      <td colspan="2"><?php echo isset($time) ? $time : '' ?></td>
                    </tr>
                    <tr>
                      <td align="right"><b>Email:</b> </td>
                      <td colspan="2"><?php echo isset($email) ? $email : '' ?></td>
                    </tr>
                    <br>
                    <br>
                    
                </thead>
            </table>
        </div>
</body>
</body>
</html>