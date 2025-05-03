
<?php


function aadhaar_digitap_api_call($method_name = "", $lead_id = 0, $request_array = array())
{
    //   print_r("testing");die;
    
    common_log_writer(5,  "Aadhaar DIGITAP verification started | $lead_id | $method_name");

    $opertion_array = array(
        "GENERATE_AADHAAR_OTP" => 1,
        "VERIFY_AADHAAR_OTP" => 2,
        // "DIGILOCKER_GET_FILES" => 3,
        // "DIGILOCKER_GET_EAADHAAR" => 4,
        // "DIGILOCKER_PULL_DOCS" => 5,
    );

    $method_id = $opertion_array[$method_name];

    if ($method_id == 1) {
        $responseArray = generateAadhaarWithDigitap( $lead_id, $request_array);
    } else if ($method_id == 2) {
        $responseArray = verifyAadhaarWithDigitap( $lead_id, $request_array);
    } else if ($method_id == 3) {
        $responseArray = digilocker_get_file_api_call($method_id, $lead_id, $request_array);
    } else if ($method_id == 4) {
        $responseArray = digilocker_get_eaadhaar_api_call($method_id, $lead_id, $request_array);
    } else {
        $responseArray["errors"] = "invalid opertation called";
    }

    common_log_writer(5, "Aadhaar DIGILOCKER API end | $lead_id | $method_name | " . json_encode($responseArray));

    return $responseArray;
}

function generateAadhaarWithDigitap($lead_id,$request_array = array()) {
    common_log_writer(5, "User Started verification and OTP generation | $lead_id");
    $clientRefNum =  uniqid(); // use provided or generate unique
    $aadhaar = $request_array["aadhaar"];
    // $url = 'https://svc.digitap.ai/validation/kyc/v1/aadhaar';
    $url = 'https://svc.digitap.ai/ent/v3/kyc/intiate-kyc-auto';
    $api_key = getenv("DIGITAP_ACCESS_KEY");
    $payload = json_encode([
        "uniqueId" => $clientRefNum,
        "uid" => $aadhaar
    ]);

    $headers = [
        'Authorization:'.$api_key,
        'Content-Type: application/json'
    ];

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => $headers,
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);

    curl_close($curl);

    if ($error) {
        return [
            'success' => false,
            'error' => "cURL Error: $error"
        ];
    }

    $decodedResponse = json_decode($response,  true);


    if ( $decodedResponse['code'] == 200) {
        return [
            'success' => true,
            'message' => $decodedResponse['model']['uidaiResponse']['message'],
            'data' => $decodedResponse['model']
        ];
    } else {
        return [
            'success' => false,
            'error' => $decodedResponse['message'] ?? $decodedResponse['msg'] ?? 'Unknown error occurred',
            'response' => $decodedResponse
        ];
    }


    // echo json_encode($response_array);
    
}

