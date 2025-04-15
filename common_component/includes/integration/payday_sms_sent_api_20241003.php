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

    $senderId = array("KASARC", "KASARL", "KASRCL");

    $hardcode_response = false;
    $debug = !empty($_REQUEST['bltest']) ? 1 : 0;
    $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : 0;
    
    $leadModelObj = new LeadModel();
    
    try {
    $apiConfig = integration_config($type, $api_sub_type);

    if ($debug == 1) {
        echo "<pre>";
        print_r($apiConfig);
    }

    if ($apiConfig['Status'] != 1) {
        throw new Exception($apiConfig['ErrorInfo']);
    }

    if ($debug == 1) {
        echo "<pre>";
        print_r($request_array);
        exit;
    }

    $mobile = $request_array['mobile'];
    $cust_name = isset($request_array['name']) ? $request_array['name'] : "Customer";
    $reference_no = isset($request_array['refrence_no']) ? $request_array['refrence_no'] : "";
    $otp = isset($request_array['otp']) ? $request_array['otp'] : "";
    $executive_name = isset($request_array['executive_name']) ? $request_array['executive_name'] : "Team Kasar Credit & Capital";
    $executive_mobile = isset($request_array['executive_mobile']) ? $request_array['executive_mobile'] : "";
    $loan_amount = isset($request_array['loan_amount']) ? $request_array['loan_amount'] : "";
    $cust_bank_account_no = isset($request_array['cust_bank_account_no']) ? $request_array['cust_bank_account_no'] : "";
    $loan_no = isset($request_array['loan_no']) ? $request_array['loan_no'] : "";
    $repayment_amount = isset($request_array['repayment_amount']) ? $request_array['repayment_amount'] : "";
    $loan_recommended = isset($request_array['loan_recommended']) ? $request_array['loan_recommended'] : "";
    $received_amount = isset($request_array['received_amount']) ? $request_array['received_amount'] : "";
    $repayment_date = isset($request_array['repayment_date']) ? $request_array['repayment_date'] : "";
    $pending_days = isset($request_array['pending_days']) ? $request_array['pending_days'] : "";
    $esign_link = isset($request_array['esign_link']) ? $request_array['esign_link'] : "";
    $ekyc_link = isset($request_array['ekyc_link']) ? $request_array['ekyc_link'] : "";
    $repayment_link_generate = isset($request_array['repayment_link_generate']) ? $request_array['repayment_link_generate'] : "";
    $upload_doc_link = isset($request_array['upload_doc_link']) ? $request_array['upload_doc_link'] : "";


    if (empty($mobile)) {
        throw new Exception('Mobile number is blank.');
    }

    $template_id = '';
    $input_message = '';
	$headerId = "";
    if (($sms_type_id == 1)) {
        if (empty($otp) || empty($cust_name)) {
            throw new Exception('Customer Name or OTP is blank.');
        }
        $template_id = "1707171353065890872";
        $headerId = "KASARC";
        $input_message = "Dear customer, $otp is the OTP for your login at SalaryOnTime. In case you have not requested this, please contact us at info@salaryontime.com KASAR";
    } else if ( ($sms_type_id == 2)) {

		if (empty($cust_name)) {
			throw new Exception('Customer Name is blank.');
		}
		$template_id = "1707172138972104046";
		$headerId = "KASRCL";
		$input_message = "Dear $cust_name, Amount of Rs $repayment_amount due on $repayment_date against LAN $loan_no. Please pay using Link https://bit.ly/4dmIpMG & save on interest amount. Ignore if paid. Kasar";

    }
	$template_id = !empty($template_id) ? $template_id : "1707171353065890872";
    $apiUrl = $apiConfig["ApiUrl"];
    $sms_username = urlencode($apiConfig["username"]);
    $sms_password = urlencode($apiConfig["password"]);
    $sms_type = $apiConfig["type"];
    $dlr = $apiConfig["dlr"];
    $sms_entityid = $apiConfig["entityid"];
    $message = urlencode($input_message);
 
    if ($headerId == 'KASARC' ) {
        $apiData = "username=$sms_username&apikey=3jhWMzQj9tmG&senderid=KASARC&route=TRANS&mobile=$mobile&text=$message&TID=$template_id&PEID=1701171300552626064&format=json";
    } else if ($headerId == 'KASRCL' ) {
        $apiData = "username=$sms_username&apikey=3jhWMzQj9tmG&senderid=KASRCL&route=TRANS&mobile=$mobile&text=$message&TID=$template_id&PEID=1701171300552626064&format=json";
    } else {
        throw new Exception('Invalid sender ID.');
    }

    $apiRequestDateTime = date("Y-m-d H:i:s");

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiUrl,
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
