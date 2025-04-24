
<?php

function poi_ocr_api_call($method_name = "", $lead_id = 0, $request_array = array()) {
    common_log_writer(3, "POI OCR API started | $lead_id | $method_name");

    $responseArray = array("status" => 0, "errors" => "");

    $opertion_array = array(
        "GET_PAN_OCR" => 1,
        "GET_AADHAAR_OCR" => 2,
        "GET_MASKED_AADHAAR" => 3,
    );

    $method_id = $opertion_array[$method_name];

    if ($method_id == 1) {
        $responseArray = pan_ocr_api_call($method_id, $lead_id, $request_array);
    } else if ($method_id == 2) {
        $responseArray = aadhaar_ocr_api_call($method_id, $lead_id, $request_array);
    } else if ($method_id == 3) {
        $responseArray = aadhaar_mask_api_call($method_id, $lead_id, $request_array);
    } else {
        $responseArray["errors"] = "invalid opertation called";
    }

    common_log_writer(3, "POI OCR API end | $lead_id | $method_name | " . json_encode($responseArray));

    return $responseArray;
}

function pan_ocr_api_call($method_id, $lead_id = 0, $request_array = array()) {

    common_log_writer(3, "pan_ocr_api_call started | $lead_id");

    require_once (COMP_PATH . '/includes/integration/integration_config.php');

    $response_array = array("status" => 0, "errors" => "");

    $envSet = COMP_ENVIRONMENT;
    $api_call_flag = true;
    $apiStatusId = 0;
    $pan_valid_status = 0;
    $apiRequestJson = "";
    $apiResponseJson = "";
    $apiRequestDateTime = date("Y-m-d H:i:s");
    $apiResponseDateTime = "";
    $apiResponseData = "";
    $errorMessage = "";
    $curlError = "";

    $type = "SIGNZY_API";
    $sub_type = "PAN_OCR";

    $hardcode_response = false;

//    if ($envSet == 'development') {
//        $hardcode_response = true;
//    }

    $debug = !empty($_REQUEST['lwtest']) ? 1 : 0;

    $applicationDetails = array();

    $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : 0;

    $leadModelObj = new LeadModel();

    $pan_no = "";
    $lead_status_id = 0;
    $pan_document_id = 0;
    $first_name = "";
    $middle_name = "";
    $sur_name = "";
    $customer_full_name = "";
    $token_string = "";
    $item_id_string = "";
    $access_token_string = "";

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

        $LeadDetails = $leadModelObj->getLeadFullDetails($lead_id);
        // print_r($LeadDetails);

        if ($LeadDetails['status'] != 1) {
            throw new Exception("Application details not found");
        }

        $app_data = !empty($LeadDetails['app_data']) ? $LeadDetails['app_data'] : "";
        $lead_status_id = !empty($app_data['lead_status_id']) ? $app_data['lead_status_id'] : "";
        $pan_no = !empty($app_data['pancard']) ? trim(strtoupper($app_data['pancard'])) : "";

        $first_name = !empty($app_data['first_name']) ? trim(strtoupper($app_data['first_name'])) : "";
        $middle_name = !empty($app_data['middle_name']) ? trim(strtoupper($app_data['middle_name'])) : "";
        $sur_name = !empty($app_data['sur_name']) ? trim(strtoupper($app_data['sur_name'])) : "";

        $customer_full_name = $first_name;
        $customer_full_name .= !empty($middle_name) ? " " . $middle_name : "";
        $customer_full_name .= !empty($sur_name) ? " " . $sur_name : "";

        $docsDetails = $leadModelObj->getDocumentDetails($lead_id, 4);

        //print_r($docsDetails);die;

        if ($docsDetails['status'] != 1) {
            throw new Exception("Please upload the PAN Card in documents.");
        }

        $doc_data = !empty($docsDetails['doc_data']) ? $docsDetails['doc_data'] : "";

        $pan_document_id = !empty($doc_data['docs_id']) ? $doc_data['docs_id'] : "";

        if (empty($pan_no)) {
            throw new Exception("Missing pancard number.");
        }

        if (empty($pan_document_id)) {
            throw new Exception("Missing pancard document.");
        }

        $panLogData = $leadModelObj->getPanOCRLastApiLog($lead_id);

        if ($panLogData['status'] == 1) {

            if (!empty($panLogData['pan_log_data'])) {

                if ($panLogData['pan_log_data']['poi_ocr_proof_no'] == $pan_no && $panLogData['pan_log_data']['poi_ocr_doc_id_1'] == $pan_document_id) {
//                    $api_call_flag = false;
                    $apiResponseJson = $panLogData['pan_log_data']['poi_ocr_response'];
                }
            }
        }

        if ($api_call_flag) {


            // $token_return_array = signzy_token_api_call(1, $lead_id, $request_array);

            // if ($token_return_array['status'] == 1) {
            //     $token_string = $token_return_array['token'];
            //     $request_array['token_user_id'] = $token_return_array['token_user_id'];
            //     $request_array['token'] = $token_return_array['token'];
            // } else {
            //     throw new Exception($token_return_array['errors']);
            // }


            $request_array['ocr_file_1'] = $doc_data['file'];
            $request_array['ocr_file_2'] = "";

            $panImage =getenv("WEBSITE_URL")."upload/".$doc_data['file'];

            // print_r("FILE==>$panImage");die;

            // $identity_return_array = signzy_identity_object_api_call('individualPan', $lead_id, $request_array);

            // if ($identity_return_array['status'] == 1) {
            //     $item_id_string = $identity_return_array['item_id_string'];
            //     $access_token_string = $identity_return_array['access_token_string'];
            // } else {
            //     throw new Exception($identity_return_array['errors']);
            // }


            // $apiRequestJson = '{
            //                 "service":"Identity",
            //                 "itemId":"' . $item_id_string . '",
            //                 "task":"autoRecognition",
            //                 "accessToken":"' . $access_token_string . '",
            //                 "essentials":{}
            //             }';

            // $apiRequestJson = preg_replace("!\s+!", " ", $apiRequestJson);

            if ($debug == 1) {
                echo "<br/><br/>=======Request JSON=========<br/><br/>";
                echo $apiRequestJson;
            }
// echo $panImage;

            // $apiHeaders = array(
            //     "content-type: application/json",
            //     "accept-language: en-US,en;q=0.8",
            //     "accept: */*",
            //     "Authorization: $token_string"
            // );

            if ($debug == 1) {
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


        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.signzy.app/api/v3/pan/extractions',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "files": [
            "' . $panImage . '"
            ],
            "type": "individualPan",
            "getRelativeData": true
        }',
          CURLOPT_HTTPHEADER => array(
            'Authorization: ScTTTviEmhU1EPT79VM6QV9NUHImPkBm',
            'Content-Type: application/json'
          ),
        ));

        $apiResponseJson = curl_exec($curl);

        //curl_close($curl);
        //  echo $apiResponseJson;
        // print_r($apiResponseJson);die;

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
                    // print_r($apiResponseData); die;

                    if (!empty($apiResponseData)) {

                        if (isset($apiResponseData['result']) && !empty($apiResponseData['result'])) {

                                //   echo $apiResponseData['result'];

                            $apiResponseData = $apiResponseData['result'];

                            if (!empty($apiResponseData['number']) && !empty($apiResponseData['name'])) {
                                $apiStatusId = 1;
                            } else {
                                throw new ErrorException("PAN details does not received from API.");
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
                    throw new ErrorException("Empty response from PAN OCR API");
                }
            }
        } else {
            $apiStatusId = 1;
            $apiResponseData = json_decode($apiResponseJson, true);
            $apiResponseData = common_trim_data_array($apiResponseData);
            $apiResponseData = $apiResponseData['response']['result'];
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
        $lead_remarks = "PAN OCR API CALL(Success) | PAN NO : $pan_no | Customer Name : " . $customer_full_name;
        $lead_remarks .= "<br>OCR FETCH DETAILS";
        $lead_remarks .= "<br>PAN NO : " . $apiResponseData['number'] . " | Name : " . $apiResponseData['name'] . " | Father Name : " . $apiResponseData['fatherName'] . " | DOB : " . $apiResponseData['dob'];

        $pan_valid_status = 1;

        if ($pan_no != trim(strtoupper($apiResponseData['number']))) {
            $pan_valid_status = 2;
        }


        if ($pan_valid_status == 1) {
            $lead_remarks .= "<br>Result : PAN number matched with PAN OCR Details";
        } else {
            $lead_remarks .= "<br>Result : PAN number does not matched with PAN OCR Details";
        }


        if ($pan_valid_status == 1) {

            $lead_customer_array = array('pancard_ocr_verified_status' => 1, 'pancard_ocr_verified_on' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"));

            if (!empty($apiResponseData['dob'])) {
                $dob_expload = explode("/", $apiResponseData['dob']);
                $pan_dob = $dob_expload[2] . "-" . $dob_expload[1] . "-" . $dob_expload[0];
                $lead_customer_array['dob'] = $pan_dob;
            }

            $leadModelObj->updateLeadCustomerTable($lead_id, $lead_customer_array);
        }
    } else {
        $lead_remarks = "PAN OCR API CALL(Failed) | PAN NO : $pan_no | Error : " . $errorMessage;
    }

    $leadModelObj->insertApplicationLog($lead_id, $lead_status_id, $lead_remarks);

    if ($api_call_flag) {

        $insertApiLog = array();
        $insertApiLog["poi_ocr_provider"] = 1;
        $insertApiLog["poi_ocr_method_id"] = $method_id;
        $insertApiLog["poi_ocr_lead_id"] = !empty($lead_id) ? $lead_id : NULL;
        $insertApiLog["poi_ocr_api_status_id"] = $apiStatusId;
        $insertApiLog["poi_ocr_request"] = addslashes($apiRequestJson);
        $insertApiLog["poi_ocr_response"] = addslashes($apiResponseJson);
        $insertApiLog["poi_ocr_proof_no"] = $pan_no;
        $insertApiLog["poi_ocr_doc_id_1"] = $pan_document_id;
        $insertApiLog["poi_ocr_errors"] = ($apiStatusId == 3) ? addslashes($curlError) : addslashes($errorMessage);
        $insertApiLog["poi_ocr_request_datetime"] = $apiRequestDateTime;
        $insertApiLog["poi_ocr_response_datetime"] = !empty($apiResponseDateTime) ? $apiResponseDateTime : date("Y-m-d H:i:s");
        $insertApiLog["poi_ocr_user_id"] = $user_id;

        $leadModelObj->insertTable("api_poi_ocr_logs", $insertApiLog);
    }
    //Preparing response array
    $response_array['status'] = $apiStatusId;
    $response_array['pan_valid_status'] = $pan_valid_status;
    $response_array['data'] = $apiResponseData;
    $response_array['errors'] = !empty($errorMessage) ? "PAN OCR Error : " . $errorMessage : "";
    $response_array['request_json'] = $apiRequestJson;
    $response_array['response_json'] = $apiResponseJson;

    return $response_array;
}