function verifyAadhaarWithDigitap($lead_id,$request_array = array()) {
    try{
    common_log_writer(5, "User Started verification and OTP verification | $lead_id");
    require_once (COMP_PATH . '/includes/integration/integration_config.php');

    $shareCode      = $request_array['shareCode'] ?? null;
    $otp            = $request_array['otp'] ?? null;
    $transactionId  = $request_array['transactionId'] ?? null;
    $codeVerifier   = $request_array['codeVerifier'] ?? null;
    $fwdp           = $request_array['fwdp'] ?? null;
    $validateXml    = $request_array['validateXml'] ?? null;

    $response_array = array("status" => 0, "errors" => "");

    // $envSet = COMP_ENVIRONMENT;
    $apiStatusId = 0;
    $apiRequestJson = "";
    $apiResponseJson = "";
    $apiRequestDateTime = date("Y-m-d H:i:s");
    $apiResponseDateTime = "";
    $apiResponseData = "";
    $errorMessage = "";
    $curlError = "";


    $hardcode_response = false;

    $debug = !empty($_REQUEST['lwtest']) ? 1 : 0;

    $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : 0;

    $leadModelObj = new LeadModel();

    $lead_status_id = 0;

    $first_name = "";
    $middle_name = "";
    $sur_name = "";
    $customer_full_name = "";
    $token_string = "";

    $aadhaar_no_last_4_digit = "";
    $aadhaar_no = "";
    $customer_dob = "";
    $lead_remarks= "";

    if (empty($lead_id)) {
        throw new Exception("Missing lead id.");
    }

    $LeadDetails = $leadModelObj->getLeadFullDetails($lead_id);

    if ($LeadDetails['status'] != 1) {
        throw new Exception("Application details not found");
    }

    $app_data = !empty($LeadDetails['app_data']) ? $LeadDetails['app_data'] : "";
    $lead_status_id = !empty($app_data['lead_status_id']) ? $app_data['lead_status_id'] : "";
    $customer_dob = !empty($app_data['dob']) ? date("d/m/Y", strtotime($app_data['dob'])) : "";

    $aadhaar_no = !empty($app_data['aadhar_no']) ? trim($app_data['aadhar_no']) : "";
    $customer_id = !empty($app_data['customer_id']) ? trim($app_data['customer_id']) : "";
    $pancard = !empty($app_data['pancard']) ? trim($app_data['pancard']) : "";
    $mobile = !empty($app_data['mobile']) ? trim($app_data['mobile']) : "";

    $customer_full_name = $first_name;
    $customer_full_name .= !empty($middle_name) ? " " . $middle_name : "";
    $customer_full_name .= !empty($sur_name) ? " " . $sur_name : "";

    // $digilockerDetails = $leadModelObj->getDigilockerApiLog($lead_id, 2);
    // $digilocker_log_data = !empty($digilockerDetails['digilocker_log_data']) ? $digilockerDetails['digilocker_log_data'] : "";
    // return $digilockerDetails ;
    $aadhaar_no_last_4_digit = $aadhaar_no;// $digilocker_log_data['ekyc_aadhaar_no'];
    // $aadhaar_no  = 5596;

    $url = 'https://svc.digitap.ai/ent/v3/kyc/submit-otp';
    $api_key = getenv("DIGITAP_ACCESS_KEY");
    $payload = json_encode([
        "shareCode"     => $shareCode,
        "otp"           => $otp,
        "transactionId" => $transactionId,
        "codeVerifier"  => $codeVerifier,
        "fwdp"          => $fwdp,
        "validateXml"   => $validateXml
    ]);

    $headers = [
        'Authorization:'.$api_key,
        'Content-Type: application/json'
    ];

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => $headers,
    ]);


    try {
        $apiResponseJson = curl_exec($curl);

        if ($debug == 1) {
            echo "<br/><br/> =======Response======<br/><br/>" . $apiResponseJson;
        }
    
        $apiResponseJson = preg_replace("!\s+!", " ", $apiResponseJson);
        $apiResponseDateTime = date("Y-m-d H:i:s");
    
        if (!$hardcode_response && curl_errno($curl)) {
            $curlError = curl_error($curl);
            curl_close($curl);
            throw new RuntimeException("Something went wrong. Please try again later.");
        } else {
            curl_close($curl);
            $apiResponseData = json_decode($apiResponseJson, true);
    
            if (!empty($apiResponseData) && $apiResponseData['code'] == "200" && $apiResponseData['msg'] == "success") {
                $aadhaarData = $apiResponseData['model'];
    
                if (!empty($aadhaarData['adharNumber']) && !empty($aadhaarData['dob'])) {
                    $apiStatusId = 1;
                } else {
                    throw new ErrorException("Aadhaar data (adharNumber or dob) missing in response.");
                }
            } elseif (!empty($apiResponseData['msg'])) {
                throw new ErrorException($apiResponseData['msg']);
            } else {
                throw new ErrorException("Unknown response format or error.");
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
    
    // If valid Aadhaar response received
    if ($apiStatusId == 1) {
        $lead_remarks = "DIGITAP Aadhaar API CALL(Success) | Aadhaar : $aadhaar_no_last_4_digit";
        $aadhaarDob = DateTime::createFromFormat('d-m-Y', $aadhaarData['dob']);
        $customerDob = DateTime::createFromFormat('d/m/Y', $customer_dob);
        // $customerDob = DateTime::createFromFormat('d/m/Y', "02/11/1998");
        
        $aadhaarDob = $aadhaarDob->format('Y-m-d');
        $customerDob =  $customerDob->format('Y-m-d');
        // Checking if UID and DOB match
        if (substr($aadhaarData['adharNumber'], -4) == $aadhaar_no && $aadhaarDob == $customerDob) {
            $apiStatusId = 5;
            $lead_remarks .= "";
    
            // Extracting address data from response
            $aadhaar_complete_address = $aadhaarData['address']['house'] . " " . $aadhaarData['address']['street'];
            $aadhaar_loc = $aadhaarData['address']['loc'] ?? '';
            $aadhaar_district = $aadhaarData['address']['dist'] ?? '';
            $aadhaar_state = $aadhaarData['address']['state'] ?? '';
            $aadhaar_pincode = $aadhaarData['address']['pc'] ?? '';
            $aadhaar_country = $aadhaarData['address']['country'] ?? '';
    
            // Extracting the image link if needed
            $aadhaar_image = $aadhaarData['image'] ?? '';
    
            // Fetch city and state details using the pincode
            $pincodeDetails = $leadModelObj->getCityStateByPincode($aadhaar_pincode);
            $m_state_id = $m_state_name = $m_city_id = $m_city_name = '';
    
            if (!empty($pincodeDetails) && $pincodeDetails['status'] == 1) {
                $m_state_id = $pincodeDetails["pincode_data"]["m_state_id"];
                $m_state_name = $pincodeDetails["pincode_data"]["m_state_name"];
                $m_city_id = $pincodeDetails["pincode_data"]["m_city_id"];
                $m_city_name = $pincodeDetails["pincode_data"]["m_city_name"];
            }
    
            // Prepare the Aadhaar data to be saved
            $aadhaar_array = [
                'aa_current_house' => $aadhaar_complete_address,
                'aa_current_locality' => $aadhaar_loc,
                'aa_current_landmark' => $aadhaarData['address']['landmark'],
                'aa_current_state' => $aadhaar_state,
                'aa_current_state_id' => $m_state_id,
                'aa_current_city' => $aadhaarData['address']['vtc'],
                'aa_current_city_id' => $m_city_id,
                'aa_cr_residence_pincode' => $aadhaar_pincode,
                'aa_current_district' => $aadhaar_district,
                // 'aa_current_country' => $aadhaar_country,
                'customer_digital_ekyc_flag' => 1,
                'customer_digital_ekyc_done_on' => date("Y-m-d H:i:s"),
                // 'aadhaar_image' => $aadhaar_image // If needed for the image
            ];
    
            $leadModelObj->updateLeadCustomerTable($lead_id, $aadhaar_array);
        } else {
            // Handle cases where Aadhaar number or DOB doesn't match
            if (substr($aadhaarData['adharNumber'], -4) != $aadhaar_no) {
                $apiStatusId = 7;
                $lead_remarks .= "<br/>Result: Aadhaar number mismatch.".$aadhaar_no." ".substr($aadhaarData['adharNumber'], -4);
            } elseif ($aadhaarDob != $customerDob) {
                $apiStatusId = 6;
                $lead_remarks .= "<br/>Result: Aadhaar DOB mismatch.".$aadhaarDob." ".$customerDob;
            } else {
                $apiStatusId = 8;
                $lead_remarks .= "<br/>Result: Aadhaar last four digits and DOB not matched.".substr($aadhaarData['adharNumber'], -4) . $aadhaar_no . $aadhaarDob . $customerDob;
            }
        }
    } else {
        $lead_remarks = "DigiTap Aadhaar API CALL(Failed) | Aadhaar : $aadhaar_no_last_4_digit | Error: " . $errorMessage;
    }
    
    $leadModelObj->insertApplicationLog($lead_id, $lead_status_id, $lead_remarks);
    
    $insertApiLog = [
        "ekyc_provider" => 1,
        "ekyc_method_id" => "VERIFY_AADHAAR_OTP",
        "ekyc_lead_id" => !empty($lead_id) ? $lead_id : NULL,
        "ekyc_api_status_id" => $apiStatusId,
        "ekyc_request" => addslashes($apiRequestJson),
        "ekyc_response" => addslashes($apiResponseJson),
        "ekyc_aadhaar_no" => $aadhaar_no_last_4_digit,
        "ekyc_errors" => ($apiStatusId == 3) ? addslashes($curlError) : addslashes($errorMessage ?? $lead_remarks),
        "ekyc_request_datetime" => $apiRequestDateTime,
        "ekyc_response_datetime" => !empty($apiResponseDateTime) ? $apiResponseDateTime : date("Y-m-d H:i:s"),
        "ekyc_user_id" => $user_id,
        // "ekyc_return_request_id" => $digilocker_request_id
    ];
    
    $leadModelObj->insertTable("api_ekyc_logs", $insertApiLog);
    
    // Preparing the response array
    $response_array = [
        'status' => $apiStatusId,
        // 'data' => $aadhaarData,
        'errors' => !empty($errorMessage) ? $errorMessage : "",
        'message' => $lead_remarks,
        'request_json' => $apiRequestJson,
        'response_json' => $apiResponseData,
        // 'aadhaar_image' => $aadhaar_image
    ];
    
    return ($response_array);
}catch(Exception $e){
    return array("success"=>false, "status"=>0, "message"=> $errorMessage = $e->getMessage());
}
    
}

function digilocker_create_url_api_call($method_id, $lead_id = 0, $request_array = array())
{

    common_log_writer(5, "digilocker_create_url_api_call started | $lead_id");

    require_once(COMP_PATH . '/includes/integration/integration_config.php');

    $response_array = array("status" => 0, "errors" => "");

    $envSet = COMP_ENVIRONMENT;
    $apiStatusId = 0;
    $apiRequestJson = "";
    $apiResponseJson = "";
    $apiRequestDateTime = date("Y-m-d H:i:s");
    $apiResponseDateTime = "";
    $apiResponseData = "";
    $errorMessage = "";
    $curlError = "";

    $type = "SIGNZY_API";
    $sub_type = "DIGILOCKER";

    $hardcode_response = false;

    $debug = !empty($_REQUEST['lwtest']) ? 1 : 0;

    $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : 0;

    $leadModelObj = new LeadModel();

    $aadhaar_no_last_4_digit = "";
    $lead_status_id = 0;

    $first_name = "";
    $middle_name = "";
    $sur_name = "";
    $customer_full_name = "";
    $token_string = "";

    $digilocker_request_id = "";
    $digilocker_url = "";

    $lw_redirect_url = COMP_CRM_URL . 'aadhaar-veri-response?refstr=' . $request_array['lead_id'];
    $lw_callback_url = "";

    try {


        $apiConfig = integration_config($type, $sub_type);

        if ($debug == 1) {
            echo "<pre>";
            print_r($apiConfig);
        }

        if ($apiConfig['Status'] != 1) {
            throw new Exception($apiConfig['ErrorInfo']);
        }


        if (empty($lead_id)) {
            throw new Exception("Missing lead id.");
        }

        $LeadDetails = $leadModelObj->getLeadFullDetails($lead_id);

        if ($LeadDetails['status'] != 1) {
            throw new Exception("Application details not found");
        }

        $app_data = !empty($LeadDetails['app_data']) ? $LeadDetails['app_data'] : "";

        $lead_status_id = !empty($app_data['lead_status_id']) ? $app_data['lead_status_id'] : "";

        $aadhaar_no_last_4_digit = !empty($app_data['aadhar_no']) ? trim($app_data['aadhar_no']) : "";

        //$aadhaar_no_last_4_digit = substr($aadhaar_no, 8, 4);


        if (empty($aadhaar_no_last_4_digit)) {
            throw new Exception("Missing aadhaar number last 4 digit.");
        }

        if (!empty($app_data['customer_digital_ekyc_flag']) && $app_data['customer_digital_ekyc_flag'] == 1) {
            throw new Exception("E-KYC already done.");
        }

        $digilockerDetails = $leadModelObj->getDigilockerApiLog($lead_id, 1);

        if ($digilockerDetails['status'] == 1) {

            $digilocker_log_data = $digilockerDetails['digilocker_log_data'];

            $tempApiResponseData = json_decode($digilocker_log_data['ekyc_response'], true);

            if (!empty($tempApiResponseData)) {

                $tempApiResponseData = common_trim_data_array($tempApiResponseData);

                if (!empty($tempApiResponseData)) {

                    if (isset($tempApiResponseData['result']) && !empty($tempApiResponseData['result'])) {

                        $tempApiResponseData = $tempApiResponseData['result'];

                        if (!empty($tempApiResponseData['requestId']) && !empty($tempApiResponseData['url'])) {
                            $response_array['status'] = 1;
                            $response_array['data'] = $tempApiResponseData;
                            $response_array['digilocker_url'] = $tempApiResponseData['url'];
                            $response_array['digilocker_request_id'] = $tempApiResponseData['requestId'];
                            return $response_array;
                        }
                    }
                }
            }
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
        //                     "task" : "url",
        //                     "essentials": {
        //                         "signup": false,
        //                         "redirectUrl": "' . $lw_redirect_url . '",
        //                         "redirectTime": "2",
        //                         "callbackUrl": "' . $lw_callback_url . '"
        //                     }
        //                   }';

        // $apiRequestJson = preg_replace("!\s+!", " ", $apiRequestJson);

        // if ($debug) {
        //     echo "<br/><br/>=======Request JSON=========<br/><br/>";
        //     echo $apiRequestJson;
        // }


        // $apiHeaders = array(
        //     "content-type: application/json",
        //     "accept-language: en-US,en;q=0.8",
        //     "accept: */*",
        //     "Authorization: $token_string"
        // );

        if ($debug) {
            echo "<br/><br/>=======Request Header=========<br/><br/>";
            echo json_encode($apiHeaders);
        }

        $apiRequestDateTime = date("Y-m-d H:i:s");

        // $curl = curl_init($apiUrl);
        // curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $apiHeaders);
        // curl_setopt($curl, CURLOPT_POST, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $apiRequestJson);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        // curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        // $apiResponseJson = curl_exec($curl);

        $validity_period = 86400; // 24 hours
        $expiry_timestamp = time() + $validity_period;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.signzy.app/api/v3/digilocker/createUrl',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "signup": true,
            "redirectUrl": "https://paisaonsalary.in/aadhaar-veri-response?refstr=' . $lead_id . '",
            "redirectTime": "1",
            "callbackUrl": "https://paisaonsalary.in/aadhaar-veri-response?refstr=' . $lead_id . '",
            "customerId": "<customer ID>",
            "successRedirectUrl": "https://paisaonsalary.in/aadhaar-veri-response?refstr=' . $lead_id . '",
            "successRedirectTime": "5",
            "failureRedirectUrl": "https://www.signzy.com/",
            "failureRedirectTime": "5",
            "logoVisible": "true",
            "logo": "https://rise.barclays/content/dam/thinkrise-com/images/rise-stories/Signzy-16_9.full.high_quality.jpg",
            "supportEmailVisible": "true",
            "supportEmail": "support@signzy.com",
            "docType": [
                "PANCR",
                "ADHAR"
            ],
            "purpose": "kyc",
            "getScope": true,
            "consentValidTill": ' . $expiry_timestamp . ',
            "showLoaderState": true,
            "internalId": "<Internal ID>",
            "companyName": "Signzy",
            "favIcon": "https://rise.barclays/content/dam/thinkrise-com/images/rise-stories/Signzy-16_9.full.high_quality.jpg"
        }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: ScTTTviEmhU1EPT79VM6QV9NUHImPkBm',
                'Content-Type: application/json'
            ),
        ));


        $apiResponseJson = curl_exec($curl);

        //curl_close($curl);
        // echo $apiResponseJson;die;


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

            $apiResponseData = json_decode($apiResponseJson, true);

            if (!empty($apiResponseData)) {

                $apiResponseData = common_trim_data_array($apiResponseData);

                if (!empty($apiResponseData)) {

                    if (isset($apiResponseData['result']) && !empty($apiResponseData['result'])) {

                        $apiResponseData = $apiResponseData['result'];

                        if (!empty($apiResponseData['requestId']) && !empty($apiResponseData['url'])) {
                            $apiStatusId = 1;
                            $digilocker_request_id = $apiResponseData['requestId'];
                            $digilocker_url = $apiResponseData['url'];
                        } else {
                            throw new ErrorException("Digilocker url does not received from API.");
                        }
                    } else if (isset($apiResponseData['error']['message']) && !empty($apiResponseData['error']['message'])) {
                        throw new ErrorException($apiResponseData['error']['message']);
                    } else {
                        $tmp_error_msg = "Some error occurred. Please try again.";
                        throw new ErrorException($tmp_error_msg);
                    }
                } else {
                    throw new ErrorException("Some error occurred. Please try again..");
                }
            } else {
                throw new ErrorException("Empty response from eSign Doc API.");
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

    if ($apiStatusId == 1) {
        $lead_remarks = "Digilocker REQUEST API CALL(Success) | Aadhaar : $aadhaar_no_last_4_digit";
        $leadModelObj->updateLeadCustomerTable($lead_id, ['customer_ekyc_request_ip' => $_SERVER['REMOTE_ADDR'], 'customer_ekyc_request_initiated_on' => date("Y-m-d H:i:s")]);
    } else {
        $lead_remarks = "Digilocker REQUEST API CALL (Failed) | Aadhaar : $aadhaar_no_last_4_digit | Error : " . $errorMessage;
    }


    $leadModelObj->insertApplicationLog($lead_id, $lead_status_id, $lead_remarks);

    $insertApiLog = array();
    $insertApiLog["ekyc_provider"] = 1;
    $insertApiLog["ekyc_method_id"] = $method_id;
    $insertApiLog["ekyc_lead_id"] = !empty($lead_id) ? $lead_id : NULL;
    $insertApiLog["ekyc_api_status_id"] = $apiStatusId;
    $insertApiLog["ekyc_request"] = addslashes($apiRequestJson);
    $insertApiLog["ekyc_response"] = addslashes($apiResponseJson);
    $insertApiLog["ekyc_aadhaar_no"] = $aadhaar_no_last_4_digit;
    $insertApiLog["ekyc_errors"] = ($apiStatusId == 3) ? addslashes($curlError) : addslashes($errorMessage);
    $insertApiLog["ekyc_request_datetime"] = $apiRequestDateTime;
    $insertApiLog["ekyc_response_datetime"] = !empty($apiResponseDateTime) ? $apiResponseDateTime : date("Y-m-d H:i:s");
    $insertApiLog["ekyc_user_id"] = $user_id;
    $insertApiLog["ekyc_return_url"] = $digilocker_url;
    $insertApiLog["ekyc_return_request_id"] = $digilocker_request_id;

    $leadModelObj->insertTable("api_ekyc_logs", $insertApiLog);

    //Preparing response array
    $response_array['status'] = $apiStatusId;
    $response_array['data'] = $apiResponseData;
    $response_array['digilocker_url'] = $digilocker_url;
    $response_array['digilocker_request_id'] = $digilocker_request_id;
    $response_array['errors'] = !empty($errorMessage) ? "Digilocker Request Error : " . $errorMessage : "";
    $response_array['request_json'] = $apiRequestJson;
    $response_array['response_json'] = $apiResponseJson;

    return $response_array;
}

