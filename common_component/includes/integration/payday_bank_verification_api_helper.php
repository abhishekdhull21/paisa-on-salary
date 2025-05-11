<?php

function bank_account_verification_api_call($method_name = "", $lead_id = 0, $request_array = array()) {

    $responseArray = array("status" => 0, "error_msg" => "");

    $opertion_array = array(
        "BANK_ACCOUNT_VERIFICATION" => 1,
    );

    $method_id = $opertion_array[$method_name];

    if ($method_id == 1) {
        $responseArray = digitap_bank_account_verification_api($lead_id, $request_array);
        // $responseArray = signzy_bank_account_verification_api($lead_id, $request_array);
    } else {
        $responseArray["error_msg"] = "invalid operation called";
    }

    return $responseArray;
}

function signzy_bank_account_verification_api($lead_id, $request_array = array()) {

    require_once (COMP_PATH . '/includes/integration/integration_config.php');

    $envSet = COMP_ENVIRONMENT;

    $apiStatusId = 0;
    $apiRequestJson = "";
    $apiResponseJson = "";
    $apiRequestDateTime = date("Y-m-d H:i:s");
    $apiResponseDateTime = "";
    $errorMessage = "";
    $curlError = "";

    $type = "SIGNZY_API";
    $sub_type = "BANK_ACCOUNT_VERIFICATION";

    $hardcode_response = false;

//    if ($envSet == 'development') {
//        $hardcode_response = true;
//    }

    $debug = !empty($_REQUEST['lwtest']) ? 1 : 0;

    $applicationDetails = [];

    $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : NULL; //for testing

    $cust_banking_id = !empty($request_array['cust_banking_id']) ? $request_array['cust_banking_id'] : "";

    $beneAccNo = "";
    $beneIFSC = "";
    $beneName = "";

    $leadModelObj = new LeadModel();

    try {


        $apiConfig = integration_config($type, $sub_type);

        if ($debug == 1) {
            echo "<pre>";
            print_r($apiConfig);
        }
        if ($apiConfig['Status'] != 1) {
            throw new Exception($apiConfig['ErrorInfo']);
        }

        $apiUrl = $apiConfig["ApiUrl"];

        if (empty($lead_id)) {
            throw new Exception("Missing lead id.");
        }

        if (empty($cust_banking_id)) {
            throw new Exception("Missing customer banking id.");
        }

        $appDataReturnArr = $leadModelObj->getLeadDetails($lead_id);

        if ($appDataReturnArr['status'] !== 1) {
            throw new Exception("Application details cannot be empty..");
        }

        if (empty($appDataReturnArr['app_data'])) {
            throw new Exception("Application details cannot be empty.");
        }

        $applicationDetails = $appDataReturnArr['app_data'];

        $bankingDataReturnArr = $leadModelObj->getCustomerBankAccountDetails($lead_id, $cust_banking_id);

        if ($bankingDataReturnArr['status'] !== 1) {
            throw new Exception("Customer banking details not found..");
        }

        if (empty($bankingDataReturnArr['banking_data'])) {
            throw new Exception("Customer banking details not found.");
        }

        $bankingDetails = $bankingDataReturnArr['banking_data'];
        
       

        if ($bankingDetails['account_status_id'] == 1) {
            throw new Exception("Customer banking already verified.");
        }
        

        $beneName = !empty($bankingDetails["beneficiary_name"]) ? $bankingDetails["beneficiary_name"] : "";
        $beneAccNo = !empty($bankingDetails["account"]) ? $bankingDetails["account"] : "";
        $beneIFSC = !empty($bankingDetails["ifsc_code"]) ? $bankingDetails["ifsc_code"] : "";
        $beneMobile = !empty($applicationDetails["mobile"]) ? $applicationDetails["mobile"] : "";
        $beneEmail = !empty($applicationDetails["email"]) ? $applicationDetails["email"] : "";
        
       
        if (empty($beneName)) {
            throw new Exception("Missing beneficiary name.");
        }

        if (empty($beneAccNo)) {
            throw new Exception("Missing beneficiary account number.");
        }

        if (empty($beneIFSC)) {
            throw new Exception("Missing beneficiary ifsc code.");
        }

        // $token_return_array = signzy_token_api_call(1, $lead_id, $request_array);

        // if ($token_return_array['status'] == 1) {
        //     $token_string = $token_return_array['token'];
        //     $token_return_user_id = $token_return_array['token_user_id'];
        // } else {
        //     throw new Exception($token_return_array['errors']);
        // }

        // $apiUrl = $apiConfig["ApiUrl"] = str_replace('customerid', $token_return_user_id, $apiConfig["ApiUrl"]);

        // $apiRequestJson = '{
        //                         "task" : "bankTransferLite",
        //                         "essentials": {
        //                             "beneficiaryAccount": "' . $beneAccNo . '",
        //                             "beneficiaryName": "' . $beneName . '",
        //                             "beneficiaryIFSC": "' . $beneIFSC . '",
        //                             "nameFuzzy" : "true",
        //                             "beneficiaryMobile": "' . $beneMobile . '",
        //                             "email" : "' . $beneEmail . '"
        //                         }
        //                     }';

        $apiRequestJson = preg_replace("!\s+!", " ", $apiRequestJson);

        // $apiHeaders = array(
        //     "content-type: application/json",
        //     "accept-language: en-US,en;q=0.8",
        //     "accept: */*",
        //     "Authorization: $token_string"
        // );
        
        // $apiHeaders = array(
        //     "content-type: application/json",
        //     "accept-language: en-US,en;q=0.8",
        //     "accept: */*",
        //     "Authorization: ScTTTviEmhU1EPT79VM6QV9NUHImPkBm"
        // );

        if ($debug == 1) {
            echo "<br/><br/> =======Request Header======<br/><br/>" . json_encode($apiHeaders);
            echo "<br/><br/> =======Request Plain======<br/><br/>" . $apiRequestJson;
        }

        // if ($hardcode_response && $envSet == 'development') {
        //     $apiResponseJson = '';
        // } else {
        //     $curl = curl_init($apiUrl);
        //     curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        //     curl_setopt($curl, CURLOPT_HTTPHEADER, $apiHeaders);
        //     curl_setopt($curl, CURLOPT_POST, true);
        //     curl_setopt($curl, CURLOPT_POSTFIELDS, $apiRequestJson);
        //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        //     curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        //     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        //     $apiResponseJson = curl_exec($curl);
        // }

        $apiResponseDateTime = date("Y-m-d H:i:s");
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.signzy.app/api/v3/bankaccountverification/bankaccountverifications',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>' {
                                    "beneficiaryAccount": "' . $beneAccNo . '",
                                    "beneficiaryName": "' . $beneName . '",
                                    "beneficiaryIFSC": "' . $beneIFSC . '",
                                    "nameFuzzy" : "true",
                                    "beneficiaryMobile": "' . $beneMobile . '",
                                    "email" : "' . $beneEmail . '"
                                }',
          CURLOPT_HTTPHEADER => array(
            'Authorization: ScTTTviEmhU1EPT79VM6QV9NUHImPkBm',
            'Content-Type: application/json'
          ),
        ));

        $apiResponseJson = curl_exec($curl);
       
