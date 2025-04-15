<?php

function integration_config($api_type = "", $api_sub_type = "") {

    $envSet = COMP_ENVIRONMENT;

    $config_arr = array();

    switch ($api_type) {

        CASE "CRIF_CALL" :
            $config_arr['Status'] = 1;
            $config_arr['Provider'] = "CRIF";
            $config_arr['UserName'] = "";
            $config_arr['UserPassword'] = "";
            $config_arr['RPMiddleWareUrl'] = "";
            $config_arr['ApiUserId'] = "crif1_cpu_uat@namanfinlease.in";
            $config_arr['ApiPassword'] = "BB4268CE44CC47CA9EEF58859BD2D5F90231CCF5";
            $config_arr['ApiMemberId'] = "NBF0003124";
            $config_arr['ApiSubMemberId'] = "NAMAN FINLEASE PRIVATE LIMITED";

            $config_arr['RPMiddleWareUrl'] = ""; //http://loanwallefintech.in:8096/middleware/service/

            if ($envSet == "production") {
                $config_arr['RPMiddleWareUrl'] = ""; //http://localhost:8096/middleware/service/
                $config_arr['ApiUserId'] = "crif1_cpu_prd@namanfinlease.in";
                $config_arr['ApiPassword'] = "58FE8056B04BF0E31D511E469CA823C56DD6C9B5";
                $config_arr['ApiMemberId'] = "NBF0000864";
                $config_arr['ApiSubMemberId'] = "NAMAN FINLEASE PRIVATE LIMITED";
            }

            if ($api_sub_type == "REQUEST_INIT") {
                if ($envSet == "development") {
                    $config_arr['ApiUrl'] = "https://test.crifhighmark.com/Inquiry/doGet.service/requestResponseSync";
                } else if ($envSet == "production") {
                    $config_arr['ApiUrl'] = "https://hub.crifhighmark.com/Inquiry/doGet.service/requestResponseSync";
                }
            }
            break;

        CASE "SIGNZY_API" :
            $config_arr['Status'] = 1;
            $config_arr['Provider'] = "signzy";
            $config_arr['UserName'] = "";
            $config_arr['UserPassword'] = "";
            $config_arr['RPMiddleWareUrl'] = "";
            $config_arr['ApiUserId'] = "surya_prod";
            $config_arr['ApiPassword'] = "jZwLe6T218s4XM1NUpwg";
            $config_arr['RPMiddleWareUrl'] = "";

            if ($envSet == "production") {
                $config_arr['RPMiddleWareUrl'] = "";
                $config_arr['ApiUserId'] = "surya_prod";
                $config_arr['ApiPassword'] = "jZwLe6T218s4XM1NUpwg";
            }

            if ($api_sub_type == "GET_TOKEN") {
                if ($envSet == "development") {
                    $config_arr['ApiUrl'] = "https://preproduction.signzy.tech/api/v2/patrons/login";
                } else if ($envSet == "production") {
                    $config_arr['ApiUrl'] = "https://signzy.tech/api/v2/patrons/login";
                }
            } else if ($api_sub_type == "GET_IDENTITIY_OBJECT") {
                if ($envSet == "development") {
                    $config_arr['ApiUrl'] = "https://preproduction.signzy.tech/api/v2/patrons/<patron-id>/identities";
                } else if ($envSet == "production") {
                    $config_arr['ApiUrl'] = "https://signzy.tech/api/v2/patrons/<patron-id>/identities";
                }
            } else if ($api_sub_type == "PAN_FETCH") {
                if ($envSet == "development") {
                    $config_arr['ApiUrl'] = "https://preproduction.signzy.tech/api/v2/snoops";
                } else if ($envSet == "production") {
                    $config_arr['ApiUrl'] = "https://signzy.tech/api/v2/snoops";
                }
            } else if ($api_sub_type == "PAN_OCR") {
                if ($envSet == "development") {
                    $config_arr['ApiUrl'] = "https://preproduction.signzy.tech/api/v2/snoops";
                } else if ($envSet == "production") {
                    $config_arr['ApiUrl'] = "https://signzy.tech/api/v2/snoops";
                }
            } else if ($api_sub_type == "AADHAAR_OCR") {
                if ($envSet == "development") {
                    $config_arr['ApiUrl'] = "https://preproduction.signzy.tech/api/v2/snoops";
                } else if ($envSet == "production") {
                    $config_arr['ApiUrl'] = "https://signzy.tech/api/v2/snoops";
                }
            } else if ($api_sub_type == "AADHAAR_MASK") {
                if ($envSet == "development") {
                    $config_arr['ApiUrl'] = "https://preproduction.signzy.tech/api/v2/snoops";
                } else if ($envSet == "production") {
                    $config_arr['ApiUrl'] = "https://signzy.tech/api/v2/snoops";
                }
            } else if ($api_sub_type == "GET_ESIGN_TOKEN") {
                if ($envSet == "development") {
                    $config_arr['ApiUrl'] = "https://esign-preproduction.signzy.tech/api/customers/login";
                } else if ($envSet == "production") {
                    $config_arr['ApiUrl'] = "https://esign.signzy.tech/api/customers/login";
                }
            } else if ($api_sub_type == "UPLOAD_ESIGN_DOCUMENT") {
                if ($envSet == "development") {
                    $config_arr['ApiUrl'] = "https://preproduction-persist.signzy.tech/api/base64";
                } else if ($envSet == "production") {
                    $config_arr['ApiUrl'] = "https://persist.signzy.tech/api/base64";
                }
            } else if ($api_sub_type == "AADHAAR_ESIGN") {
                if ($envSet == "development") {
                    $config_arr['ApiUrl'] = "https://esign-preproduction.signzy.tech/api/customers/customerid/aadhaaresigns";
                } else if ($envSet == "production") {
                    $config_arr['ApiUrl'] = "https://esign.signzy.tech/api/customers/customerid/aadhaaresigns";
                }
            } else if ($api_sub_type == "DOWNLOAD_ESIGN_DOCUMENT") {
                if ($envSet == "development") {
                    $config_arr['ApiUrl'] = "https://esign-preproduction.signzy.tech/api/callbacks";
                } else if ($envSet == "production") {
                    $config_arr['ApiUrl'] = "https://esign.signzy.tech/api/callbacks";
                }
            } else if ($api_sub_type == "DIGILOCKER") {
                if ($envSet == "development") {
                    $config_arr['ApiUrl'] = "https://preproduction.signzy.tech/api/v2/patrons/customerid/digilockers";
                } else if ($envSet == "production") {
                    $config_arr['ApiUrl'] = "https://signzy.tech/api/v2/patrons/customerid/digilockers";
                }
            } else if ($api_sub_type == "OFFICE_EMAIL_VERIFICATION") {
                if ($envSet == "development") {
                    $config_arr['ApiUrl'] = "https://preproduction.signzy.tech/api/v2/patrons/customerid/emailverificationsv2";
                } else if ($envSet == "production") {
                    $config_arr['ApiUrl'] = "https://signzy.tech/api/v2/patrons/customerid/emailverificationsv2";
                }
            }
            break;

        CASE "REPAY_API" :
            $config_arr['Status'] = 1;
            $config_arr['Provider'] = "ICICI_EAZYPAY";
            $config_arr['RPMiddleWareUrl'] = "http://loanwallefintech.in:8096/middleware/service/";
            $config_arr['ApiKey'] = 2408430522005001;
            $config_arr['MerchantId'] = 242204;
            $config_arr['ApiUrl'] = "https://eazypay.icicibank.com/EazyPG?merchantid=" . $config_arr['MerchantId'];
            $config_arr['Paymode'] = 9;
            $config_arr['ReferenceNo'] = mt_rand();
            $config_arr['ReturnURL'] = "https://www.surya.com/loan/status1";

            if ($envSet == "production") {
                $config_arr['ApiKey'] = 2408430522005001;
                $config_arr['MerchantId'] = 242204;
                $config_arr['ApiUrl'] = "https://eazypay.icicibank.com/EazyPG?merchantid=" . $config_arr['MerchantId'];
                $config_arr['Paymode'] = 9;
                $config_arr['ReferenceNo'] = date("YmdHis") . rand(1000, 9999);
                $config_arr['ReturnURL'] = "https://www.surya.com/loan/status1";
                $config_arr['RPMiddleWareUrl'] = "http://localhost:8096/middleware/service/";
            }
            break;

        default :
            $config_arr['Status'] = 0;
            $config_arr['ErrorInfo'] = "LW : Invalid config value passed";
            break;
    }


    return $config_arr;
}