function digilocker_get_details_api_call($method_id, $lead_id = 0, $request_array = array())
{

    common_log_writer(5, "digilocker_get_details_api_call started | $lead_id");

    require_once(COMP_PATH . '/includes/integration/integration_config.php');

    $response_array = array("status" => 0, "errors" => "");

    $envSet = COMP_ENVIRONMENT;
    $apiStatusId = 0;
    $apiRequestJson = "";
    $apiResponseJson = "";
    $apiRequestDateTime = date("Y-m-d H:i:s");
    $apiResponseDateTime = "";
    $apiResponseData = "";
    $errorMessage = "";
    $curlError = "";

    $type = "SIGNZY_API";
    $sub_type = "DIGILOCKER";

    $hardcode_response = false;

    $debug = !empty($_REQUEST['lwtest']) ? 1 : 0;

    $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : 0;

    $leadModelObj = new LeadModel();

    $lead_status_id = 0;

    $first_name = "";
    $middle_name = "";
    $sur_name = "";
    $customer_full_name = "";
    $token_string = "";

    $aadhaar_no_last_4_digit = "";
    $eaadhaar_available_flag = "N";

    try {

        $apiConfig = integration_config($type, $sub_type);

        if ($debug == 1) {
            echo "<pre>";
            print_r($apiConfig);
        }

        if ($apiConfig['Status'] != 1) {
            throw new Exception($apiConfig['ErrorInfo']);
        }


        if (empty($lead_id)) {
            throw new Exception("Missing lead id.");
        }

        $LeadDetails = $leadModelObj->getLeadFullDetails($lead_id);

        if ($LeadDetails['status'] != 1) {
            throw new Exception("Application details not found");
        }



        $app_data = !empty($LeadDetails['app_data']) ? $LeadDetails['app_data'] : "";
        $lead_status_id = !empty($app_data['lead_status_id']) ? $app_data['lead_status_id'] : "";
        $customer_dob = !empty($app_data['dob']) ? date("d/m/Y", strtotime($app_data['dob'])) : "";

        $aadhaar_no = !empty($app_data['aadhar_no']) ? trim($app_data['aadhar_no']) : "";

        if (!empty($app_data['customer_digital_ekyc_flag']) && $app_data['customer_digital_ekyc_flag'] == 1) {
            throw new Exception("E-KYC already done.");
        }


        // $token_return_array = signzy_token_api_call(1, $lead_id, $request_array);

        // if ($token_return_array['status'] == 1) {
        //     $token_string = $token_return_array['token'];
        //     $token_return_user_id = $token_return_array['token_user_id'];
        // } else {
        //     throw new Exception($token_return_array['errors']);
        // }

        // $apiUrl = $apiConfig["ApiUrl"] = str_replace('customerid', $token_return_user_id, $apiConfig["ApiUrl"]);

        $digilockerDetails = $leadModelObj->getDigilockerApiLog($lead_id, 1);

        if ($digilockerDetails['status'] != 1) {
            throw new Exception("Digilocker Request details not found");
        }

        $digilocker_log_data = !empty($digilockerDetails['digilocker_log_data']) ? $digilockerDetails['digilocker_log_data'] : "";

        $digilocker_request_id = $digilocker_log_data['ekyc_return_request_id'];

        $aadhaar_no_last_4_digit = $digilocker_log_data['ekyc_aadhaar_no'];

        if ($aadhaar_no_last_4_digit != $aadhaar_no) {
            throw new Exception("Aadhaar number does not mateched for initiated request.");
        }


        // $apiRequestJson = '{
        //                         "task" : "getDetails",
        //                         "essentials" : {
        //                             "requestId" : "' . $digilocker_request_id . '"
        //                         }
        //                   }';

        // $apiRequestJson = preg_replace("!\s+!", " ", $apiRequestJson);

        // if ($debug) {
        //     echo "<br/><br/>=======Request JSON=========<br/><br/>";
        //     echo $apiRequestJson;
        // }


        // $apiHeaders = array(
        //     "content-type: application/json",
        //     "accept-language: en-US,en;q=0.8",
        //     "accept: */*",
        //     "Authorization: $token_string"
        // );

        if ($debug) {
            echo "<br/><br/>=======Request Header=========<br/><br/>";
            echo json_encode($apiHeaders);
        }

        $apiRequestDateTime = date("Y-m-d H:i:s");

        // $curl = curl_init($apiUrl);
        // curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $apiHeaders);
        // curl_setopt($curl, CURLOPT_POST, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $apiRequestJson);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        // curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.signzy.app/api/v3/digilocker/getDetails',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "requestId": "' . $digilocker_request_id . '"
        }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: ScTTTviEmhU1EPT79VM6QV9NUHImPkBm',
                'Content-Type: application/json'
            ),
        ));



        $apiResponseJson = curl_exec($curl);

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

            $apiResponseData = json_decode($apiResponseJson, true);

            if (!empty($apiResponseData)) {

                $apiResponseData = common_trim_data_array($apiResponseData);

                if (!empty($apiResponseData)) {

                    if (isset($apiResponseData['result']) && !empty($apiResponseData['result'])) {

                        $apiResponseData = $apiResponseData['result'];
                        $apiUserDetailsData = $apiResponseData['userDetails'];

                        if (!empty($apiUserDetailsData['digilockerid'])) {

                            $apiStatusId = 1;

                            if (!empty($apiUserDetailsData['eaadhaar']) && $apiUserDetailsData['eaadhaar'] == "Y") {
                                $eaadhaar_available_flag = "Y";
                            }
                        } else {
                            throw new ErrorException("Aadhaar details does not received from API.");
                        }
                    } else if (isset($apiResponseData['error']['message']) && !empty($apiResponseData['error']['message'])) {
                        throw new ErrorException($apiResponseData['error']['message']);
                    } else {
                        $tmp_error_msg = "Some error occurred. Please try again.";
                        throw new ErrorException($tmp_error_msg);
                    }
                } else {
                    throw new ErrorException("Please check raw response for error details");
                }
            } else {
                throw new ErrorException("Empty response from eSign Request API");
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

    if ($apiStatusId == 1) {
        $lead_remarks = "Digilocker GetDetails API CALL(Success) | Aadhaar : $aadhaar_no_last_4_digit";
        if ($eaadhaar_available_flag == "Y") {
            $lead_remarks .= "<br>E-Aadhaar available : YES";
        } else {
            $lead_remarks .= "<br>E-Aadhaar available : NO";
        }
    } else {
        $lead_remarks = "Digilocker GetDetails API CALL(Failed) | Aadhaar : $aadhaar_no_last_4_digit | Error : " . $errorMessage;
    }



    $leadModelObj->insertApplicationLog($lead_id, $lead_status_id, $lead_remarks);

    $insertApiLog = array();
    $insertApiLog["ekyc_provider"] = 1;
    $insertApiLog["ekyc_method_id"] = $method_id;
    $insertApiLog["ekyc_lead_id"] = !empty($lead_id) ? $lead_id : NULL;
    $insertApiLog["ekyc_api_status_id"] = $apiStatusId;
    $insertApiLog["ekyc_request"] = addslashes($apiRequestJson);
    $insertApiLog["ekyc_response"] = addslashes($apiResponseJson);
    $insertApiLog["ekyc_aadhaar_no"] = $aadhaar_no_last_4_digit;
    $insertApiLog["ekyc_errors"] = ($apiStatusId == 3) ? addslashes($curlError) : addslashes($errorMessage);
    $insertApiLog["ekyc_request_datetime"] = $apiRequestDateTime;
    $insertApiLog["ekyc_response_datetime"] = !empty($apiResponseDateTime) ? $apiResponseDateTime : date("Y-m-d H:i:s");
    $insertApiLog["ekyc_user_id"] = $user_id;
    $insertApiLog["ekyc_eaadhaar_available_flag"] = $eaadhaar_available_flag;
    $insertApiLog["ekyc_return_request_id"] = $digilocker_request_id;

    $leadModelObj->insertTable("api_ekyc_logs", $insertApiLog);

    //Preparing response array
    $response_array['status'] = $apiStatusId;
    //    $response_array['nsdl_url'] = $aadhaar_nsdl_url;
    $response_array['data'] = $apiResponseData;
    $response_array['errors'] = !empty($errorMessage) ? "Digilocker GetDetails Error : " . $errorMessage : "";
    $response_array['request_json'] = $apiRequestJson;
    $response_array['response_json'] = $apiResponseJson;

    if ($apiStatusId == 1 && $eaadhaar_available_flag == "Y") {
        $temp_eadhaar_return = digilocker_get_eaadhaar_api_call(4, $lead_id, $request_array);

        if ($temp_eadhaar_return['status'] == 5) {
            $response_array['aadhaar_photo'] = $temp_eadhaar_return['aadhaar_photo'];

            $temp_aadhaar_files_return = digilocker_get_file_api_call(3, $lead_id, $request_array);
            if ($temp_aadhaar_files_return['status'] == 1) {
                $response_array['digilocker_files'] = $temp_aadhaar_files_return['digilocker_files'];
                $response_array['fetch_document_array'] = $temp_aadhaar_files_return['fetch_document_array'];
            }
        }
    }


    return $response_array;
}

