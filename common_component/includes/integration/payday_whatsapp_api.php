<?php

function payday_whatsapp_api($type_id = 0, $lead_id = 0, $request_array = array()) {

    $responseArray = array("status" => 0, "errors" => "");
    if (!empty($type_id)) {
        $responseArray = whatsapp_api_call($type_id, $lead_id, $request_array);
    } else {
        $responseArray["errors"] = "Type id is can not be blank.";
    }

    return $responseArray;
}

function whatsapp_api_call($templete_type_id, $lead_id = 0, $request_array = array()) {

    $response_array = array("status" => 0, "errors" => "");

    $apiStatusId = 0;
    $apiResponseJson = "";
    $apiRequestDateTime = date("Y-m-d H:i:s");
    $apiResponseDateTime = "";
    $apiResponseData = "";
    $errorMessage = "";
    $curlError = "";

    $hardcode_response = false;

    $debug = !empty($_REQUEST['bltest']) ? 1 : 0;
    $debug = 1;

    $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : 0;

    $leadModelObj = new LeadModel();

    try {

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

        $repayment_amount = !empty($app_data['repayment_amount']) ? trim($app_data['repayment_amount']) : "###";
        $repayment_date = !empty($app_data['repayment_date']) ? trim(strtoupper($app_data['repayment_date'])) : "###";
        $loan_recommended = !empty($app_data['loan_recommended']) ? trim(strtoupper($app_data['loan_recommended'])) : "";
        $due_amount = $loan_recommended - $repayment_amount;
        $customer_full_name = $first_name . (!empty($middle_name) ? " " . $middle_name : "") . (!empty($sur_name) ? " " . $sur_name : "");
        $loan_no = !empty($app_data['loan_no']) ? trim($app_data['loan_no']) : "###";

        $mobile = !empty($app_data['mobile']) ? $app_data['mobile'] : "";

        $reference_no = !empty($app_data['lead_reference_no']) ? $app_data['lead_reference_no'] : "";

        $apiRequestDateTime = date("Y-m-d H:i:s");
        $request_data = array();
        if ($templete_type_id == 1) {
            $request_data = json_encode(array(
                "apiKey" => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjY2ZWU3ZDQ4YjJlNjRjMGI3ODU2NDJiMiIsIm5hbWUiOiIgS2FzYXIgQ3JlZGl0ICYgQ2FwaXRhbCBQcml2YXRlIExpbWl0ZWQiLCJhcHBOYW1lIjoiQWlTZW5zeSIsImNsaWVudElkIjoiNjZlZTdkNDdiMmU2NGMwYjc4NTY0MmFjIiwiYWN0aXZlUGxhbiI6IkJBU0lDX01PTlRITFkiLCJpYXQiOjE3Mjc2Nzg2NDR9.aDH__mf-dbvXhMZv0W6Q9dbnA8T0ONrBeBxhLHy8ves",
                "campaignName" => "Repayment_reminder",
                "destination" => "+91" . trim($mobile),
                "userName" => "rohtash@salaryontime.com",
                "source" => "any",
                "templateParams" => [
                    $customer_full_name,
                    $repayment_amount,
                    $repayment_date,
                    $loan_no
                ],
                "attributes" => new stdClass()
            ));
        } elseif ($templete_type_id == 2) {
            $request_data = '{
                "apiKey": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjY2ZWU3ZDQ4YjJlNjRjMGI3ODU2NDJiMiIsIm5hbWUiOiIgS2FzYXIgQ3JlZGl0ICYgQ2FwaXRhbCBQcml2YXRlIExpbWl0ZWQiLCJhcHBOYW1lIjoiQWlTZW5zeSIsImNsaWVudElkIjoiNjZlZTdkNDdiMmU2NGMwYjc4NTY0MmFjIiwiYWN0aXZlUGxhbiI6IkJBU0lDX01PTlRITFkiLCJpYXQiOjE3Mjc2Nzg2NDR9.aDH__mf-dbvXhMZv0W6Q9dbnA8T0ONrBeBxhLHy8ves",
                "campaignName": "incomplete_journey_notification",
                "destination":  "91' . $mobile . ',
                "userName": " Kasar Credit & Capital Private Limited",
                "templateParams": [],
                "source": "new-landing-page form",
                "media": {},
                "buttons": [],
                "carouselCards": [],
                "location": {},
                "paramsFallbackValue": {}
              }';
        } else {
            throw new Exception("Template type id is not valid.");
        }

        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://backend.aisensy.com/campaign/t1/api/v2',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => $request_data,
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json',
        //     ),
        // ));
        // Initialize a cURL session

        $curl = curl_init('https://backend.aisensy.com/campaign/t1/api/v2');

        // Set the cURL options
        curl_setopt($curl, CURLOPT_POST, 1); // Set the method to POST
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); // Set the headers
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request_data); // Attacurl the JSON-encoded data
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return the response instead of outputting

        $response = curl_exec($curl);
        print_r("response", $response);
        if (curl_errno($curl)) {
            $curlError = curl_error($curl);
            curl_close($curl);
            throw new RuntimeException("Something went wrong. Please try after sometime. Error: $curlError");
        } else {
            curl_close($curl);
            $apiResponseData = json_decode($response, true);


            if (!empty($apiResponseData)) {
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
    $insertApiLog['whatsapp_request'] = $request_data;
    $insertApiLog['whatsapp_response'] = $apiResponseJson;
    // $insertApiLog['whatsapp_template_id'] = $templateId;
    $insertApiLog['whatsapp_api_status_id'] = $apiStatusId;
    $insertApiLog['whatsapp_lead_id'] = $lead_id;
    $insertApiLog['whatsapp_user_id'] = $user_id;
    $insertApiLog['whatsapp_errors'] = $errorMessage;
    $insertApiLog['whatsapp_created_on'] = date("Y-m-d H:i:s");

    //$leadModelObj->insertTable("api_whatsapp_logs", $insertApiLog);

    $response_array['status'] = $apiStatusId;
    $response_array['mobile'] = $mobile;
    $response_array['errors'] = $errorMessage;

    if ($debug == 1) {
        $response_array['request_json'] = $request_data;
        $response_array['response_json'] = $apiResponseJson;
    }

    return $response_array;
}