function aadhaar_ocr_api_call($method_id, $lead_id = 0, $request_array = array()) {

    common_log_writer(3, "aadhaar_ocr_api_call started | $lead_id");

    require_once (COMP_PATH . '/includes/integration/integration_config.php');

    $response_array = array("status" => 0, "errors" => "");

    $envSet = COMP_ENVIRONMENT;

    $api_call_flag = true;
    $apiStatusId = 0;
    $aadhaar_valid_status = 0;
    $apiRequestJson = "";
    $apiResponseJson = "";
    $apiRequestDateTime = date("Y-m-d H:i:s");
    $apiResponseDateTime = "";
    $apiResponseData = "";
    $errorMessage = "";
    $curlError = "";

    $type = "SIGNZY_API";
    $sub_type = "AADHAAR_OCR";

    $hardcode_response = false;

//    if ($envSet == 'development') {
//        $hardcode_response = true;
//    }

    $debug = !empty($_REQUEST['lwtest']) ? 1 : 0;

    $applicationDetails = array();

    $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : 0;

    $leadModelObj = new LeadModel();

    $pan_no = "";
    $lead_status_id = 0;
    $pan_document_id = 0;
    $first_name = "";
    $middle_name = "";
    $sur_name = "";
    $customer_full_name = "";
    $token_string = "";
    $item_id_string = "";
    $access_token_string = "";
    $aadhar_no = "";
    $aadhaar_front_document_id = "";
    $aadhaar_back_document_id = "";

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

        $LeadDetails = $leadModelObj->getLeadFullDetails($lead_id);
        // print_r($LeadDetails);

        if ($LeadDetails['status'] != 1) {
            throw new Exception("Application details not found");
        }

        $app_data = !empty($LeadDetails['app_data']) ? $LeadDetails['app_data'] : "";
        $lead_status_id = !empty($app_data['lead_status_id']) ? $app_data['lead_status_id'] : "";
        $aadhar_no = !empty($app_data['aadhar_no']) ? trim($app_data['aadhar_no']) : "";

        $first_name = !empty($app_data['first_name']) ? trim(strtoupper($app_data['first_name'])) : "";
        $middle_name = !empty($app_data['middle_name']) ? trim(strtoupper($app_data['middle_name'])) : "";
        $sur_name = !empty($app_data['sur_name']) ? trim(strtoupper($app_data['sur_name'])) : "";

        $customer_full_name = $first_name;
        $customer_full_name .= !empty($middle_name) ? " " . $middle_name : "";
        $customer_full_name .= !empty($sur_name) ? " " . $sur_name : "";

        if (empty($aadhar_no)) {
            throw new Exception("Missing aadhaar number.");
        }

        $aadhaarFrontDocsDetails = $leadModelObj->getDocumentDetails($lead_id, 1);

        if ($aadhaarFrontDocsDetails['status'] != 1) {
            throw new Exception("Please upload the Aadhaar Front document.");
        }

        $aadhaar_front_doc_data = !empty($aadhaarFrontDocsDetails['doc_data']) ? $aadhaarFrontDocsDetails['doc_data'] : "";
        $aadhaar_front_document_id = !empty($aadhaar_front_doc_data['docs_id']) ? $aadhaar_front_doc_data['docs_id'] : "";

        if (empty($aadhaar_front_document_id)) {
            throw new Exception("Please upload the Aadhaar Front document.");
        }

        $aadhaarBackDocsDetails = $leadModelObj->getDocumentDetails($lead_id, 2);

        if ($aadhaarBackDocsDetails['status'] != 1) {
            throw new Exception("Please upload the Aadhaar Back document.");
        }

        $aadhaar_back_doc_data = !empty($aadhaarBackDocsDetails['doc_data']) ? $aadhaarBackDocsDetails['doc_data'] : "";
        $aadhaar_back_document_id = !empty($aadhaar_back_doc_data['docs_id']) ? $aadhaar_back_doc_data['docs_id'] : "";

        if (empty($aadhaar_back_document_id)) {
            throw new Exception("Please upload the Aadhaar Back document..");
        }

        $aadhaarLogData = $leadModelObj->getAadhaarOCRLastApiLog($lead_id);

        if ($aadhaarLogData['status'] == 1) {

            if (!empty($aadhaarLogData['aadhaar_log_data'])) {

                if ($aadhaarLogData['aadhaar_log_data']['poi_ocr_proof_no'] == $aadhar_no && $aadhaarLogData['aadhaar_log_data']['poi_ocr_doc_id_1'] == $aadhaar_front_document_id && $aadhaarLogData['aadhaar_log_data']['poi_ocr_doc_id_2'] == $aadhaar_back_document_id) {
//                    $api_call_flag = false;
                    $apiResponseJson = $aadhaarLogData['aadhaar_log_data']['poi_ocr_response'];
                }
            }
        }

        if ($api_call_flag) {


            // $token_return_array = signzy_token_api_call(1, $lead_id, $request_array);

            // if ($token_return_array['status'] == 1) {
            //     $token_string = $token_return_array['token'];
            //     $request_array['token_user_id'] = $token_return_array['token_user_id'];
            //     $request_array['token'] = $token_return_array['token'];
            // } else {
            //     throw new Exception($token_return_array['errors']);
            // }


            $request_array['ocr_file_1'] = $aadhaar_front_doc_data['file'];
            $request_array['ocr_file_2'] = $aadhaar_back_doc_data['file'];

            $front="https://paisaonsalary.com/upload/".$request_array['ocr_file_1'];
            $back="https://paisaonsalary.com/upload/".$request_array['ocr_file_2'];

            // print_r($request_array);die ;

            // $identity_return_array = signzy_identity_object_api_call('aadhaar', $lead_id, $request_array);

            // if ($identity_return_array['status'] == 1) {
            //     $item_id_string = $identity_return_array['item_id_string'];
            //     $access_token_string = $identity_return_array['access_token_string'];
            // } else {
            //     throw new Exception($identity_return_array['errors']);
            // }


            // $apiRequestJson = '{
            //                 "service":"Identity",
            //                 "itemId":"' . $item_id_string . '",
            //                 "task":"autoRecognition",
            //                 "accessToken":"' . $access_token_string . '",
            //                 "essentials":{}
            //             }';

            // $apiRequestJson = preg_replace("!\s+!", " ", $apiRequestJson);

            // if ($debug == 1) {
            //     echo "<br/><br/>=======Request JSON=========<br/><br/>";
            //     echo $apiRequestJson;
            // }


            // $apiHeaders = array(
            //     "content-type: application/json",
            //     "accept-language: en-US,en;q=0.8",
            //     "accept: */*",
            //     "Authorization: $token_string"
            // );

            if ($debug == 1) {
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


            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.signzy.app/api/v3/aadhaar/extraction',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
                "files": [
                "'.$front.'",
                "'.$back.'"

                ]
            }',
              CURLOPT_HTTPHEADER => array(
                'Authorization: ScTTTviEmhU1EPT79VM6QV9NUHImPkBm',
                'Content-Type: application/json'
              ),
            ));

            $apiResponseJson = curl_exec($curl);

            //curl_close($curl);
            // echo $front;
            // echo $back;

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

                            if (!empty($apiResponseData['uid'])) {
                                $apiStatusId = 1;
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
                    throw new ErrorException("Empty response from Aaddhaar OCR API");
                }
            }
        } else {
            $apiStatusId = 1;
            $apiResponseData = json_decode($apiResponseJson, true);
            $apiResponseData = common_trim_data_array($apiResponseData);
            $apiResponseData = $apiResponseData['response']['result'];
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
        $lead_remarks = "AADHAAR OCR API CALL(Success) | AADHAAR NO : $aadhar_no | Customer Name : " . $customer_full_name;
        $lead_remarks .= "<br>OCR FETCH DETAILS";
        $lead_remarks .= "<br>AADHAAR NO : " . $apiResponseData['uid'] . " | Name : " . $apiResponseData['name'] . " | Guardian Name : " . $apiResponseData['summary']['guardianName'] . " | DOB : " . $apiResponseData['dob'];
        $lead_remarks .= "<br>AADHAAR ADDRESS : " . $apiResponseData['address'];

        $aadhaar_valid_status = 1;

        if ($aadhar_no != substr(trim($apiResponseData['uid']), 8, 4)) {
            $aadhaar_valid_status = 2;
        }


        if ($aadhaar_valid_status == 1) {
            $lead_remarks .= "<br>Result : AADHAAR number matched with AADHAAR OCR Details";
        } else {
            $lead_remarks .= "<br>Result : AADHAAR number does not matched with AADHAAR OCR Details";
        }


        if ($aadhaar_valid_status == 1) {

            $lead_customer_array = array('aadhaar_ocr_verified_status' => 1, 'aadhaar_ocr_verified_on' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s"));

            $leadModelObj->updateLeadCustomerTable($lead_id, $lead_customer_array);
        }
    } else {
        $lead_remarks = "AADHAAR OCR API CALL(Failed) | AADHAAR NO : $aadhar_no | Error : " . $errorMessage;
    }

    $leadModelObj->insertApplicationLog($lead_id, $lead_status_id, $lead_remarks);

    if ($api_call_flag) {

        $insertApiLog = array();
        $insertApiLog["poi_ocr_provider"] = 1;
        $insertApiLog["poi_ocr_method_id"] = $method_id;
        $insertApiLog["poi_ocr_lead_id"] = !empty($lead_id) ? $lead_id : NULL;
        $insertApiLog["poi_ocr_api_status_id"] = $apiStatusId;
        $insertApiLog["poi_ocr_request"] = addslashes($apiRequestJson);
        $insertApiLog["poi_ocr_response"] = addslashes($apiResponseJson);
        $insertApiLog["poi_ocr_proof_no"] = $aadhar_no;
        $insertApiLog["poi_ocr_doc_id_1"] = $aadhaar_front_document_id;
        $insertApiLog["poi_ocr_doc_id_2"] = $aadhaar_back_document_id;
        $insertApiLog["poi_ocr_errors"] = ($apiStatusId == 3) ? addslashes($curlError) : addslashes($errorMessage);
        $insertApiLog["poi_ocr_request_datetime"] = $apiRequestDateTime;
        $insertApiLog["poi_ocr_response_datetime"] = !empty($apiResponseDateTime) ? $apiResponseDateTime : date("Y-m-d H:i:s");
        $insertApiLog["poi_ocr_user_id"] = $user_id;

        $leadModelObj->insertTable("api_poi_ocr_logs", $insertApiLog);
    }
    //Preparing response array
    $response_array['status'] = $apiStatusId;
    $response_array['aadhaar_valid_status'] = $aadhaar_valid_status;
    $response_array['data'] = $apiResponseData;
    $response_array['errors'] = !empty($errorMessage) ? "AADHAAR OCR Error : " . $errorMessage : "";
    $response_array['request_json'] = $apiRequestJson;
    $response_array['response_json'] = $apiResponseJson;

    return $response_array;
}