function digilocker_get_eaadhaar_api_call($method_id, $lead_id = 0, $request_array = array())
{

    common_log_writer(5, "digilocker_get_eaadhaar_api_call started | $lead_id");

    require_once(COMP_PATH . '/includes/integration/integration_config.php');

    $response_array = array("status" => 0, "errors" => "");

    $envSet = COMP_ENVIRONMENT;
    $apiStatusId = 0;
    $apiRequestJson = "";
    $apiResponseJson = "";
    $apiRequestDateTime = date("Y-m-d H:i:s");
    $apiResponseDateTime = "";
    $apiResponseData = "";
    $errorMessage = "";
    $curlError = "";

    $type = "SIGNZY_API";
    $sub_type = "DIGILOCKER";

    $hardcode_response = false;

    $debug = !empty($_REQUEST['lwtest']) ? 1 : 0;

    $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : 0;

    $leadModelObj = new LeadModel();

    $lead_status_id = 0;

    $first_name = "";
    $middle_name = "";
    $sur_name = "";
    $customer_full_name = "";
    $token_string = "";

    $aadhaar_no_last_4_digit = "";
    $aadhaar_no = "";
    $customer_dob = "";

    try {

        $apiConfig = integration_config($type, $sub_type);

        if ($debug == 1) {
            echo "<pre>";
            print_r($apiConfig);
        }

        if ($apiConfig['Status'] != 1) {
            throw new Exception($apiConfig['ErrorInfo']);
        }


        if (empty($lead_id)) {
            throw new Exception("Missing lead id.");
        }

        $LeadDetails = $leadModelObj->getLeadFullDetails($lead_id);

        if ($LeadDetails['status'] != 1) {
            throw new Exception("Application details not found");
        }

        $app_data = !empty($LeadDetails['app_data']) ? $LeadDetails['app_data'] : "";
        $lead_status_id = !empty($app_data['lead_status_id']) ? $app_data['lead_status_id'] : "";
        $customer_dob = !empty($app_data['dob']) ? date("d/m/Y", strtotime($app_data['dob'])) : "";

        $aadhaar_no = !empty($app_data['aadhar_no']) ? trim($app_data['aadhar_no']) : "";
        $customer_id = !empty($app_data['customer_id']) ? trim($app_data['customer_id']) : "";
        $pancard = !empty($app_data['pancard']) ? trim($app_data['pancard']) : "";
        $mobile = !empty($app_data['mobile']) ? trim($app_data['mobile']) : "";

        $customer_full_name = $first_name;
        $customer_full_name .= !empty($middle_name) ? " " . $middle_name : "";
        $customer_full_name .= !empty($sur_name) ? " " . $sur_name : "";

        $digilockerDetails = $leadModelObj->getDigilockerApiLog($lead_id, 2);

        if ($digilockerDetails['status'] != 1) {
            throw new Exception("Digilocker Request details not found");
        }

        $digilocker_log_data = !empty($digilockerDetails['digilocker_log_data']) ? $digilockerDetails['digilocker_log_data'] : "";

        $digilocker_request_id = $digilocker_log_data['ekyc_return_request_id'];

        $eaadhaar_available_flag = $digilocker_log_data['ekyc_eaadhaar_available_flag'];

        $aadhaar_no_last_4_digit = $digilocker_log_data['ekyc_aadhaar_no'];

        if ($aadhaar_no_last_4_digit != $aadhaar_no) {
            throw new Exception("Aadhaar number does not mateched for initiated request.");
        }

        $apiRequestDateTime = date("Y-m-d H:i:s");


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.signzy.app/api/v3/digilocker/geteaadhaar",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "requestId": "' . $digilocker_request_id . '",
            "extraDigitalCertificateParams" : true
        }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: ScTTTviEmhU1EPT79VM6QV9NUHImPkBm',
                'Content-Type: application/json'
            ),
        ));

        $apiResponseJson = curl_exec($curl);


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

            $apiResponseData = json_decode($apiResponseJson, true);

            if (!empty($apiResponseData)) {

                $apiResponseData = common_trim_data_array($apiResponseData);

                if (!empty($apiResponseData)) {

                    if (isset($apiResponseData['result']) && !empty($apiResponseData['result'])) {

                        $apiResponseData = $apiResponseData['result'];

                        if (!empty($apiResponseData['uid']) && !empty($apiResponseData['dob'])) {
                            $apiStatusId = 1;
                        } else {
                            throw new ErrorException("E-Aadhaar data does not received from API.");
                        }
                    } else if (isset($apiResponseData['error']['message']) && !empty($apiResponseData['error']['message'])) {
                        throw new ErrorException($apiResponseData['error']['message']);
                    } else {
                        $tmp_error_msg = "Some error occurred. Please try again.";
                        throw new ErrorException($tmp_error_msg);
                    }
                } else {
                    throw new ErrorException("Please check raw response for error details");
                }
            } else {
                throw new ErrorException("Empty response from eSign Request API");
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

    if ($apiStatusId == 1) {
        $lead_remarks = "Digilocker E-Aadhaar API CALL(Success) | Aadhaar : $aadhaar_no_last_4_digit";
        if (substr($apiResponseData['uid'], 8, 4) == $aadhaar_no && $apiResponseData['dob'] == $customer_dob) {
            $apiStatusId = 5;
            $lead_remarks .= "<br/>Result : Aadhaar last four digit and dob match";

            $aadhaar_complete_address = !empty($apiResponseData['address']) ? $apiResponseData['address'] : "";
            $aadhaar_city = !empty($apiResponseData['splitAddress']['city'][0]) ? $apiResponseData['splitAddress']['city'][0] : "";
            $aadhaar_district = !empty($apiResponseData['splitAddress']['district'][0]) ? $apiResponseData['splitAddress']['district'][0] : "";
            $aadhaar_addressLine = !empty($apiResponseData['splitAddress']['addressLine']) ? $apiResponseData['splitAddress']['addressLine'] : "";
            $aadhaar_addressLandmark = !empty($apiResponseData['splitAddress']['landMark']) ? $apiResponseData['splitAddress']['landMark'] : $aadhaar_district;
            $aadhaar_pincode = !empty($apiResponseData['splitAddress']['pincode']) ? $apiResponseData['splitAddress']['pincode'] : "";

            $pincodeDetails = $leadModelObj->getCityStateByPincode($aadhaar_pincode);

            $m_state_id = "";
            $m_state_name = "";
            $m_city_id = "";
            $m_city_name = "";

            if ($pincodeDetails['status'] == 1) {
                $m_state_id = $pincodeDetails["pincode_data"]["m_state_id"];
                $m_state_name = $pincodeDetails["pincode_data"]["m_state_name"];
                $m_city_id = $pincodeDetails["pincode_data"]["m_city_id"];
                $m_city_name = $pincodeDetails["pincode_data"]["m_city_name"];
            }

            $aadhaar_array = [
                'aa_current_house' => $aadhaar_addressLine,
                'aa_current_locality' => $aadhaar_city,
                'aa_current_landmark' => $aadhaar_addressLandmark,
                'aa_current_eaadhaar_address' => $aadhaar_complete_address,
                'aa_current_state' => $m_state_name,
                'aa_current_state_id' => $m_state_id,
                'aa_current_city' => $m_city_name,
                'aa_current_city_id' => $m_city_id,
                'aa_cr_residence_pincode' => $aadhaar_pincode,
                'aa_current_district' => $aadhaar_district,
                'customer_digital_ekyc_flag' => 1,
                'customer_digital_ekyc_done_on' => date("Y-m-d H:i:s")
            ];

            $leadModelObj->updateLeadCustomerTable($lead_id, $aadhaar_array);
        } else if (substr($apiResponseData['uid'], 8, 4) != $aadhaar_no) {
            $apiStatusId = 7;
            $lead_remarks .= "<br/>Result : Aadhaar number does not matched with input aadhaar.[" . $apiResponseData['uid'] . "]";
        } else if ($apiResponseData['dob'] != $customer_dob) {
            $apiStatusId = 6;
            $lead_remarks .= "<br/>Result : Aadhaar dob does not matched with input aadhaar.[" . $apiResponseData['dob'] . "]";
        } else {
            $apiStatusId = 8;
            $lead_remarks .= "<br/>Result : Aadhaar number last four digit and dob not matched. [" . $apiResponseData['uid'] . " - " . $apiResponseData['dob'] . "]";
        }
    } else {
        $lead_remarks = "Digilocker E-Aadhaar API CALL(Failed) | Aadhaar : $aadhaar_no_last_4_digit | Error : " . $errorMessage;
    }



    $leadModelObj->insertApplicationLog($lead_id, $lead_status_id, $lead_remarks);

    $insertApiLog = array();
    $insertApiLog["ekyc_provider"] = 1;
    $insertApiLog["ekyc_method_id"] = $method_id;
    $insertApiLog["ekyc_lead_id"] = !empty($lead_id) ? $lead_id : NULL;
    $insertApiLog["ekyc_api_status_id"] = $apiStatusId;
    $insertApiLog["ekyc_request"] = addslashes($apiRequestJson);
    $insertApiLog["ekyc_response"] = addslashes($apiResponseJson);
    $insertApiLog["ekyc_aadhaar_no"] = $aadhaar_no_last_4_digit;
    $insertApiLog["ekyc_errors"] = ($apiStatusId == 3) ? addslashes($curlError) : addslashes($errorMessage);
    $insertApiLog["ekyc_request_datetime"] = $apiRequestDateTime;
    $insertApiLog["ekyc_response_datetime"] = !empty($apiResponseDateTime) ? $apiResponseDateTime : date("Y-m-d H:i:s");
    $insertApiLog["ekyc_user_id"] = $user_id;
    $insertApiLog["ekyc_return_request_id"] = $digilocker_request_id;

    $leadModelObj->insertTable("api_ekyc_logs", $insertApiLog);

    //Preparing response array
    $response_array['status'] = $apiStatusId;
    $response_array['data'] = $apiResponseData;
    $response_array['errors'] = !empty($errorMessage) ? "Digilocker E-Aadhaar Error : " . $errorMessage : "";
    $response_array['request_json'] = $apiRequestJson;
    $response_array['response_json'] = $apiResponseJson;
    $response_array['aadhaar_photo'] = $apiResponseData['photo'];
    return $response_array;
}