function signzy_token_api_call($method_id, $lead_id = 0, $request_array = array(), $retrigger_call = 0) {
    common_log_writer(3, "signzy_token_api_call started | Lead Id : $lead_id | method id : $method_id");
    $envSet = COMP_ENVIRONMENT;

    //RP Variables
    $apiStatusId = 0;
    $apiRequestJson = "";
    $apiResponseJson = "";
    $apiRequestDateTime = date("Y-m-d H:i:s");
    $apiResponseDateTime = "";
    $errorMessage = "";
    $curlError = "";

    $type = "SIGNZY_API";
    $sub_type = "GET_TOKEN";

    if ($method_id == 3) {
        $sub_type = "GET_ESIGN_TOKEN";
    }

    $debug = !empty($_REQUEST['lwtest']) ? 1 : 0;
    $hardcode_response = false;

    $token_string = "";
    $token_return_user_id = "";

    $leadModelObj = new LeadModel();

    try {

        //API Config
        $apiConfig = integration_config($type, $sub_type);

        if ($debug == 1) {
            echo "<pre>";
            print_r($apiConfig);
        }

        if ($apiConfig['Status'] != 1) {
            throw new Exception($apiConfig['ErrorInfo']);
        }

        $apiUrl = $apiConfig["ApiUrl"];
        $apiUserId = $apiConfig["ApiUserId"];
        $apiPassword = $apiConfig["ApiPassword"];

        $tempDetails = $leadModelObj->checkTokenValidity($method_id); //for signzy

        if (count($tempDetails['items']) > 0) {

            $tempDetails = $tempDetails['items'][0];
            $token_response_datetime = $tempDetails['token_response_datetime'];

            $difference_in_minute = intval((strtotime(date("Y-m-d H:i:s")) - strtotime($token_response_datetime)) / 60);
            // api will be not called if last token fetched in last 45 min, token validity is 1 hours.
            if (!empty($tempDetails['token_string']) && !empty($token_response_datetime) && $difference_in_minute >= 0 && $difference_in_minute < 45) {
                $token_string = $tempDetails['token_string'];
                $token_return_user_id = $tempDetails['token_return_user_id'];
                $return_array = array();
                $return_array['status'] = 1;
                $return_array['token'] = $token_string;
                $return_array['token_user_id'] = $token_return_user_id;

                return $return_array;
            }
        }

        $apiRequestJson = '{"username": "' . $apiUserId . '","password": "' . $apiPassword . '"}';

        $apiRequestJson = preg_replace("!\s+!", " ", $apiRequestJson);

        if ($debug == 1) {
            echo "<br/><br/> =======Request======" . $apiRequestJson;
        }

        $apiHeaders = array(
            "content-type: application/json",
            "accept-language: en-US,en;q=0.8",
            "accept: */*"
        );

        if ($hardcode_response) {
            $apiResponseJson = "";
        } else {

            $curl = curl_init($apiUrl);
            curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $apiHeaders);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($curl, CURLOPT_TIMEOUT, 25);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $apiRequestJson);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            $apiResponseJson = curl_exec($curl);
        }

        if ($debug == 1) {
            echo "<br/><br/> =======Response======" . $apiResponseJson;
        }

        $apiResponseJson = preg_replace("!\s+!", " ", $apiResponseJson);

        $apiResponseDateTime = date("Y-m-d H:i:s");

        if (curl_errno($curl) && !$hardcode_response) {
            $curlError = "(" . curl_errno($curl) . ") " . curl_error($curl) . "to url " . $apiUrl;
            curl_close($curl);
            throw new RuntimeException("Something went wrong. Please try after sometime.");
        } else {

            if (isset($curl)) {
                curl_close($curl);
            }

            $apiResponseData = json_decode($apiResponseJson, true);

            if (!empty($apiResponseData)) {

                $apiResponseData = common_trim_data_array($apiResponseData);

                if (!empty($apiResponseData['id'])) {
                    $apiStatusId = 1;
                    $token_string = $apiResponseData['id'];
                    $token_return_user_id = $apiResponseData['userId'];
                } else {
                    throw new ErrorException("accessToken was not received from Token API.");
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
        $retrigger_call = ($retrigger_call == 0) ? 1 : 0;
    } catch (Exception $e) {
        $apiStatusId = 4;
        $errorMessage = $e->getMessage();
    }

    $insertApiLog = array();
    $insertApiLog["token_provider"] = 1;
    $insertApiLog["token_method_id"] = $method_id; //1=> Id Proof Login, 3=> eSign Api Login
    $insertApiLog["token_lead_id"] = !empty($lead_id) ? $lead_id : NULL;
    $insertApiLog["token_api_status_id"] = $apiStatusId;
    $insertApiLog["token_request"] = addslashes($apiRequestJson);
    $insertApiLog["token_response"] = addslashes($apiResponseJson);
    $insertApiLog["token_string"] = $token_string;
    $insertApiLog["token_return_user_id"] = $token_return_user_id;
    $insertApiLog["token_errors"] = ($apiStatusId == 3) ? addslashes($curlError) : addslashes($errorMessage);
    $insertApiLog["token_request_datetime"] = $apiRequestDateTime;
    $insertApiLog["token_response_datetime"] = !empty($apiResponseDateTime) ? $apiResponseDateTime : date("Y-m-d H:i:s");

    $leadModelObj->insertTable("api_service_token_logs", $insertApiLog);

    $return_array = array();
    $return_array['status'] = $apiStatusId;
    $return_array['token'] = $token_string;
    $return_array['token_user_id'] = $token_return_user_id;
    $return_array['errors'] = !empty($errorMessage) ? "Token Error:" . $errorMessage : "";

    return $return_array;
}

function signzy_identity_object_api_call($request_type, $lead_id = 0, $request_array = array()) {
    common_log_writer(3, "signzy_identity_object_api_call started | $lead_id");
    $envSet = COMP_ENVIRONMENT;

    //RP Variables
    $apiStatusId = 0;
    $apiRequestJson = "";
    $apiResponseJson = "";
    $apiRequestDateTime = date("Y-m-d H:i:s");
    $apiResponseDateTime = "";
    $errorMessage = "";
    $curlError = "";

    $type = "SIGNZY_API";
    $sub_type = "GET_IDENTITIY_OBJECT";

    $debug = !empty($_REQUEST['lwtest']) ? 1 : 0;
    $hardcode_response = false;

    $item_id_string = "";
    $accessToken_string = "";
    $token_string = $request_array['token'];
    $token_return_user_id = $request_array['token_user_id'];

    $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : 0;

    $ocr_file_1 = !empty($request_array['ocr_file_1']) ? $request_array['ocr_file_1'] : "";
    $ocr_file_2 = !empty($request_array['ocr_file_2']) ? $request_array['ocr_file_2'] : "";

    $leadModelObj = new LeadModel();

    try {


        if (empty($token_string)) {
            throw new Exception("Token is missing while creating identity object.");
        }

        if (empty($token_return_user_id)) {
            throw new Exception("Token user id is missing while creating identity object.");
        }

        //API Config
        $apiConfig = integration_config($type, $sub_type);

        $apiUrl = $apiConfig["ApiUrl"] = str_replace('<patron-id>', $token_return_user_id, $apiConfig["ApiUrl"]);

        if ($debug == 1) {
            echo "<pre>";
            print_r($apiConfig);
        }

        if ($apiConfig['Status'] != 1) {
            throw new Exception($apiConfig['ErrorInfo']);
        }

        $apiRequestJsonArray = array();
        $apiRequestJsonArray["type"] = $request_type;
        $apiRequestJsonArray["email"] = "admin@signzy.com";
        $apiRequestJsonArray["callbackUrl"] = "https://www.surya.com/";
        $apiRequestJsonArray["images"] = [];

        if (!empty($ocr_file_1)) {
            $apiRequestJsonArray["images"][] = COMP_DOC_URL . $ocr_file_1;
        }

        if (!empty($ocr_file_2)) {
            $apiRequestJsonArray["images"][] = COMP_DOC_URL . $ocr_file_2;
        }


        $apiRequestJson = json_encode($apiRequestJsonArray);

        $apiRequestJson = preg_replace("!\s+!", " ", $apiRequestJson);

        if ($debug == 1) {
            echo "<br/><br/> =======Request======" . $apiRequestJson;
        }

        $apiHeaders = array(
            "Content-Type: application/json",
            "accept-language: en-US,en;q=0.8",
            "accept: */*",
            "authorization: $token_string"
        );

        if ($debug == 1) {
            echo "<br/><br/> =======Header======" . json_encode($apiHeaders);
        }

        if ($hardcode_response) {
            $apiResponseJson = "";
        } else {

            $curl = curl_init($apiUrl);
            curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $apiHeaders);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($curl, CURLOPT_TIMEOUT, 60);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $apiRequestJson);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            $apiResponseJson = curl_exec($curl);
        }

        if ($debug == 1) {
            echo "<br/><br/> =======Response======" . $apiResponseJson;
        }

        $apiResponseJson = preg_replace("!\s+!", " ", $apiResponseJson);

        $apiResponseDateTime = date("Y-m-d H:i:s");

        if (curl_errno($curl) && !$hardcode_response) {
            $curlError = "(" . curl_errno($curl) . ") " . curl_error($curl) . "to url " . $apiUrl;
            curl_close($curl);
            throw new RuntimeException("Something went wrong. Please try after sometime.");
        } else {

            if (isset($curl)) {
                curl_close($curl);
            }

            $apiResponseData = json_decode($apiResponseJson, true);

            if (!empty($apiResponseData)) {

                $apiResponseData = common_trim_data_array($apiResponseData);

                if (!empty($apiResponseData['id']) && !empty($apiResponseData['accessToken'])) {
                    $apiStatusId = 1;
                    $item_id_string = $apiResponseData['id'];
                    $accessToken_string = $apiResponseData['accessToken'];
                } else {
                    throw new ErrorException("accessToken was not received from Token API.");
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
        $retrigger_call = ($retrigger_call == 0) ? 1 : 0;
    } catch (Exception $e) {
        $apiStatusId = 4;
        $errorMessage = $e->getMessage();
    }

    $insertApiLog = array();
    $insertApiLog["token_provider"] = 1;
    $insertApiLog["token_method_id"] = 2;
    $insertApiLog["token_lead_id"] = !empty($lead_id) ? $lead_id : NULL;
    $insertApiLog["token_api_status_id"] = $apiStatusId;
    $insertApiLog["token_request"] = addslashes($apiRequestJson);
    $insertApiLog["token_response"] = addslashes($apiResponseJson);
    $insertApiLog["token_string"] = $item_id_string;
    $insertApiLog["token_errors"] = ($apiStatusId == 3) ? addslashes($curlError) : addslashes($errorMessage);
    $insertApiLog["token_request_datetime"] = $apiRequestDateTime;
    $insertApiLog["token_response_datetime"] = !empty($apiResponseDateTime) ? $apiResponseDateTime : date("Y-m-d H:i:s");
    $insertApiLog["token_user_id"] = $user_id;

    $leadModelObj->insertTable("api_service_token_logs", $insertApiLog);

    $return_array = array();
    $return_array['status'] = $apiStatusId;
    $return_array['item_id_string'] = $item_id_string;
    $return_array['access_token_string'] = $accessToken_string;
    $return_array['errors'] = !empty($errorMessage) ? "IdentityObj Error:" . $errorMessage : "";

    return $return_array;
}

function MiddlewareApiReqEncrypt($url, $Provider = "", $apiName = "", $data = "", $key = "", $lead_id = 0) {

    $envSet = COMP_ENVIRONMENT;
    $leadModelObj = new LeadModel();

    $apiStatusId = 0;
    $apiRequestDateTime = date("Y-m-d H:i:s");
    $curlError = "";
    $errorMessage = "";

    $return_array = array("status" => 0, "errors" => "Encryption: Something went wrong. Please contact to LW team.", "output_data" => "");

    $request_array = array(
        "environment" => "UAT",
        "plainText" => base64_encode($data),
        "requestFor" => "encrypt",
        "clientId" => $Provider,
        "encKey" => $key,
        "apiName" => $apiName
    );

    $apiHeaders = array('Content-Type:application/json');

    if ($envSet == "production") {
        $request_array['environment'] = "PROD";
    }

    $apiRequestJson = json_encode($request_array);

    $return_array["raw_request"] = $apiRequestJson;

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $apiHeaders);
    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $apiRequestJson);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    $apiResponseJson = curl_exec($curl);

    $apiResponseDateTime = date("Y-m-d H:i:s");

    $return_array["raw_response"] = $apiResponseJson;

    if (curl_errno($curl)) {

        $apiStatusId = 3;
        $curlError = "Encryption: (" . curl_errno($curl) . ") " . curl_error($curl) . " to url " . $url;
        $return_array["errors"] = $curlError; //"Some error occurred during java lang.e1. Please try again..";
        curl_close($curl);
    } else {
        curl_close($curl);

        $apiResponseData = json_decode($apiResponseJson, true);

        if ($apiResponseData["status"] == "true") {
            $apiStatusId = 1;
            $return_array["errors"] = "";
            $return_array["output_data"] = base64_decode($apiResponseData["response"]);
        } else {
            $apiStatusId = 2;
            $errorMessage = $apiResponseData['message'];
            $return_array["errors"] = "Some error occurred during java lang.e2. Please try again..";
        }
    }

    $insertApiLog = array();
    $insertApiLog["middleware_product_id"] = $key;
    $insertApiLog["middleware_method_id"] = 1;
    $insertApiLog["middleware_api_name"] = $apiName;
    $insertApiLog["middleware_lead_id"] = $lead_id;
    $insertApiLog["middleware_api_status_id"] = $apiStatusId;
    $insertApiLog["middleware_request"] = addslashes($apiRequestJson);
    $insertApiLog["middleware_response"] = addslashes($apiResponseJson);
    $insertApiLog["middleware_errors"] = ($apiStatusId == 3) ? addslashes($curlError) : addslashes($errorMessage);
    $insertApiLog["middleware_request_datetime"] = $apiRequestDateTime;
    $insertApiLog["middleware_response_datetime"] = !empty($apiResponseDateTime) ? $apiResponseDateTime : date("Y-m-d H:i:s");

    $leadModelObj->insertTable("api_java_middleware_logs", $insertApiLog);

    $return_array["status"] = $apiStatusId;

    return $return_array;
}

?>
