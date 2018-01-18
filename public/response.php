<?php
namespace App\Http\Services\response;

if(isset($_POST) && !empty($_POST)){
    /*Created by saqib  18-08-2009*/
    $PaymentID = $_POST['paymentid'];
    $presult = $_POST['result'];
    $postdate = $_POST['postdate'];
    $tranid = $_POST['tranid'];
    $auth = $_POST['auth'];
    $ref = $_POST['ref'];
    $trackid = $_POST['trackid'];
    $bookingId = $_POST['udf1'];

    $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
    if ( $presult == "CAPTURED" )
    {
        $result_url = $actual_link."result";
        
        $result_params = "?PaymentID=" . $PaymentID . "&Result=" . $presult . "&PostDate=" . $postdate . "&TranID=" . $tranid . "&Auth=" . $auth . "&Ref=" . $ref . "&TrackID=" . $trackid . "&BookingID=" . $bookingId   ;            
    }
    else
    {
        $result_url = $actual_link."error";
        $result_params = "?PaymentID=" . $PaymentID . "&Result=" . $presult . "&PostDate=" . $postdate . "&TranID=" . $tranid . "&Auth=" . $auth . "&Ref=" . $ref . "&TrackID=" . $trackid . "&BookingID=" . $bookingId;

    }
    echo "REDIRECT=".$result_url.$result_params;
}else{
   echo json_encode(['status'=>false]);
    exit; 
}