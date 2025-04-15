<?php

function payday_whatsapp_api($type_id = 0, $lead_id = 0, $request_array = array()) {

    common_log_writer(7, "Whatapp Send started | $lead_id | $type_id");

    $responseArray = array("status" => 0, "errors" => "");

    if (!empty($type_id)) {
        $responseArray = whatsapp_api_call($type_id, $lead_id, $request_array);
    } else {
        $responseArray["errors"] = "Type id is can not be blank.";
    }

    common_log_writer(6, "SMS Send end | $lead_id | $type_id | " . json_encode($responseArray));

    return $responseArray;
}

function whatsapp_api_call($templete_type_id, $lead_id = 0, $request_array = array()) {

    common_log_writer(6, "whatsapp_api_call started | $lead_id");

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
    $template_id = "";
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
    $type = "WHATSAPP_API";
    $api_sub_type = "";

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

        if (empty($lead_id)) {
            throw new Exception('Lead is black.');
        }

        $LeadDetails = $leadModelObj->getLeadFullDetails($lead_id);

        if ($LeadDetails['status'] != 1) {
            throw new Exception("Application details not found");
        }

        $app_data = !empty($LeadDetails['app_data']) ? $LeadDetails['app_data'] : "";

        $first_name = !empty($app_data['first_name']) ? trim(strtoupper($app_data['first_name'])) : "";
        $middle_name = !empty($app_data['middle_name']) ? trim(strtoupper($app_data['middle_name'])) : "";
        $sur_name = !empty($app_data['sur_name']) ? trim(strtoupper($app_data['sur_name'])) : "";

        $customer_full_name = $first_name;
        $customer_full_name .= !empty($middle_name) ? " " . $middle_name : "";
        $customer_full_name .= !empty($sur_name) ? " " . $sur_name : "";

        $mobile = !empty($app_data['mobile']) ? $app_data['mobile'] : "";
        $reference_no = !empty($app_data['lead_reference_no']) ? $app_data['lead_reference_no'] : "";

        if ($templete_type_id == 1) {// Thank You Message
            if (empty($first_name)) {
                throw new Exception('Customer Name is blank.');
            } elseif (empty($reference_no)) {
                throw new Exception('Customer Reference is blank.');
            }

            $templateId = "thank_you";
            $parameters = array(
                "customer" => $first_name,
                "reference_no" => $reference_no
            );
        } elseif ($templete_type_id == 2) { // loan_eligibility
            $templateId = "loan_eligibility";
            $parameters = array(
                "website_link" => "https://www.salaryontime.com/apply-now"
            );
        } elseif ($templete_type_id == 3) { // affordable_loan
            $templateId = "affordable_loan";
            $parameters = array(
                "website_link" => "https://www.salaryontime.com/apply-now"
            );
        }

        $RequestData = array(
            "userDetails" => array(
                "number" => $mobile
            ),
            "notification" => array(
                "type" => "whatsapp",
                "sender" => "9717882592",
                "templateId" => $templateId,
                "language" => "en",
                "params" => $parameters
            )
        );

        $json_request = json_encode($RequestData);

        $apiUrl = $apiConfig["ApiUrl"];
        $token_string = $apiConfig["ApiKey"];

        $apiHeaders = array(
            "content-type: application/json",
            "x-api-key: $token_string"
        );

        $apiRequestDateTime = date("Y-m-d H:i:s");

        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $apiHeaders);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $apiResponseJson = curl_exec($curl);

        if ($debug == 1) {
            echo "<br/><br/> ====== = Response======<br/><br/>" . $apiResponseJson;
        }

        $apiResponseJson = preg_replace("!\s+! ", " ", $apiResponseJson);

        $apiResponseDateTime = date("Y-m-d H:i:s");

        if (!$hardcode_response && curl_errno($curl)) { // CURL Error
            $curlError = curl_error($curl);
            curl_close($curl);
            throw new RuntimeException("Something went wrong. Please try after sometimes.");
        } else {

            if (isset($curl)) {
                curl_close($curl);
            }

            $apiResponseData = json_decode($apiResponseJson, true);

            if (!empty($apiResponseData)) {

                $apiResponseData = $apiResponseData;

                if (!empty($apiResponseData['msgId'])) {
                    $apiStatusId = 1;
                } else if (!empty($apiResponseData['message'])) {
                    throw new ErrorException($apiResponseData['message']);
                } else {
                    throw new ErrorException("Some error occurred. Please try again.[2]");
                }
            } else {
                throw new ErrorException("Some error occurred. Please try again.[1]");
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
    $insertApiLog['whatsapp_provider'] = 1;
    $insertApiLog['whatsapp_type_id'] = $templete_type_id;
    $insertApiLog['whatsapp_mobile'] = $mobile;
    $insertApiLog['whatsapp_request'] = $json_request;
    $insertApiLog['whatsapp_response'] = $apiResponseJson;
    $insertApiLog['whatsapp_template_id'] = $templateId;
    $insertApiLog['whatsapp_api_status_id'] = $apiStatusId;
    $insertApiLog['whatsapp_lead_id'] = $lead_id;
    $insertApiLog['whatsapp_user_id'] = $user_id;
    $insertApiLog['whatsapp_errors'] = $errorMessage;
    $insertApiLog['whatsapp_created_on'] = date("Y-m-d H:i:s");

    $leadModelObj->insertTable("api_whatsapp_logs", $insertApiLog);

    $response_array['status'] = $apiStatusId;
    $response_array['mobile'] = $mobile;
    $response_array['errors'] = $errorMessage;

    if ($debug == 1) {
        $response_array['request_json'] = $json_request;
        $response_array['response_json'] = $apiResponseJson;
    }
    return $response_array;
}

?>