function digilocker_get_file_api_call($method_id, $lead_id = 0, $request_array = array())
{

    common_log_writer(5, "digilocker_get_file_api_call started | $lead_id");

    require_once(COMP_PATH . '/includes/integration/integration_config.php');

    $response_array = array("status" => 0, "errors" => "");

    $envSet = COMP_ENVIRONMENT;
    $apiStatusId = 0;
    $apiRequestJson = "";
    $apiResponseJson = "";
    $apiRequestDateTime = date("Y-m-d H:i:s");
    $apiResponseDateTime = "";
    $apiResponseData = "";
    $errorMessage = "";
    $curlError = "";

    $type = "SIGNZY_API";
    $sub_type = "DIGILOCKER";

    $hardcode_response = false;

    $debug = !empty($_REQUEST['lwtest']) ? 1 : 0;

    $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : 0;

    $leadModelObj = new LeadModel();

    $lead_status_id = 0;

    $first_name = "";
    $middle_name = "";
    $sur_name = "";
    $customer_full_name = "";
    $token_string = "";

    $aadhaar_no_last_4_digit = "";
    $digilocker_request_id = "";
    $api_return_files = array();
    $fetch_document_array = array();

    try {

        $apiConfig = integration_config($type, $sub_type);

        if ($debug == 1) {
            echo "<pre>";
            print_r($apiConfig);
        }

        if ($apiConfig['Status'] != 1) {
            throw new Exception($apiConfig['ErrorInfo']);
        }


        if (empty($lead_id)) {
            throw new Exception("Missing lead id.");
        }

        $LeadDetails = $leadModelObj->getLeadFullDetails($lead_id);

        if ($LeadDetails['status'] != 1) {
            throw new Exception("Application details not found");
        }

        $app_data = !empty($LeadDetails['app_data']) ? $LeadDetails['app_data'] : "";

        $lead_status_id = !empty($app_data['lead_status_id']) ? $app_data['lead_status_id'] : "";

        $aadhaar_no = !empty($app_data['aadhar_no']) ? trim($app_data['aadhar_no']) : "";
        $customer_id = !empty($app_data['customer_id']) ? trim($app_data['customer_id']) : "";
        $pancard = !empty($app_data['pancard']) ? trim($app_data['pancard']) : "";
        $mobile = !empty($app_data['mobile']) ? trim($app_data['mobile']) : "";

        // $aadhaar_no = substr($aadhaar_no, 8, 4);

        // $token_return_array = signzy_token_api_call(1, $lead_id, $request_array);

        // if ($token_return_array['status'] == 1) {
        //     $token_string = $token_return_array['token'];
        //     $token_return_user_id = $token_return_array['token_user_id'];
        // } else {
        //     throw new Exception($token_return_array['errors']);
        // }

        // $apiUrl = $apiConfig["ApiUrl"] = str_replace('customerid', $token_return_user_id, $apiConfig["ApiUrl"]);

        $digilockerDetails = $leadModelObj->getDigilockerApiLog($lead_id, 2);
        if ($digilockerDetails['status'] != 1) {
            throw new Exception("Digilocker Request details not found");
        }

        $digilocker_log_data = !empty($digilockerDetails['digilocker_log_data']) ? $digilockerDetails['digilocker_log_data'] : "";


        $digilocker_request_id = $digilocker_log_data['ekyc_return_request_id'];

        $aadhaar_no_last_4_digit = $digilocker_log_data['ekyc_aadhaar_no'];

        if ($aadhaar_no_last_4_digit != $aadhaar_no) {
            throw new Exception("Aadhaar number does not mateched for initiated request.");
        }

        $digilocker_response = $digilocker_log_data["ekyc_response"];

        if (!empty($digilocker_response)) {

            $digilocker_response = json_decode($digilocker_response, true);

            $temp_array = $digilocker_response['result']['files'];

            $digilocker_document_ids = array();

            foreach ($temp_array as $doc_array) {

                if (in_array($doc_array['doctype'], array('ADHAR', 'DRVLC', 'PANCR'))) { //,'UNCRD'
                    if (empty($fetch_document_array[$doc_array['doctype']])) {
                        $fetch_document_array[$doc_array['doctype']] = $doc_array['id'];
                        $digilocker_document_ids[] =  $doc_array['id'];
                    }
                }
            }
        }

        // $digilocker_document_ids = rtrim($digilocker_document_ids, ",");

        if (empty($digilocker_document_ids)) {
            throw new Exception("No document received to fetch digilocker docs.");
        }

        $apiRequestData = array(
            "requestId" =>  $digilocker_request_id ,
            "fileIds" => $digilocker_document_ids
            );

        $apiRequestJson = preg_replace("!\s+!", " ", json_encode($apiRequestData));

        if ($debug) {
            echo "<br/><br/>=======Request JSON=========<br/><br/>";
            echo $apiRequestJson;
        }


        // $apiHeaders = array(
        //     "content-type: application/json",
        //     "accept-language: en-US,en;q=0.8",
        //     "accept: */*",
        //     "Authorization: $token_string"
        // );

        if ($debug) {
            echo "<br/><br/>=======Request Header=========<br/><br/>";
            echo json_encode($apiHeaders);
        }

        $apiRequestDateTime = date("Y-m-d H:i:s");

        // $curl = curl_init($apiUrl);
        // curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $apiHeaders);
        // curl_setopt($curl, CURLOPT_POST, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $apiRequestJson);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        // curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.signzy.app/api/v3/digilocker/getFiles',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $apiRequestJson,
            CURLOPT_HTTPHEADER => array(
                'Authorization: ScTTTviEmhU1EPT79VM6QV9NUHImPkBm',
                'Content-Type: application/json'
            ),
        ));

        $apiResponseJson = curl_exec($curl);


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

            $apiResponseData = json_decode($apiResponseJson, true);

            if (!empty($apiResponseData)) {

                $apiResponseData = common_trim_data_array($apiResponseData);

                if (!empty($apiResponseData)) {

                    if (isset($apiResponseData['result']) && !empty($apiResponseData['result'])) {

                        $api_return_files = $apiResponseData['result']['files'];

                        if (!empty($api_return_files)) {
                            $apiStatusId = 1;
                        } else {
                            throw new ErrorException("Document does not received from API.");
                        }
                    } else if (isset($apiResponseData['error']['message']) && !empty($apiResponseData['error']['message'])) {
                        throw new ErrorException($apiResponseData['error']['message']);
                    } else {
                        $tmp_error_msg = "Some error occurred. Please try again.";
                        throw new ErrorException($tmp_error_msg);
                    }
                } else {
                    throw new ErrorException("Please check raw response for error details");
                }
            } else {
                throw new ErrorException("Empty response from eSign Request API");
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
    $insertApiLog["ekyc_provider"] = 1;
    $insertApiLog["ekyc_method_id"] = $method_id;
    $insertApiLog["ekyc_lead_id"] = !empty($lead_id) ? $lead_id : NULL;
    $insertApiLog["ekyc_api_status_id"] = $apiStatusId;
    $insertApiLog["ekyc_request"] = addslashes($apiRequestJson);
    $insertApiLog["ekyc_response"] = addslashes($apiResponseJson);
    $insertApiLog["ekyc_aadhaar_no"] = $aadhaar_no_last_4_digit;
    $insertApiLog["ekyc_errors"] = ($apiStatusId == 3) ? addslashes($curlError) : addslashes($errorMessage);
    $insertApiLog["ekyc_request_datetime"] = $apiRequestDateTime;
    $insertApiLog["ekyc_response_datetime"] = !empty($apiResponseDateTime) ? $apiResponseDateTime : date("Y-m-d H:i:s");
    $insertApiLog["ekyc_user_id"] = $user_id;
    $insertApiLog["ekyc_return_request_id"] = $digilocker_request_id;

    $leadModelObj->insertTable("api_ekyc_logs", $insertApiLog);

    if ($apiStatusId == 1) {
        $lead_remarks = "Digilocker GetFiles API CALL(Success) | Aadhaar : $aadhaar_no_last_4_digit";
    } else {
        $lead_remarks = "Digilocker GetFiles API CALL(Failed) | Aadhaar : $aadhaar_no_last_4_digit | Error : " . $errorMessage;
    }

    $leadModelObj->insertApplicationLog($lead_id, $lead_status_id, $lead_remarks);

    if ($apiStatusId == 1) {
        $lead_remarks = "Digilocker GetFiles API CALL(Success) | Aadhaar : $aadhaar_no_last_4_digit";

        foreach ($api_return_files as $document_array) {

            if (!empty($fetch_document_array[$document_array['doctype']])) {

                if (!empty($document_array["id"]) && $document_array["id"] == $fetch_document_array[$document_array['doctype']] && !empty($document_array["file"]['pdf'])) {

                    $generated_file_name = "DIGILOCKER_" . $document_array['doctype'] . "_" . $lead_id . "_" . date("YmdHis") . "_" . rand(1000, 9999) . ".pdf";

                    $file_write_flag = file_put_contents(COMP_DOC_PATH . $generated_file_name, file_get_contents($document_array["file"]['pdf']));

                    if ($file_write_flag) {

                        $document_insert_array = array();
                        $document_insert_array["lead_id"] = $lead_id;
                        $document_insert_array["customer_id"] = $customer_id;
                        $document_insert_array["pancard"] = $pancard;
                        $document_insert_array["mobile"] = $mobile;
                        $document_insert_array["file"] = $generated_file_name;
                        $document_insert_array["ip"] = $_SERVER["REMOTE_ADDR"];
                        $document_insert_array["created_on"] = date("Y-m-d H:i:s");
                        $document_insert_array["upload_by"] = $user_id;

                        if ($document_array['doctype'] == "ADHAR") {
                            $document_insert_array["docs_master_id"] = 20;
                            $document_insert_array["docs_type"] = "DIGILOCKER";
                            $document_insert_array["sub_docs_type"] = "AADHAAR CARD";
                        } else if ($document_array['doctype'] == "PANCR") {
                            $document_insert_array["docs_master_id"] = 21;
                            $document_insert_array["docs_type"] = "DIGILOCKER";
                            $document_insert_array["sub_docs_type"] = "PAN CARD";
                        } else if ($document_array['doctype'] == "DRVLC") {
                            $document_insert_array["docs_master_id"] = 23;
                            $document_insert_array["docs_type"] = "DIGILOCKER";
                            $document_insert_array["sub_docs_type"] = "DRIVING LICENCE";
                        }

                        $leadModelObj->insertTable("docs", $document_insert_array);
                    }
                }
            }
        }
    } else {
        $lead_remarks = "Digilocker GetFiles API CALL(Failed) | Aadhaar : $aadhaar_no_last_4_digit | Error : " . $errorMessage;
    }

    //Preparing response array
    $response_array['status'] = $apiStatusId;
    $response_array['data'] = $apiResponseData;
    $response_array['errors'] = !empty($errorMessage) ? "Digilocker GetFiles Error : " . $errorMessage : "";
    $response_array['request_json'] = $apiRequestJson;
    $response_array['response_json'] = $apiResponseJson;
    $response_array['digilocker_files'] = $api_return_files;
    $response_array['fetch_document_array'] = $fetch_document_array;
    return $response_array;
}

?>
