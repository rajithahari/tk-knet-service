<?php
namespace App\Http\Controllers;

use App\Http\ {
    Controllers\Controller,
    Services\Gateway\Plugins\E24PaymentPipe
};
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    /**
     * Show the form for creating a new contact.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $e24PaymentPipes = new E24PaymentPipe;
        $e24PaymentPipes->setAction(1);
        $e24PaymentPipes->setCurrency(414);
        $e24PaymentPipes->setLanguage("ENG"); //change it to "ARA" for arabic language
        $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";

        $e24PaymentPipes->setResponseURL($actual_link."response.php"); // set your respone page URL
        $e24PaymentPipes->setErrorURL($actual_link."error.php"); //set your error page URL
        $e24PaymentPipes->setResourcePath($_SERVER['DOCUMENT_ROOT']."\\resource\\"); //change the path where your resource file is
        $e24PaymentPipes->setAlias("kuwait"); //set your alias name here
        $e24PaymentPipes->setTrackId("3434");//generate the random number here
        $json = '{
        "id" : 252,
        "passengerInfo" : [ 
           {
               "phoneNumber" : "+91-9876543210",
               "paxType" : "Adult",
               "expiryDate" : "2018-05-16T18:30:00.000Z",
               "passportNumber" : "AFSDGG23455",
               "dob" : "12-1-1999",
              "ticketNumber" : "TK324",
               "nationality" : "India",
               "email" : "test@gmail.com",
               "lastName" : "user",
               "firstName" : "Test",
               "title" : "Ms"
           }
        ],
        "generalInfo" : {
           "customerEmail" : "test@gmail.com",
           "customerMobile" : "+91-9876543210",
           "tkbookingId" : "TK000094",
           "currency" : "KWD",
           "amount" : 300,
           "bookingDate" : "2017-11-14T18:30:00.000Z"
        }
        }';
        $decoded_data = json_decode($json, true);
        $e24PaymentPipes->setAmt($decoded_data['generalInfo']['amount']); //set the amount for the transaction

        $e24PaymentPipes->setUdf1($decoded_data['generalInfo']['tkbookingId']);

            //get results
        if($e24PaymentPipes->performPaymentInitialization()!=$e24PaymentPipes->SUCCESS){
            echo json_encode(['status'=>$e24PaymentPipes->SUCCESS,'errorMsg'=>$e24PaymentPipes->getErrorMsg(),'debugMsg'=>$e24PaymentPipes->getDebugMsg()]);
            
        }else {
            $payId = $e24PaymentPipes->getPaymentId();
            $payUrl = $e24PaymentPipes->getPaymentPage();
            echo json_encode(['status'=>true,'payId'=>$payId,'payUrl'=>$payUrl."?PaymentID=".$payId],JSON_UNESCAPED_SLASHES);
            //return REDIRECT::to($payUrl."?PaymentID=".$payId);
        }
        exit;

    }

    public function response(){

        if(isset($_POST) && !empty($_POST)){
            /*Created by saqib  18-08-2009*/
            $PaymentID = $_POST['paymentid'];
            $presult = $_POST['result'];
            $postdate = $_POST['postdate'];
            $tranid = $_POST['tranid'];
            $auth = $_POST['auth'];
            $ref = $_POST['ref'];
            $trackid = $_POST['trackid'];
            $udf1 = $_POST['udf1'];

            $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
            if ( $presult == "CAPTURED" )
            {
                $result_url = $actual_link."result";
                
                $result_params = "?PaymentID=" . $PaymentID . "&Result=" . $presult . "&PostDate=" . $postdate . "&TranID=" . $tranid . "&Auth=" . $auth . "&Ref=" . $ref . "&TrackID=" . $trackid . "&UDF1=" . $udf1   ;            
            }
            else
            {
                $result_url = $actual_link."error";
                $result_params = "?PaymentID=" . $PaymentID . "&Result=" . $presult . "&PostDate=" . $postdate . "&TranID=" . $tranid . "&Auth=" . $auth . "&Ref=" . $ref . "&TrackID=" . $trackid . "&UDF1=" . $udf1;

            }
            echo "REDIRECT=".$result_url.$result_params;
        }else{
           echo json_encode(['status'=>false]);
            exit; 
        }
        
    }   
    public function error(){
        echo json_encode(['status'=>false]);
        exit;
    }
    public function result(){
        $data = [];
        $data = $_GET;
        echo json_encode(['status'=>true,'data'=>$data]);
        exit;
    }
}