function aadhaar_mask_api_call($method_id, $lead_id = 0, $request_array = array()) {

    common_log_writer(3, "aadhaar_mask_api_call started | $lead_id");

    require_once (COMP_PATH . '/includes/integration/integration_config.php');

    $response_array = array("status" => 0, "errors" => "");

    $envSet = COMP_ENVIRONMENT;

    $api_call_flag = true;
    $apiStatusId = 0;
    $apiRequestJson = "";
    $apiResponseJson = "";
    $apiRequestDateTime = date("Y-m-d H:i:s");
    $apiResponseDateTime = "";
    $apiResponseData = "";
    $errorMessage = "";
    $curlError = "";

    $type = "SIGNZY_API";
    $sub_type = "AADHAAR_MASK";

    $hardcode_response = false;

//    if ($envSet == 'development') {
//        $hardcode_response = true;
//    }

    $debug = !empty($_REQUEST['lwtest']) ? 1 : 0;

    $applicationDetails = array();

    $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : 0;

    $leadModelObj = new LeadModel();

    $pan_no = "";
    $lead_status_id = 0;
    $pan_document_id = 0;
    $first_name = "";
    $middle_name = "";
    $sur_name = "";
    $customer_full_name = "";
    $token_string = "";
    $item_id_string = "";
    $access_token_string = "";
    $aadhar_no = "";

    $aadhaar_document_id = !empty($request_array['doc_id']) ? $request_array['doc_id'] : "";
    $aadhaar_document_type_id = !empty($request_array['doc_type_id']) ? $request_array['doc_type_id'] : "";

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

        $LeadDetails = $leadModelObj->getLeadFullDetails($lead_id);

        if ($LeadDetails['status'] != 1) {
            throw new Exception("Application details not found");
        }

        $app_data = !empty($LeadDetails['app_data']) ? $LeadDetails['app_data'] : "";
        $lead_status_id = !empty($app_data['lead_status_id']) ? $app_data['lead_status_id'] : "";
        $pan_no = !empty($app_data['pancard']) ? trim(strtoupper($app_data['pancard'])) : "";

        $first_name = !empty($app_data['first_name']) ? trim(strtoupper($app_data['first_name'])) : "";
        $middle_name = !empty($app_data['middle_name']) ? trim(strtoupper($app_data['middle_name'])) : "";
        $sur_name = !empty($app_data['sur_name']) ? trim(strtoupper($app_data['sur_name'])) : "";

        $customer_full_name = $first_name;
        $customer_full_name .= !empty($middle_name) ? " " . $middle_name : "";
        $customer_full_name .= !empty($sur_name) ? " " . $sur_name : "";

        $docsDetails = $leadModelObj->getDocumentDetails($lead_id, 4);

        if ($docsDetails['status'] != 1) {
            throw new Exception("Please upload the PAN Card in documents.");
        }

        $doc_data = !empty($docsDetails['doc_data']) ? $docsDetails['doc_data'] : "";

        $pan_document_id = !empty($doc_data['docs_id']) ? $doc_data['docs_id'] : "";

        if (empty($pan_no)) {
            throw new Exception("Missing pancard number.");
        }

        if (empty($pan_document_id)) {
            throw new Exception("Missing pancard document.");
        }

        $panLogData = $leadModelObj->getPanOCRLastApiLog($lead_id);

        if ($panLogData['status'] == 1) {

            if (!empty($panLogData['pan_log_data'])) {

                if ($panLogData['pan_log_data']['poi_ocr_proof_no'] == $pan_no && $panLogData['pan_log_data']['poi_ocr_doc_id_1'] == $pan_document_id) {
//                    $api_call_flag = false;
                    $apiResponseJson = $panLogData['pan_log_data']['poi_ocr_response'];
                }
            }
        }

        if ($api_call_flag) {


            // $token_return_array = signzy_token_api_call(1, $lead_id, $request_array);

            // if ($token_return_array['status'] == 1) {
            //     $token_string = $token_return_array['token'];
            //     $request_array['token_user_id'] = $token_return_array['token_user_id'];
            //     $request_array['token'] = $token_return_array['token'];
            // } else {
            //     throw new Exception($token_return_array['errors']);
            // }


            $request_array['ocr_file_1'] = $aadhaar_front_doc_data['file'];
            $request_array['ocr_file_2'] = $aadhaar_back_doc_data['file'];

            $front="https://paisaonsalary.com/upload/".$request_array['ocr_file_1'];
            $back="https://paisaonsalary.com/upload/".$request_array['ocr_file_2'];

            $identity_return_array = signzy_identity_object_api_call('individualPan', $lead_id, $request_array);

            // if ($identity_return_array['status'] == 1) {
            //     $item_id_string = $identity_return_array['item_id_string'];
            //     $access_token_string = $identity_return_array['access_token_string'];
            // } else {
            //     throw new Exception($identity_return_array['errors']);
            // }


            // $apiRequestJson = '{
            //                 "service":"Identity",
            //                 "itemId":"' . $item_id_string . '",
            //                 "task":"autoRecognition",
            //                 "accessToken":"' . $access_token_string . '",
            //                 "essentials":{}
            //             }';

            $apiRequestJson = preg_replace("!\s+!", " ", $apiRequestJson);

            if ($debug == 1) {
                echo "<br/><br/>=======Request JSON=========<br/><br/>";
                echo $apiRequestJson;
            }


            // $apiHeaders = array(
            //     "content-type: application/json",
            //     "accept-language: en-US,en;q=0.8",
            //     "accept: */*",
            //     "Authorization: $token_string"
            // );

            if ($debug == 1) {
                echo "<br/><br/>=======Request Header=========<br/><br/>";
                echo json_encode($apiHeaders);
            }

            $apiRequestDateTime = date("Y-m-d H:i:s");

            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.signzy.app/api/v3/aadhaar/extraction',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
                "files": [
                "'.$front.'",
                "'.$back.'"

                ]
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

                //  print_r($apiResponseData); die;

                if (!empty($apiResponseData)) {

                    $apiResponseData = common_trim_data_array($apiResponseData);

                    if (!empty($apiResponseData)) {

                        if (isset($apiResponseData['response']['result']) && !empty($apiResponseData['response']['result'])) {

                            $apiResponseData = $apiResponseData['response']['result'];

                            if (!empty($apiResponseData['maskedImages']) && $apiResponseData['isMasked'] == "yes") {
                                $masked_document_url = $apiResponseData['maskedImages'][0];
                                if (!empty($masked_document_url)) {
                                    $apiStatusId = 1;
                                } else {
                                    throw new ErrorException("Aadhaar masked image not recieved..");
                                }
                            } else {
                                throw new ErrorException("Aadhaar image not masked.");
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
                    throw new ErrorException("Empty response from Aaddhaar Masked API");
                }
            }
        } else {
            $apiStatusId = 1;
            $apiResponseData = json_decode($apiResponseJson, true);
            $apiResponseData = common_trim_data_array($apiResponseData);
            $apiResponseData = $apiResponseData['response']['result'];
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
        $apiStatusId = 5;
    } else {
//        $lead_remarks = "AADHAAR MASKED API CALL(Failed) | AADHAAR NO : $aadhar_no | Error : " . $errorMessage;
    }



    if ($api_call_flag) {

        $insertApiLog = array();
        $insertApiLog["poi_ocr_provider"] = 1;
        $insertApiLog["poi_ocr_method_id"] = $method_id;
        $insertApiLog["poi_ocr_lead_id"] = !empty($lead_id) ? $lead_id : NULL;
        $insertApiLog["poi_ocr_api_status_id"] = $apiStatusId;
        $insertApiLog["poi_ocr_request"] = addslashes($apiRequestJson);
        $insertApiLog["poi_ocr_response"] = addslashes($apiResponseJson);
        $insertApiLog["poi_ocr_proof_no"] = $aadhar_no;
        $insertApiLog["poi_ocr_doc_id_1"] = $aadhaar_document_id;
        $insertApiLog["poi_ocr_errors"] = ($apiStatusId == 3) ? addslashes($curlError) : addslashes($errorMessage);
        $insertApiLog["poi_ocr_request_datetime"] = $apiRequestDateTime;
        $insertApiLog["poi_ocr_response_datetime"] = !empty($apiResponseDateTime) ? $apiResponseDateTime : date("Y-m-d H:i:s");
        $insertApiLog["poi_ocr_user_id"] = $user_id;

        $leadModelObj->insertTable("api_poi_ocr_logs", $insertApiLog);
    }
    //Preparing response array
    $response_array['status'] = $apiStatusId;
    $response_array['data'] = $apiResponseData;
    $response_array['errors'] = !empty($errorMessage) ? "AADHAAR MASKED Error : " . $errorMessage : "";
    $response_array['request_json'] = $apiRequestJson;
    $response_array['response_json'] = $apiResponseJson;
    $response_array['aadhaar_masked_url'] = $masked_document_url;
    $response_array['aadhaar_docs_data'] = $aadhaar_doc_data;
    $response_array['aadhaar_no'] = $aadhar_no;
    $response_array['customer_full_name'] = $customer_full_name;
    return $response_array;
}

?>
