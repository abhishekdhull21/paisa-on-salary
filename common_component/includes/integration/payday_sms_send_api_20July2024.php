<?php

function payday_sms_sent_api($type_id = "", $lead_id = 0, $request_array = array()) {

    common_log_writer(6, "SMS Send started | $lead_id | $type_id");

    $responseArray = array("status" => 0, "errors" => "");

    if (!empty($type_id)) {
        $responseArray = routemobile_sms_sent_api_call($type_id, $lead_id, $request_array);
    } else {
        $responseArray["errors"] = "Type id is can not be blank.";
    }

    common_log_writer(6, "SMS Send end | $lead_id | $type_id | " . json_encode($responseArray));

    return $responseArray;
}

function routemobile_sms_sent_api_call($sms_type_id, $lead_id = 0, $request_array = array()) {
   
    common_log_writer(6, "sms_sent_api_call started | $lead_id");

    require_once (COMP_PATH . '/includes/integration/integration_config.php');

    $response_array = array("status" => 0, "errors" => "");

    $envSet = COMP_ENVIRONMENT;
    $apiStatusId = 0;
    $apiResponseJson = "";
    $apiRequestDateTime = date("Y-m-d H:i:s");
    $apiResponseDateTime = "";
    $apiResponseData = "";
    $errorMessage = "";
    $curlError = "";
    $source = "";
    $tempid = "";
    $message = "";
    $template_id = 'Template_ID';
    $mobile = "";
    $cust_name = "";
    $executive_name = "";
    $executive_mobile = "";
    $otp = "";
    $reference_no = "";
    $loan_amount = "";
    $cust_bank_account_no = "";
    $loan_no = "";
    $repayment_amount = "";
    $repayment_date = "";
    $repayment_link = "https://www.lms.sotcrm.com/repay-loan";
    //$repayment_link = 'repay-loan';
    $type = "SMS_API";
    $api_sub_type = "NORMAL";

    // if (in_array($sms_type_id, [16, 17])) {
    //     $api_sub_type = 'PROMOTIONAL';
    // }

    $hardcode_response = false;

    $debug = !empty($_REQUEST['bltest']) ? 1 : 0;
//        $debug = 1;

    $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : 0;

    $leadModelObj = new LeadModel();

    try {


        $apiConfig = integration_config($type, $api_sub_type);

        if ($debug == 1) {
            echo "<pre>";
            print_r($apiConfig);
        }

        if ($debug == 1) {
            echo "<pre>";
            print_r($request_array);
            exit;
        }

        if ($apiConfig['Status'] != 1) {
            throw new Exception($apiConfig['ErrorInfo']);
        }

        $mobile = $request_array['mobile'];
        $cust_name = (isset($request_array['name']) && !empty($request_array['name'])) ? $request_array['name'] : "Customer";
        $reference_no = (isset($request_array['refrence_no']) && !empty($request_array['refrence_no'])) ? $request_array['refrence_no'] : "";
        $otp = (isset($request_array['otp']) && !empty($request_array['otp'])) ? $request_array['otp'] : "";
        $executive_name = (isset($request_array['executive_name']) && !empty($request_array['executive_name'])) ? $request_array['executive_name'] : "Team Kasar Credit & Capital";
        $executive_mobile = (isset($request_array['executive_mobile']) && !empty($request_array['executive_mobile'])) ? $request_array['executive_mobile'] : "";
        $loan_amount = (isset($request_array['loan_amount']) && !empty($request_array['loan_amount'])) ? $request_array['loan_amount'] : "";
        $cust_bank_account_no = (isset($request_array['cust_bank_account_no']) && !empty($request_array['cust_bank_account_no'])) ? $request_array['cust_bank_account_no'] : "";
        $loan_no = (isset($request_array['loan_no']) && !empty($request_array['loan_no'])) ? $request_array['loan_no'] : "";
        $repayment_amount = (isset($request_array['repayment_amount']) && !empty($request_array['repayment_amount'])) ? $request_array['repayment_amount'] : "";
        $repayment_date = (isset($request_array['repayment_date']) && !empty($request_array['repayment_date'])) ? $request_array['repayment_date'] : "";
        $pending_days = (isset($request_array['pending_days']) && !empty($request_array['pending_days'])) ? $request_array['pending_days'] : "";
        $esign_link = (isset($request_array['esign_link']) && !empty($request_array['esign_link'])) ? $request_array['esign_link'] : "";
        $ekyc_link = (isset($request_array['ekyc_link']) && !empty($request_array['ekyc_link'])) ? $request_array['ekyc_link'] : "";
        $repayment_link_generate = (isset($request_array['repayment_link_generate']) && !empty($request_array['repayment_link_generate'])) ? $request_array['repayment_link_generate'] : "";
        $upload_doc_link = (isset($request_array['upload_doc_link']) && !empty($request_array['upload_doc_link'])) ? $request_array['upload_doc_link'] : "";
        
        if (empty($mobile)) {
            throw new Exception('Mobile number is black.');
        }

//        if ($sms_type_id == 1) {//OTP
//            if (empty($cust_name) || empty($otp)) {
//                throw new Exception('Customer Name or otp is blank.');
//            }
//
//            $template_id = "1207167522658871633";
//            $source = "BLAPPL";
//            $input_message = "Dear $cust_name,\n$otp is your mobile verification code.\nPlease don't share it with anyone - Kasar Credit & Capital (SOT CRM)";
//        }
        
            // if (empty($otp) || empty($cust_name)) {
            //     throw new Exception('Customer Name or OTP is blank.');
            // }
           $sms_username = urlencode($apiConfig["username"]);
        //   if ($sms_type_id == 1) {//Mobile OTP Sms New
            if (empty($otp) || empty($cust_name)) {
                throw new Exception('Customer Name or OTP is blank.');
            }
            $template_id = "1707171353065890872";
            $source = "TOPYRN";
            //$input_message = "Dear " . $cust_name . ", " . $otp . " is your mobile verification code. Please don't share it with anyone. Kasar Credit & Capital (SOT CRM)";
            //$input_message = "Dear customer, ".$otp." is the OTP for your login at SalaryOnTime. In case you have not requested this, please contact us at info@salaryontime.com KASAR";
            $input_message = "Dear customer, ".$otp." is the OTP for your login at SalaryOnTime. In case you have not requested this, please contact us at info@salaryontime.com KASAR";
            // }
    
       
        $apiUrl = $apiConfig["ApiUrl"];
        
        $sms_username = urlencode($apiConfig["username"]);
        $sms_password = urlencode($apiConfig["password"]);
        $sms_type = $apiConfig["type"];
        $dlr = $apiConfig["dlr"];
        $sms_entityid = $apiConfig["entityid"];
        $message = urlencode($input_message);
    //   https://vapio.in/api.php?username=kasar&apikey=3jhWMzQj9tmG&senderid=KASARC&route=TRANS&mobile=7217602607,8282824633&text="
    // "Dear customer, 384656 is the OTP for your login at SalaryOnTime. In case you have not requested this, please contact us at info@salaryontime.com KASAR
    // &TID=1707171353065890872&PEID=1701171300552626064"
        //$apiData = "username=$sms_username&password=$sms_password&type=$sms_type&dlr=$dlr&destination=$mobile&source=$source&message=$message&entityid=$sms_entityid&tempid=$template_id";
        $apiData ="username=kasar&apikey=3jhWMzQj9tmG&senderid=KASARC&route=TRANS&mobile=$mobile&text=$message&TID=1707171353065890872&PEID=1701171300552626064&format=json";
        
        $apiRequestDateTime = date("Y-m-d H:i:s");
        // print_r($apiUrl.$apiData); die;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl.$apiData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $apiData
        ));
 
        $apiResponseJson = curl_exec($curl);
                // print_r($apiResponseJson); die;


        if ($debug == 1) {
            echo "<br/><br/> =======Response======<br/><br/>" . $apiResponseJson;
        }

        $apiResponseJson = preg_replace("!\s+!", " ", $apiResponseJson);

        $apiResponseDateTime = date("Y-m-d H:i:s");

        if (!$hardcode_response && curl_errno($curl)) { // CURL Error
            $curlError = curl_error($curl);
            curl_close($curl);
            throw new RuntimeException("Something went wrong. Please try after sometimes.");
        } else {

            if (isset($curl)) {
                curl_close($curl);
            }

            $apiResponseData = explode(":", $apiResponseJson);

            if (!empty($apiResponseData)) {

                $apiResponseData = $apiResponseData[1];

                if (!empty($apiResponseData)) {
                    $apiStatusId = 1;
                } else {
                    throw new ErrorException("Some error occurred. Please try again.");
                }
            } else {
                throw new ErrorException("Some error occurred. Please try again..");
            }
        }
    } catch (ErrorException $le) {
        $apiStatusId = 2;
        $errorMessage = $le->getMessage();
    } catch (RuntimeException $re) {
        $apiStatusId = 3;
        $errorMessage = $re->getMessage();
    } catch (Exception $e) {
        $apiStatusId = 4;
        $errorMessage = $e->getMessage();
    }


    $insertApiLog = array();
    $insertApiLog['sms_provider'] = 1;
    //$insertApiLog['sms_type_id'] = $sms_type_id;
    $insertApiLog['sms_mobile'] = $mobile;
    $insertApiLog['sms_content'] = addslashes($input_message);
    $insertApiLog['sms_template_id'] = $template_id;
    $insertApiLog['sms_template_source'] = $source;
    $insertApiLog['sms_api_status_id'] = $apiStatusId;
    $insertApiLog['sms_lead_id'] = $lead_id;
    $insertApiLog['sms_user_id'] = $user_id;
    $insertApiLog['sms_errors'] = $errorMessage;
    $insertApiLog['sms_created_on'] = date("Y-m-d H:i:s");

    $leadModelObj->insertTable("api_sms_logs", $insertApiLog);

    $response_array['status'] = $apiStatusId;
    $response_array['data'] = $apiResponseData;
    $response_array['mobile'] = $mobile;
    $response_array['errors'] = $errorMessage;

    if ($debug) {
        $response_array['request_json'] = $apiData;
        $response_array['response_json'] = $apiResponseJson;
    }
    return $apiResponseJson;
}

?>
