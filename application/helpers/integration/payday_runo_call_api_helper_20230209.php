<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('payday_call_management_api_call')) {

    function payday_call_management_api_call($method_name = "", $lead_id = 0, $request_array = array()) {

        $responseArray = array("status" => 0, "error_msg" => "");

        $opertion_array = array(
            "LEAD_CAT_SANCTION" => 1,
            "PRECOLLX_CAT_SANCTION" => 2,
            "COLLECTION_CAT_SANCTION" => 3,
        );

        $method_id = $opertion_array[$method_name];

        if ($method_id == 1) {
            $responseArray = runo_sanction_allocation_api($lead_id, $request_array);
        } elseif ($method_id == 2) {
            $responseArray = runo_precollex_sanction_allocation_api($lead_id, $request_array);
        } elseif ($method_id == 3) {
            $responseArray = runo_sanction_collection_allocation_api($lead_id, $request_array);
        } else {
            $responseArray["error_msg"] = "invalid opertation called";
        }

        return $responseArray;
    }

}



if (!function_exists('runo_sanction_allocation_api')) {

    function runo_sanction_allocation_api($lead_id, $request_array = array()) {

        $ci = & get_instance();
        $ci->load->helper('integration/integration_config');
        $ci->load->model('Integration/Integration_Model', 'IntegrationModel');

        $apiStatusId = 0;

        $apiRequestJson = "";
        $apiResponseJson = "";
        $apiRequestDateTime = date("Y-m-d H:i:s");
        $apiResponseDateTime = "";
        $errorMessage = "";
        $curlError = "";

        $type = "RUNO_CALL_CRM";
        $sub_type = "CALL_ALLOCATION";
        $process_name = "Sanction Team";

        $hardcode_response = false;

        $debug = !empty($_REQUEST['lwtest']) ? 1 : 0;

        $applicationDetails = array();
        $input_mobile = "";
        $agent_mobile = "";
        $assignedTo = "";

        $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : "";
        $call_type = !empty($request_array['call_type']) ? $request_array['call_type'] : "";

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
            $apiToken = $apiConfig["ApiKey"];

            if (empty($lead_id)) {
                throw new Exception("Missing Lead Id.");
            }

            $appDataReturnArr = $ci->IntegrationModel->getLeadFullDetails($lead_id);

            if ($appDataReturnArr['status'] === 1) {

                $applicationDetails = $appDataReturnArr['app_data'];
                $mobile = $applicationDetails["mobile"];
                $user_id = $applicationDetails["lead_screener_assign_user_id"];

                if ($call_type == 2) {
                    $mobile = $applicationDetails["alternate_mobile"];
                }

                if (!is_mobile($mobile)) {
                    throw new Exception("Mobile number is not correct.");
                }
            } else {
                throw new Exception("Application details does not exist.");
            }

            if (!empty($user_id)) {

                $crmUserReturnArr = $ci->IntegrationModel->getUMSUserDetails($user_id);

                if ($crmUserReturnArr['status'] === 1) {
                    $crmUserDetails = $crmUserReturnArr['app_data'];
                    $agent_mobile = $crmUserDetails['mobile'];
                }
            }


            $input_mobile = $mobile;
            $email = !empty($applicationDetails["email"]) ? $applicationDetails["email"] : '';
            $name = !empty($applicationDetails["name"]) ? $applicationDetails["name"] : 'NA';
            $address = !empty($applicationDetails["address"]) ? $applicationDetails["address"] : '';
            $city = !empty($applicationDetails["city"]) ? $applicationDetails["city"] : '';
            $state = !empty($applicationDetails["state"]) ? $applicationDetails["state"] : '';
            $pancard = !empty($applicationDetails["pancard"]) ? $applicationDetails["pancard"] : 'XXXXX0123X';
            $pincode = !empty($applicationDetails["pincode"]) ? $applicationDetails["pincode"] : '999999';
            $source = !empty($applicationDetails["source"]) ? $applicationDetails["source"] : 'NA';

            if (!empty($agent_mobile)) {
                $assignedTo = "+91" . $agent_mobile;
            }

            $apiRequestArray = array(
                "customer" => array(
                    "name" => $name,
                    "phoneNumber" => "+91" . $input_mobile,
                    "email" => $email,
                    "company" => array(
                        "name" => $pancard,
                        "address" => array(
                            "street" => $address,
                            "city" => $city,
                            "state" => $state,
                            "country" => "India",
                            "pincode" => $pincode
                        ),
                        "kdm" => array(
                            "name" => $lead_id,
                            "phoneNumber" => $source
                        )
                    )
                ),
                "priority" => 3,
                "notes" => "Self Allocated",
                "processName" => $process_name
            );

            if (!empty($assignedTo)) {
                $apiRequestArray['assignedTo'] = $assignedTo;
            }

            $apiRequestJson = json_encode($apiRequestArray);

            $apiRequestJson = preg_replace("!\s+!", " ", $apiRequestJson);

            $apiHeaders[] = "Content-Type: application/json";
            $apiHeaders[] = "Accept: application/json";
            $apiHeaders[] = "Auth-Key: $apiToken";
            $apiHeaders[] = "Content-Length: " . strlen($apiRequestJson);

            if ($debug == 1) {
                echo "<br/><br/> =======Header Plain======<br/><br/>" . json_encode($apiHeaders);
                echo "<br/><br/> =======Request Plain======<br/><br/>" . $apiRequestJson;
            }


            if (empty($assignedTo)) {
                $apiUrl .= "?isCommonPool=true";
            }

            $curl = curl_init($apiUrl);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $apiHeaders);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $apiRequestJson);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

            $apiResponseJson = curl_exec($curl);

            $apiResponseDateTime = date("Y-m-d H:i:s");
            $apiResponseJson = preg_replace("!\s+!", " ", $apiResponseJson);

            if ($debug == 1) {
                echo "<br/><br/> =======Response Plain ======<br/><br/>" . $apiResponseJson;
            }

            if (curl_errno($curl) && !$hardcode_response) {
                $curlError = "(" . curl_errno($curl) . ") " . curl_error($curl) . " to url " . $apiUrl;
                curl_close($curl);
                throw new RuntimeException("Something went wrong. Please try after sometime.");
            } else {

                if (isset($curl)) {
                    curl_close($curl);
                }


                $apiResponseData = json_decode($apiResponseJson, true);

                if (!empty($apiResponseData)) {

                    $apiResponseData = trim_data_array($apiResponseData);

                    if (!empty($apiResponseData)) {

                        if (isset($apiResponseData['statusCode']) && $apiResponseData['statusCode'] == 0) {
                            $apiStatusId = 1;
                        } else {
                            $temp_error = !empty($apiResponseData['message']) ? $apiResponseData['message'] : "Some error occurred. Please try again..";
                            throw new ErrorException($temp_error);
                        }
                    } else {
                        $temp_error = !empty($apiResponseData['message']) ? $apiResponseData['message'] : "Some error occurred. Please try again.";
                        throw new ErrorException($temp_error);
                    }
                } else {
                    throw new ErrorException("Invalid api response..");
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

        if ($applicationDetails["lead_status_id"] > 0) {
            $lead_followup = [
                'lead_id' => $lead_id,
                'user_id' => $user_id,
                'status' => $applicationDetails["status"],
                'stage' => $applicationDetails["stage"],
                'lead_followup_status_id' => $applicationDetails["lead_status_id"],
                'remarks' => addslashes("RUNO call assigned"),
                'created_on' => date("Y-m-d H:i:s")
            ];

            $ci->IntegrationModel->insert("lead_followup", $lead_followup);
        }

        $insertApiLog = array();
        $insertApiLog["cml_provider_id"] = 1;
        $insertApiLog["cml_user_id"] = $user_id;
        $insertApiLog["cml_method_id"] = 1; //Call Sanction Allocation
        $insertApiLog["cml_lead_id"] = !empty($lead_id) ? $lead_id : NULL;
        $insertApiLog["cml_api_status_id"] = $apiStatusId;
        $insertApiLog["cml_mobile"] = $input_mobile;
        $insertApiLog["cml_request"] = $apiRequestJson;
        $insertApiLog["cml_response"] = $apiResponseJson;
        $insertApiLog["cml_errors"] = ($apiStatusId == 3) ? addslashes($curlError) : addslashes($errorMessage);
        $insertApiLog["cml_request_datetime"] = $apiRequestDateTime;
        $insertApiLog["cml_response_datetime"] = !empty($apiResponseDateTime) ? $apiResponseDateTime : date("Y-m-d H:i:s");

        $return_log_id = $ci->IntegrationModel->insert("api_call_management_logs", $insertApiLog);

        $returnResponseData = array();
        $returnResponseData['status'] = $apiStatusId;
        $returnResponseData['mobile'] = $input_mobile;
        $returnResponseData['log_id'] = $return_log_id;
        $returnResponseData['error_msg'] = !empty($errorMessage) ? $errorMessage : "";

        if ($debug == 1) {
            $returnResponseData['raw_request'] = $apiRequestJson;
            $returnResponseData['raw_response'] = $apiResponseJson;
            $returnResponseData['parse_response'] = $apiResponseData;
        }
        return $returnResponseData;
    }

}