//  echo '<pre>';
//         print_r($apiResponseJson); die;

        if (curl_errno($curl) && !$hardcode_response) {
            $curlError = "(" . curl_errno($curl) . ") " . curl_error($curl) . " to url " . $apiUrl;
            curl_close($curl);
            throw new RuntimeException("Something went wrong. Please try after sometime.");
        } else {

            if (isset($curl)) {
                curl_close($curl);
            }

            $apiResponseJson = preg_replace("!\s+!", " ", $apiResponseJson);

            if ($debug == 1) {
                echo "<br/><br/> =======Response Plain ======<br/><br/>" . $apiResponseJson;
            }

            $apiResponseData = json_decode($apiResponseJson, true);
            
            // print_r($apiResponseData);

            if (!empty($apiResponseData)) {

                $apiResponseData = common_trim_data_array($apiResponseData);

                if (isset($apiResponseData['result']['active']) && $apiResponseData['result']['active'] == 'yes' && trim($apiResponseData['result']['bankTransfer']['response']) == 'Transaction Successful') {
                    $apiStatusId = 1;
                    $apiNameMatch = $apiResponseData['result']['nameMatch'];
                    $apiNameMatchScore = $apiResponseData['result']['nameMatchScore'];
                    $apiBeneName = $apiResponseData['result']['bankTransfer']['beneName'];
                } else if (isset($apiResponseData['result']['reason']) && !empty($apiResponseData['result']['reason'])) {
                    throw new ErrorException($apiResponseData['result']['reason']);
                } else if (isset($apiResponseData['error']['message']) && !empty($apiResponseData['error']['message'])) {
                    throw new ErrorException($apiResponseData['error']['message']);
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
    $insertApiLog["bav_user_id"] = $user_id;
    $insertApiLog["bav_provider_id"] = 2;
    $insertApiLog["bav_method_id"] = 2;
    $insertApiLog["bav_lead_id"] = !empty($lead_id) ? $lead_id : NULL;
    $insertApiLog["bav_cust_banking_id"] = !empty($cust_banking_id) ? $cust_banking_id : NULL;
    $insertApiLog["bav_api_status_id"] = $apiStatusId;
    $insertApiLog["bav_request"] = addslashes($apiRequestJson);
    $insertApiLog["bav_response"] = addslashes($apiResponseJson);
    $insertApiLog["bav_errors"] = ($apiStatusId == 3) ? addslashes($curlError) : addslashes($errorMessage);
    $insertApiLog["bav_request_datetime"] = $apiRequestDateTime;
    $insertApiLog["bav_response_datetime"] = !empty($apiResponseDateTime) ? $apiResponseDateTime : date("Y-m-d H:i:s");

    $return_log_id = $leadModelObj->insertTable("api_bank_account_verification_logs", $insertApiLog);

    if (!empty($applicationDetails["lead_status_id"]) && $applicationDetails["lead_status_id"] > 0) {

        if ($apiStatusId == 1) {
            $call_description = "Signzy Penny Drop API(Success) <br> Account Number : $beneAccNo";
            $call_description .= " <br> Valid : " . $apiResponseData['result']['active'];
//            $call_description .= " <br> NameMatch : " . $apiNameMatch;
//            $call_description .= " <br> NameMatchScore : " . $apiNameMatchScore;
            $call_description .= " <br> Return BeneName : " . $apiBeneName;

            $leadModelObj->updateTable('customer_banking', ['beneficiary_name' => $apiBeneName], ' id=' . $cust_banking_id);
        } else {
            $call_description = "Signzy Penny Drop API(Fail) <br> Account Number : $beneAccNo <br> Error : $errorMessage";
        }

        $leadModelObj->insertApplicationLog($lead_id, $applicationDetails["lead_status_id"], $call_description);
    }

    $returnResponseData = array();
    $returnResponseData['status'] = $apiStatusId;
    $returnResponseData['log_id'] = $return_log_id;
    $returnResponseData['error_msg'] = !empty($errorMessage) ? $errorMessage : "";

    if ($debug == 1) {
        $returnResponseData['actual_error'] = $insertApiLog["bav_errors"];
        $returnResponseData['raw_request'] = $apiRequestJson;
        $returnResponseData['raw_response'] = $apiResponseJson;
        $returnResponseData['parse_response'] = $apiResponseData;
    }


    return $returnResponseData;
}

function digitap_bank_account_verification_api($lead_id, $request_array = array()) {

    require_once (COMP_PATH . '/includes/integration/integration_config.php');

    $envSet = COMP_ENVIRONMENT;

    $apiStatusId = 0;
    $apiRequestJson = "";
    $apiResponseJson = "";
    $apiRequestDateTime = date("Y-m-d H:i:s");
    $apiResponseDateTime = "";
    $errorMessage = "";
    $curlError = "";

    $type = "DIGITAP_API";
    $sub_type = "BANK_ACCOUNT_VERIFICATION";

    $hardcode_response = false;

//    if ($envSet == 'development') {
//        $hardcode_response = true;
//    }

    $debug = !empty($_REQUEST['lwtest']) ? 1 : 0;

    $applicationDetails = [];

    $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : NULL; //for testing

    $cust_banking_id = !empty($request_array['cust_banking_id']) ? $request_array['cust_banking_id'] : "";

    $beneAccNo = "";
    $beneIFSC = "";
    $beneName = "";

    $leadModelObj = new LeadModel();

    try {


        $apiConfig = integration_config($type, $sub_type);

        if ($debug == 1) {
            echo "<pre>";
            print_r($apiConfig);
        }
        if ($apiConfig['Status'] != 1) {
            throw new Exception($apiConfig['ErrorInfo']);
        }

        $apiUrl = $apiConfig["ApiUrl"];

        if (empty($lead_id)) {
            throw new Exception("Missing lead id.");
        }

        if (empty($cust_banking_id)) {
            throw new Exception("Missing customer banking id.");
        }

        $appDataReturnArr = $leadModelObj->getLeadDetails($lead_id);

        if ($appDataReturnArr['status'] !== 1) {
            throw new Exception("Application details cannot be empty..");
        }

        if (empty($appDataReturnArr['app_data'])) {
            throw new Exception("Application details cannot be empty.");
        }

        $applicationDetails = $appDataReturnArr['app_data'];

        $bankingDataReturnArr = $leadModelObj->getCustomerBankAccountDetails($lead_id, $cust_banking_id);

        if ($bankingDataReturnArr['status'] !== 1) {
            throw new Exception("Customer banking details not found..");
        }

        if (empty($bankingDataReturnArr['banking_data'])) {
            throw new Exception("Customer banking details not found.");
        }

        $bankingDetails = $bankingDataReturnArr['banking_data'];
        
       

        if ($bankingDetails['account_status_id'] == 1) {
            throw new Exception("Customer banking already verified.");
        }
        

        $beneName = !empty($bankingDetails["beneficiary_name"]) ? $bankingDetails["beneficiary_name"] : "";
        $beneAccNo = !empty($bankingDetails["account"]) ? $bankingDetails["account"] : "";
        $beneIFSC = !empty($bankingDetails["ifsc_code"]) ? $bankingDetails["ifsc_code"] : "";
        $beneMobile = !empty($applicationDetails["mobile"]) ? $applicationDetails["mobile"] : "";
        $beneEmail = !empty($applicationDetails["email"]) ? $applicationDetails["email"] : "";
        
       
        if (empty($beneName)) {
            throw new Exception("Missing beneficiary name.");
        }

        if (empty($beneAccNo)) {
            throw new Exception("Missing beneficiary account number.");
        }

        if (empty($beneIFSC)) {
            throw new Exception("Missing beneficiary ifsc code.");
        }


        $apiRequestJson = preg_replace("!\s+!", " ", $apiRequestJson);

        if ($debug == 1) {
            echo "<br/><br/> =======Request Header======<br/><br/>" . json_encode($apiHeaders);
            echo "<br/><br/> =======Request Plain======<br/><br/>" . $apiRequestJson;
        }


        $apiResponseDateTime = date("Y-m-d H:i:s");
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "accNo": "' . $beneAccNo . '",
                "benificiaryName": "' . $beneName . '",
                "ifsc": "' . $beneIFSC . '"
            }',
            CURLOPT_HTTPHEADER => array(
                'ent_authorization: ' . getenv("DIGITAP_ACCESS_KEY"), // No quotes around value
                'Content-Type: application/json'
            ),
        ));

        $apiResponseJson = curl_exec($curl);

 // ==== (1) Handle cURL Errors First ====
        if (curl_errno($curl)) {
            $curlErrNo = curl_errno($curl);
            $curlError = curl_error($curl);
            
            // Map cURL error codes to user-friendly messages
            $errorMap = [
                6  => "Could not resolve server. Check your internet connection.", // CURLE_COULDNT_RESOLVE_HOST
                7  => "Failed to connect to the server. It may be down.",         // CURLE_COULDNT_CONNECT
                28 => "Request timed out. Please try again later.",               // CURLE_OPERATION_TIMEDOUT
                35 => "SSL/TLS handshake failed. Contact support.",               // CURLE_SSL_CONNECT_ERROR
            ];
            
            $userMessage = $errorMap[$curlErrNo] ?? "A technical error occurred (Code: $curlErrNo).";
            $logMessage = "cURL Error ($curlErrNo): $curlError | URL: " . $apiUrl;
            
            // Log technical details (for debugging)
            error_log($logMessage);
            
            // Throw user-friendly exception
            throw new RuntimeException($userMessage);
        }
        // echo "curl". curl_getinfo($curl);


        // ==== (2) Handle JSON Decoding Errors ====
        $apiResponseData = json_decode($apiResponseJson, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("Invalid API response format. Please contact support.");
        }

        // ==== (3) Handle API Business Logic Errors ====
        if (empty($apiResponseData['model']['status']) || $apiResponseData['model']['status'] !== 'SUCCESS') {
            $apiError = $apiResponseData['error']['message'] ??  $apiResponseData['message'] ?? "Unknown API error". print_r($apiResponseData);
            throw new RuntimeException("API Error: " . $apiError);
        }


            if (isset($curl)) {
                curl_close($curl);
            }

            if ($debug == 1) {
                echo "<br/><br/> =======Response Plain ======<br/><br/>" . $apiResponseJson;
            }

            $apiResponseData = json_decode($apiResponseJson, true);
            
            if (!empty($apiResponseData)) {
                $apiResponseData = common_trim_data_array($apiResponseData);
            
                if (isset($apiResponseData['model']['status']) && $apiResponseData['model']['status'] == 'SUCCESS') {
                    $apiStatusId = 1;
                    $apiNameMatch = $apiResponseData['model']['isNameMatch'];
                    $apiNameMatchScore = $apiResponseData['model']['matchingScore'];
                    $apiBeneName = $apiResponseData['model']['beneficiaryName'];
                } else if (isset($apiResponseData['model']['status']) && $apiResponseData['model']['status'] != 'SUCCESS') {
                    throw new ErrorException("API returned status: " . $apiResponseData['model']['status']);
                } else if (isset($apiResponseData['error']['message']) && !empty($apiResponseData['error']['message'])) {
                    throw new ErrorException($apiResponseData['error']['message']);
                } else {
                    throw new ErrorException("Some error occurred. Please try again. Setp: 1");
                }
            } else {
                throw new ErrorException("Some error occurred. Please try again..");
            }
        // }
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
    $insertApiLog["bav_user_id"] = $user_id;
    $insertApiLog["bav_provider_id"] = 2;
    $insertApiLog["bav_method_id"] = 2;
    $insertApiLog["bav_lead_id"] = !empty($lead_id) ? $lead_id : NULL;
    $insertApiLog["bav_cust_banking_id"] = !empty($cust_banking_id) ? $cust_banking_id : NULL;
    $insertApiLog["bav_api_status_id"] = $apiStatusId;
    $insertApiLog["bav_request"] = addslashes($apiRequestJson);
    $insertApiLog["bav_response"] = addslashes($apiResponseJson);
    $insertApiLog["bav_errors"] = ($apiStatusId == 3) ? addslashes($curlError) : addslashes($errorMessage);
    $insertApiLog["bav_request_datetime"] = $apiRequestDateTime;
    $insertApiLog["bav_response_datetime"] = !empty($apiResponseDateTime) ? $apiResponseDateTime : date("Y-m-d H:i:s");

    $return_log_id = $leadModelObj->insertTable("api_bank_account_verification_logs", $insertApiLog);

    if (!empty($applicationDetails["lead_status_id"]) && $applicationDetails["lead_status_id"] > 0) {

        if ($apiStatusId == 1) {
            $call_description = "DIGITAP Penny Drop API(Success) <br> Account Number : $beneAccNo";
            $call_description .= " <br> Status : " . $apiResponseData['model']['status'];
            $call_description .= " <br> NameMatch : " . ($apiNameMatch ? 'Yes' : 'No');
            $call_description .= " <br> MatchingScore : " . $apiNameMatchScore;
            $call_description .= " <br> Return BeneName : " . $apiBeneName;
        
            $leadModelObj->updateTable('customer_banking', ['beneficiary_name' => $apiBeneName], ' id=' . $cust_banking_id);
        } else {
            $call_description = "DIGITAP Penny Drop API(Fail) <br> Account Number : $beneAccNo <br> Error : $errorMessage";
        }
  

        $leadModelObj->insertApplicationLog($lead_id, $applicationDetails["lead_status_id"], $call_description);
    }

    $returnResponseData = array();
    $returnResponseData['status'] = $apiStatusId;
    $returnResponseData['log_id'] = $return_log_id;
    $returnResponseData['error_msg'] = !empty($errorMessage) ? $errorMessage : "";

    if ($debug == 1) {
        $returnResponseData['actual_error'] = $insertApiLog["bav_errors"];
        $returnResponseData['raw_request'] = $apiRequestJson;
        $returnResponseData['raw_response'] = $apiResponseJson;
        $returnResponseData['parse_response'] = $apiResponseData;
    }   

    return $returnResponseData;
}