if (!function_exists('runo_precollex_sanction_allocation_api')) {

    function runo_precollex_sanction_allocation_api($lead_id, $request_array = array()) {

        $ci = & get_instance();
        $ci->load->helper('integration/integration_config');
        $ci->load->helper('commonfun');
        $ci->load->model('Integration/Integration_Model', 'IntegrationModel');

        $apiStatusId = 0;
        $apiRequestJson = "";
        $apiResponseJson = "";
        $apiRequestDateTime = date("Y-m-d H:i:s");
        $apiResponseDateTime = "";
        $errorMessage = "";
        $curlError = "";

        $type = "RUNO_CALL_CRM";
        $sub_type = "CALL_ALLOCATION";

        $process_name = "Pre-Collection Team";

        $hardcode_response = false;

        $debug = !empty($_REQUEST['lwtest']) ? 1 : 0;

        $applicationDetails = array();
        $input_mobile = "";
        $agent_mobile = "";
        $assignedTo = "";

        $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : ""; //for testing
        $call_type = !empty($request_array['call_type']) ? $request_array['call_type'] : "";

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
            $apiToken = $apiConfig["ApiKey"];

            if (empty($lead_id)) {
                throw new Exception("Missing Lead Id.");
            }


            $appDataReturnArr = $ci->IntegrationModel->getLoanApplicationDetails($lead_id);
            traceObject($appDataReturnArr);

            if ($appDataReturnArr['status'] === 1) {

                $loanDetails = $appDataReturnArr['app_data'];
                $input_mobile = $loanDetails["mobile"];

                if ($call_type == 2) {
                    $input_mobile = $applicationDetails["alternate_mobile"];
                }

                if (!is_mobile($input_mobile)) {
                    throw new Exception("Mobile number is not correct.");
                }
            } else {
                throw new Exception("Application details does not exist.");
            }

            if (!empty($user_id)) {

                $crmUserReturnArr = $ci->IntegrationModel->getUMSUserDetails($user_id);

                if ($crmUserReturnArr['status'] === 1) {
                    $crmUserDetails = $crmUserReturnArr['app_data'];
                    $agent_mobile = $crmUserDetails['mobile'];
                }
            }

//            $input_mobile = $mobile;
            $email = !empty($loanDetails["email"]) ? $loanDetails["email"] : '';
            $name = !empty($loanDetails["name"]) ? $loanDetails["name"] : 'NA';
            $address = !empty($loanDetails["address"]) ? $loanDetails["address"] : '';
            $city = !empty($loanDetails["city"]) ? $loanDetails["city"] : '';
            $state = !empty($loanDetails["state"]) ? $loanDetails["state"] : '';
            $branch = !empty($loanDetails["branch"]) ? $loanDetails["branch"] : 'NA1';
            $pancard = !empty($loanDetails["pancard"]) ? strtoupper($loanDetails["pancard"]) : '';
            $pincode = !empty($loanDetails["pincode"]) ? $loanDetails["pincode"] : '';
            $source = !empty($loanDetails["source"]) ? $loanDetails["source"] : 'NA';
//            $agent_mobile_number = $request_array['mobile'];
            $loan_no = !empty($loanDetails["loan_no"]) ? $loanDetails["loan_no"] : 'NA';
            $loan_amount = !empty($loanDetails["loan_recommended"]) ? $loanDetails["loan_recommended"] : 0;
            $repay_amount = !empty($loanDetails["repayment_amount"]) ? $loanDetails["repayment_amount"] : 'NA';
            $roi = !empty($loanDetails["roi"]) ? $loanDetails["roi"] : 'NA';
            $tenure = !empty($loanDetails["tenure"]) ? $loanDetails["tenure"] : 'NA';
            $disbursal_date = !empty($loanDetails["disbursal_date"]) ? $loanDetails["disbursal_date"] : 'NA';
            $repay_date = !empty($loanDetails["repayment_date"]) ? $loanDetails["repayment_date"] : 'NA';
            $status = !empty($loanDetails["status"]) ? $loanDetails["status"] : 'NA';
//            $last_repay_date = !empty($loanDetails["date_of_recived"]) ? $loanDetails["date_of_recived"] : 'NA';
            $received_amount = !empty($loanDetails["loan_total_received_amount"]) ? $loanDetails["loan_total_received_amount"] : 0;

            if (!empty($agent_mobile)) {
                $assignedTo = "+91" . $agent_mobile;
            }

            $apiRequestArray = array(
                "customer" => array(
                    "name" => $name,
                    "phoneNumber" => "+91" . $input_mobile,
                    "email" => $email,
                    "branch" => $branch,
                    "company" => array(
                        "name" => $pancard,
                        "address" => array(
                            "street" => $address,
                            "city" => $city,
                            "state" => $state,
                            "country" => "India",
                            "pincode" => $pincode
                        ),
                        "kdm" => array(
                            "name" => $lead_id,
                            "phoneNumber" => $status
                        )
                    )
                ),
                "priority" => 3,
                "processName" => $process_name
            );

            if (!empty($assignedTo)) {
                $apiRequestArray['assignedTo'] = $assignedTo;
            }


            $apiRequestArray['userFields'] = array(
                array(
                    "name" => "source",
                    "value" => "Referral"
                ),
                array(
                    "name" => "Lead_id",
                    "value" => $lead_id
                ),
                array(
                    "name" => "Loan_no",
                    "value" => $loan_no
                ),
                array(
                    "name" => "Loan_amount",
                    "value" => $loan_amount
                ),
                array(
                    "name" => "Roi",
                    "value" => $roi
                ),
                array(
                    "name" => "Tenure",
                    "value" => $tenure
                ),
                array(
                    "name" => "Repayment_amount",
                    "value" => $repay_amount
                ),
                array(
                    "name" => "Disbursal_date",
                    "value" => $disbursal_date
                ),
                array(
                    "name" => "Repayment_date",
                    "value" => $repay_date
                ),
                array(
                    "name" => "Received_amount",
                    "value" => $received_amount
                ),
                array(
                    "name" => "Loan_status",
                    "value" => $status
                )
            );

            $apiRequestJson = json_encode($apiRequestArray);

            $apiRequestJson = preg_replace("!\s+!", " ", $apiRequestJson);

            $apiHeaders[] = "Content-Type: application/json";
            $apiHeaders[] = "Accept: application/json";
            $apiHeaders[] = "Auth-Key: $apiToken";
            $apiHeaders[] = "Content-Length: " . strlen($apiRequestJson);

            if ($debug == 1) {
                echo "<br/><br/> =======Header Plain======<br/><br/>" . json_encode($apiHeaders);
                echo "<br/><br/> =======Request Plain======<br/><br/>" . $apiRequestJson;
            }

            if (empty($assignedTo)) {
                $apiUrl .= "?isCommonPool=true";
            }

            $curl = curl_init($apiUrl);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $apiHeaders);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $apiRequestJson);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

            $apiResponseJson = curl_exec($curl);

            $apiResponseDateTime = date("Y-m-d H:i:s");
            $apiResponseJson = preg_replace("!\s+!", " ", $apiResponseJson);

            if ($debug == 1) {
                echo "<br/><br/> =======Response Plain ======<br/><br/>" . $apiResponseJson;
            }

            if (curl_errno($curl) && !$hardcode_response) {
                $curlError = "(" . curl_errno($curl) . ") " . curl_error($curl) . " to url " . $apiUrl;
                curl_close($curl);
                throw new RuntimeException("Something went wrong. Please try after sometime.");
            } else {

                if (isset($curl)) {
                    curl_close($curl);
                }


                $apiResponseData = json_decode($apiResponseJson, true);

                if (!empty($apiResponseData)) {

                    $apiResponseData = trim_data_array($apiResponseData);

                    if (!empty($apiResponseData)) {

                        if (isset($apiResponseData['statusCode']) && $apiResponseData['statusCode'] == 0) {
                            $apiStatusId = 1;
                        } else {
                            $temp_error = !empty($apiResponseData['message']) ? $apiResponseData['message'] : "Some error occurred. Please try again..";
                            throw new ErrorException($temp_error);
                        }
                    } else {
                        $temp_error = !empty($apiResponseData['message']) ? $apiResponseData['message'] : "Some error occurred. Please try again.";
                        throw new ErrorException($temp_error);
                    }
                } else {
                    throw new ErrorException("Invalid api response..");
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
        $insertApiLog["cml_provider_id"] = 1;
        $insertApiLog["cml_user_id"] = $user_id;
        $insertApiLog["cml_method_id"] = 2; //Call Pre-Collection Allocation for Sanctrion Team
        $insertApiLog["cml_lead_id"] = !empty($lead_id) ? $lead_id : NULL;
        $insertApiLog["cml_api_status_id"] = $apiStatusId;
        $insertApiLog["cml_mobile"] = $input_mobile;
        $insertApiLog["cml_request"] = $apiRequestJson;
        $insertApiLog["cml_response"] = $apiResponseJson;
        $insertApiLog["cml_errors"] = ($apiStatusId == 3) ? addslashes($curlError) : addslashes($errorMessage);
        $insertApiLog["cml_request_datetime"] = $apiRequestDateTime;
        $insertApiLog["cml_response_datetime"] = !empty($apiResponseDateTime) ? $apiResponseDateTime : date("Y-m-d H:i:s");

        $return_log_id = $ci->IntegrationModel->insert("api_call_management_logs", $insertApiLog);

        $returnResponseData = array();
        $returnResponseData['status'] = $apiStatusId;
        $returnResponseData['mobile'] = $input_mobile;
        $returnResponseData['log_id'] = $return_log_id;
        $returnResponseData['error_msg'] = !empty($errorMessage) ? $errorMessage : "";

        if ($debug == 1) {
            $returnResponseData['raw_request'] = $apiRequestJson;
            $returnResponseData['raw_response'] = $apiResponseJson;
            $returnResponseData['parse_response'] = $apiResponseData;
        }

        return $returnResponseData;
    }

}
        
