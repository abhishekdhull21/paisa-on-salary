<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('payday_loan_disbursement_api_call')) {

    function payday_loan_disbursement_call($lead_id = 0, $request_array = array()) {

        $responseArray = array("status" => 0, "error_msg" => "");

        $transRefNoCreateFlag = true; //create new payment request

        $envSet = ENVIRONMENT;

        $ci = & get_instance();
        $ci->load->model('Integration/Integration_Model', 'IntegrationModel');

        $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : "9999"; //for testing

        $disbursementTransArray = $ci->IntegrationModel->getDisbursementTransLogs($lead_id); //check the previous transactions of disbursement

        if (!empty($disbursementTransArray)) {
            //Trans ref no will be same for pending status.
            if (in_array($disbursementTransArray['disb_trans_status_id'], array(1, 2, 4))) {//initiated, pending and hold
                $transRefNoCreateFlag = false; //not need to create new payment request.
                $tranRefNo = $disbursementTransArray["disb_trans_reference_no"]; //previous transaction no
                $disb_trans_id = $disbursementTransArray["disb_trans_id"]; //previous transaction pk id

                if ($disbursementTransArray["disb_trans_payment_mode_id"] == 1 && $request_array['payment_mode_id'] == 2) {
                    $responseArray['status'] = 4;
                    $responseArray['error_msg'] = "Disbursement not allowed as transaction initiated as Online";
                    return $responseArray;
                }
            } else if (in_array($disbursementTransArray['disb_trans_status_id'], array(3))) {//failed
                $transRefNoCreateFlag = true;
            } else if (in_array($disbursementTransArray['disb_trans_status_id'], array(5))) {
                $responseArray['status'] = 4;
                $responseArray['error_msg'] = "Disbursement already done to this application.";
                return $responseArray;
            }
        }

        if ($transRefNoCreateFlag) {//create the disbursement request
            $tranRefNo = "LWUAT" . date("YmdHis") . rand(100, 999);

            if ($envSet == 'production') {
                $tranRefNo = "LWPRD" . date("YmdHis") . rand(100, 999);
            }
            $disb_trans_array = array();
            $disb_trans_array["disb_trans_lead_id"] = $lead_id;
            $disb_trans_array["disb_trans_reference_no"] = $tranRefNo;
            $disb_trans_array["disb_trans_bank_id"] = $request_array['bank_id'];
            $disb_trans_array["disb_trans_payment_mode_id"] = $request_array['payment_mode_id']; //1=>Online,2=>Offline
            $disb_trans_array["disb_trans_payment_type_id"] = $request_array['payment_type_id']; //1=>IMPS,2=>NEFT
            $disb_trans_array["disb_trans_status_id"] = 1; //1=>initiated,2=>pending,3=>failed,4=>hold,5=>completed
            $disb_trans_array["disb_trans_created_by"] = $user_id;
            $disb_trans_array["disb_trans_created_on"] = date("Y-m-d H:i:s");

            if ($request_array['payment_mode_id'] == 2) {
                $disb_trans_array["disb_trans_status_id"] = 5;
            }

            $disb_trans_id = $ci->IntegrationModel->insert("lead_disbursement_trans_log", $disb_trans_array);
        }

        $request_array['tran_ref_no'] = $tranRefNo;

        if ($request_array['payment_mode_id'] == 1) {
            if ($request_array['bank_id'] == 1 && $request_array['payment_type_id'] == 1) {
                $request_array['disb_trans_id'] = $disb_trans_id;
                $responseArray = icici_disburse_loan_amount_api($lead_id, $request_array);
            } else {
                $responseArray["status"] = 4;
                $responseArray["error_msg"] = "Bank is not available for online mode.";
            }
        } else {
            $responseArray["status"] = 1;
        }


        return $responseArray;
    }

}


if (!function_exists('icici_disburse_loan_amount_api')) {


    function icici_disburse_loan_amount_api($lead_id, $request_array) {

        $api_disburse_bypass_mobile = array(); //"9560807913"

        $envSet = ENVIRONMENT;

        $ci = & get_instance();
        $ci->load->helper('integration/integration_config');
        $ci->load->model('Integration/Integration_Model', 'IntegrationModel');

        $apiStatusId = 0;
        $apiRequestJson = "";
        $apiResponseJson = "";
        $esbApiRequestJson = "";
        $esbApiResponseJson = "";
        $apiRequestDateTime = date("Y-m-d H:i:s");
        $apiResponseDateTime = "";
        $errorMessage = "";
        $curlError = "";
        $parseResponseData = "";
        $type = "ICICI_DISBURSEMENT_CALL";
        $sub_type = "DO_DISBURSEMENT";
        $product_id = 1;

        $hardcode_response = false;

        $debug = !empty($_REQUEST['lwtest']) ? 0 : 0;

        $applicationDetails = array();

        $master_payment_mode = array(1 => "IMPS", 2 => "NEFT");

        $beneAccNo = "";
        $beneIFSC = "";
        $beneName = "";
        $loan_account_number = "";
        $loan_amount = "";
        $bank_reference_no = "";
        $payment_reference_no = "";
        $loan_id = "";

        $trans_type = !empty($request_array['payment_type_id']) ? $request_array['payment_type_id'] : "";
        $tranRefNo = !empty($request_array['tran_ref_no']) ? $request_array['tran_ref_no'] : "";
        $disb_trans_id = !empty($request_array['disb_trans_id']) ? $request_array['disb_trans_id'] : "";
        $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : "9999";

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

            $apiKey = $apiConfig["ApiKey"];

            $apiPassCode = $apiConfig["ApiPassCode"];

            $apiBCID = $apiConfig["ApiBCID"];

            $RPMiddleWareUrl = $apiConfig["RPMiddleWareUrl"];

            if (empty($lead_id)) {
                throw new Exception("Missing Lead Id.");
            }

            if (empty($trans_type)) {
                throw new Exception("Missing payment mode type.");
            }

            if (empty($master_payment_mode[$trans_type])) {
                throw new Exception("Invalid payment mode type.");
            }

            if (!in_array($user_id, array(64, 153))) {//Rahul & MD ISLAM ACCESS
                throw new Exception("Un-Authorized access of disbursement api.");
            }

            $appDataReturnArr = $ci->IntegrationModel->getLeadDetails($lead_id);

            if ($appDataReturnArr['status'] === 1) {
                $applicationDetails = $appDataReturnArr['app_data'];

                if (empty($applicationDetails)) {
                    throw new Exception("Application details cannot be empty.");
                } else {


                    $lead_status_id = $applicationDetails["lead_status_id"];

                    if (in_array($applicationDetails['mobile'], $api_disburse_bypass_mobile) && $envSet == 'development') {
//                    $hardcode_response = true;
                    }
                }
            }

            $camDataReturnArr = $ci->IntegrationModel->getLeadCAMDetails($lead_id);

            if ($camDataReturnArr['status'] === 1) {
                $camDetails = $camDataReturnArr['cam_data'];
                if (empty($camDetails)) {
                    throw new Exception("CAM details cannot be empty.");
                }
            }

            $loanDataReturnArr = $ci->IntegrationModel->getLeadLoanDetails($lead_id);

            if ($loanDataReturnArr['status'] === 1) {

                $loanDetails = $loanDataReturnArr['loan_data'];

                if (empty($loanDetails)) {
                    throw new Exception("Loan details cannot be empty.");
                } else {
                    $loan_id = !empty($loanDetails["loan_id"]) ? $loanDetails["loan_id"] : "";
                    $loan_amount = !empty($loanDetails["recommended_amount"]) ? $loanDetails["recommended_amount"] : "";
                    $loan_account_number = !empty($loanDetails["loan_no"]) ? $loanDetails["loan_no"] : "";
                }
            }

            $bankingDataReturnArr = $ci->IntegrationModel->getCustomerAccountDetails($lead_id);

            if ($bankingDataReturnArr['status'] === 1) {
                $bankingDetails = $bankingDataReturnArr['banking_data'];
                if (empty($bankingDetails)) {
                    throw new Exception("Customer banking details not found.");
                } else {
                    $beneName = !empty($bankingDetails["beneficiary_name"]) ? $bankingDetails["beneficiary_name"] : "";
                    $beneAccNo = !empty($bankingDetails["account"]) ? $bankingDetails["account"] : "";
                    $beneIFSC = !empty($bankingDetails["ifsc_code"]) ? $bankingDetails["ifsc_code"] : "";
                }
            } else {
                throw new Exception("Please verify the customer banking details.");
            }



            if (empty($loan_account_number)) {
                throw new Exception("Please generate loan account number for loan disbursement.");
            }

            if (empty($loan_amount)) {
                throw new Exception("Loan amount cannot be zero or blank.");
            }

            if ($loan_amount < 4000) {
                throw new Exception("Loan amount cannot be lesser then Rs. 4000.");
            }

            if ($loan_amount > 150000) {
//            if ($loan_amount > 500000) {
                throw new Exception("Loan amount cannot be greater then Rs. 150000.");
            }

            if (empty($lead_status_id) || !in_array($lead_status_id, array(13))) {
                throw new Exception("Application has been moved to next steps.");
            }


            $tmp_disburse_status_arr = array();

            if ($loanDetails['loan_disbursement_trans_status_id'] == 1) {
                throw new Exception("Loan amount has been already disbursed.");
            }

            if (in_array($loanDetails['loan_disbursement_trans_status_id'], array(2, 3))) {

                $tmp_disburse_status_arr = icici_disburse_loan_status_api($lead_id, $request_array);

                return $tmp_disburse_status_arr; //write code for failed transaction in status for set status as 2;
            }

            $temp_pay_name_array = common_parse_full_name($beneName);

            if ($trans_type == 1) {

                $beneName_first_name = trim(substr($temp_pay_name_array['first_name'], 0, 15));

                $localTxnDtTime = date("YmdHis");

//                $beneAccNo = "00170000004";
//                $beneIFSC = "SBIN0003060";
//                $beneName = "Ram singh";

                $senderName = "NAMANFINLEASE";
                $sender_mobile = "9999999341";
                $retailerCode = "rcode";

                $payment_reference_no = "IMPS/ICICI/" . $loan_account_number . "/" . $beneName_first_name . "/" . $loan_amount;

                $passCode = $apiPassCode;
                $bcID = $apiBCID;

                $x_priority = "0100";

                $apiRequestJson = '{
                            "localTxnDtTime":"' . $localTxnDtTime . '",
                            "beneAccNo":"' . $beneAccNo . '",
                            "beneIFSC":"' . $beneIFSC . '",
                            "amount":"' . $loan_amount . '",
                            "tranRefNo":"' . $tranRefNo . '",
                            "paymentRef":"' . $payment_reference_no . '",
                            "senderName":"' . $senderName . '",
                            "mobile":"' . $sender_mobile . '",
                            "retailerCode":"' . $retailerCode . '",
                            "passCode":"' . $passCode . '",
                            "bcID":"' . $bcID . '"
                        }';
            } else if ($trans_type == 2) {
                $beneName_first_name = trim($temp_pay_name_array['first_name']);
//                $senderAcctNo = "000451000301";
//                $beneAccNo = "000405002777";
//                $beneName = "Mehul";
//                $beneIFSC = "SBIN0003060";
                $payment_reference_no = "NEFT/ICICI/" . $loan_account_number . "/" . $beneName_first_name . "/" . $loan_amount;
                $crpId = "PRACHICIB1";
                $crpUsr = "USER3";
                $aggrId = "AGGRID";
                $urn = $tranRefNo;
                $aggrName = "AGGRNAME";
                $txnType = (substr(strtoupper($beneIFSC), 0, 4) == "ICIC") ? "RGS" : "TPA";
                $WORKFLOW_REQD = "N";
                $x_priority = "0010";

                $apiRequestJson = '{
                    "tranRefNo":"' . $tranRefNo . '",
                    "amount":"' . $loan_amount . '",
                    "senderAcctNo":"' . $senderAcctNo . '",
                    "beneAccNo":"' . $beneAccNo . '",
                    "beneName": "' . $beneName . '",
                    "beneIFSC": "' . $beneIFSC . '",
                    "narration1": "' . $payment_reference_no . '",
                    "crpId": "' . $crpId . '",
                    "crpUsr": "' . $crpUsr . '",
                    "aggrId": "' . $aggrId . '",
                    "urn": "' . $urn . '",
                    "aggrName": "' . $aggrName . '",
                    "txnType": "' . $txnType . '",
                    "WORKFLOW_REQD": "' . $WORKFLOW_REQD . '"
                }';
            }

            $apiRequestJson = preg_replace("!\s+!", " ", $apiRequestJson);

            if ($debug == 1) {

                echo "<br/><br/> =======Request Plain======<br/><br/>" . $apiRequestJson;
            }

            $encryptedKey = "";
            $encryptedData = "";

            $middleware_return_arr = MiddlewareApiReqEncrypt($RPMiddleWareUrl, 'LWCOMMON', 'ICICENCDEC', $apiRequestJson, $lead_id);

            if ($debug == 1) {
                echo "<br/><br/> =======Middleware Encrypt Return======<br/><br/>";
                traceObject($middleware_return_arr);
            }

            if ($middleware_return_arr['status'] === 1) {
                if (!empty($middleware_return_arr['output_data'])) {
                    $middleware_return_arr = explode("|", $middleware_return_arr['output_data']);

                    if (!empty($middleware_return_arr[0]) && !empty($middleware_return_arr[1])) {
                        $encryptedKey = $middleware_return_arr[0];
                        $encryptedData = $middleware_return_arr[1];
                    } else {
                        throw new Exception("Middleware Error : missing encrypted data.");
                    }
                } else {
                    throw new Exception("Middleware Error : missing encrypted data..");
                }
            } else {
                throw new Exception("Middleware Error : " . $middleware_return_arr['errors']);
            }


            $requestId = date("YmdHis") . rand(1000, 9999);

            $esbApiRequestJson = '{
                                    "requestId": "' . $requestId . '",
                                    "service": "PaymentApi",
                                    "encryptedKey": "' . $encryptedKey . '",
                                    "oaepHashingAlgorithm": "NONE",
                                    "iv": "",
                                    "encryptedData": "' . $encryptedData . '",
                                    "clientInfo": "",
                                    "optionalParam": ""
                                }';

            $esbApiRequestJson = preg_replace("!\s+!", " ", $esbApiRequestJson);

            $apiHeaders = array(
                "apikey: $apiKey",
                "x-priority: $x_priority",
                "Content-Type:application/json",
                "Content-Length: " . strlen($esbApiRequestJson)
            );

            if ($debug == 1) {
                echo "<br/><br/> =======Request Header======<br/><br/>" . json_encode($apiHeaders);
                echo "<br/><br/> =======Request Plain======<br/><br/>" . $apiRequestJson;
                echo "<br/><br/> =======Request Encrypted======<br/><br/>" . $esbApiRequestJson;
            }

            if ($hardcode_response && $envSet == 'development') {
                $apiResponseJson = '{"ActCode":"0","Response":"Transaction Successful","BankRRN":"200701023783","BeneName":"BENE CUSTOMER NAME","success":true,"TransRefNo":"LWUAT20220107013948999"}';
            } else {
                $curl = curl_init($apiUrl);
                curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $apiHeaders);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $esbApiRequestJson);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($curl, CURLOPT_TIMEOUT, 60);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                $esbApiResponseJson = curl_exec($curl);

                if ($debug == 1) {
                    echo "<br/><br/> =======Response Encrypted ======<br/><br/>" . $esbApiResponseJson;
                }
            }

            $apiResponseDateTime = date("Y-m-d H:i:s");

            if (curl_errno($curl) && !$hardcode_response) {
                $curlError = "(" . curl_errno($curl) . ") " . curl_error($curl) . " to url " . $apiUrl;
                curl_close($curl);
                throw new RuntimeException("Something went wrong. Please try after sometime.");
            } else {

                if (isset($curl)) {
                    curl_close($curl);
                }

                if (!$hardcode_response) {

                    $tempRespnse = json_decode($esbApiResponseJson, true);

                    if ($debug == 1) {
                        echo "<br/><br/> =======Middleware Response Decode ======<br/><br/>";
                        traceObject($tempRespnse);
                    }

                    if (empty($tempRespnse['encryptedKey']) || empty($tempRespnse['encryptedData'])) {
                        $tmp_error = "Invalid response received from api.";
                        $tmp_error = str_replace("\\\\r\\\\n", " ", $tmp_error);
                        throw new ErrorException($tmp_error);
                    }

                    $tempRespnse = $tempRespnse['encryptedKey'] . "|" . $tempRespnse['encryptedData'];

                    $middleware_return_arr = MiddlewareApiResDecrypt($RPMiddleWareUrl, 'LWCOMMON', 'ICICENCDEC', $tempRespnse, $lead_id);

                    if ($debug == 1) {
                        echo "<br/><br/> =======Middleware Response Decrypted ======<br/><br/>";
                        traceObject($middleware_return_arr);
                    }

                    if ($middleware_return_arr['status'] === 1) {

                        if (!empty($middleware_return_arr['output_data'])) {
                            $apiResponseJson = $middleware_return_arr['output_data'];
                        }
                    } else {
                        throw new ErrorException("Unable to decrypt received response from api.");
                    }
                }

                $apiResponseJson = preg_replace("!\s+!", " ", $apiResponseJson);

                if ($debug == 1) {
                    echo "<br/><br/> =======Response Plain ======<br/><br/>" . $apiResponseJson;
                }

                $apiResponseData = json_decode($apiResponseJson, true);

                if (!empty($apiResponseData)) {

                    $apiResponseData = trim_data_array($apiResponseData);

                    if ($trans_type == 1 && isset($apiResponseData['ActCode']) && $apiResponseData['ActCode'] == 0 && isset($apiResponseData['success']) && $apiResponseData['success'] == 1) {
                        if (!empty($apiResponseData['BankRRN'])) {
                            $apiStatusId = 1;
                            $bank_reference_no = $apiResponseData['BankRRN'];
                        } else {
                            throw new ErrorException("BankRRN is not available in IMPS response.");
                        }
                    } else if ($trans_type == 2 && isset($apiResponseData['RESPONSE']) && $apiResponseData['RESPONSE'] == "SUCCESS") {
                        if (!empty($apiResponseData['URN']) && !empty($apiResponseData["UTR"])) {
                            $apiStatusId = 1;
                            $bank_reference_no = $apiResponseData['URN'] . " | " . $apiResponseData["UNIQUEID"] . " | " . $apiResponseData["UTR"];
                        } else {
                            throw new ErrorException("URN or UTR is not available in NEFT response.");
                        }
                    } else {

                        if (!empty($apiResponseData['ActCodeDesc'])) {
                            $tmp_error = $apiResponseData['ActCodeDesc'];
                        } else if (!empty($apiResponseData['MESSAGE'])) {
                            $tmp_error = $apiResponseData['MESSAGE'];
                        } else if (!empty($apiResponseData['Response'])) {
                            $tmp_error = $apiResponseData['Response'];
                        } else if (!empty($apiResponseData['description'])) {
                            $tmp_error = $apiResponseData['description'];
                        }

                        $tmp_error = !empty($tmp_error) ? $tmp_error : "Some error occurred. Please try again.";

                        throw new ErrorException($tmp_error);
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
            $retrigger_call = ($retrigger_call == 0) ? 0 : 0;
        } catch (Exception $e) {
            $apiStatusId = 4;
            $errorMessage = $e->getMessage();
        }

        $insertApiLog = array();
        $insertApiLog["disburse_user_id"] = $user_id;
        $insertApiLog["disburse_lan_no"] = $loan_account_number;
        $insertApiLog["disburse_bank_id"] = 1;
        $insertApiLog["disburse_method_id"] = 1;
        $insertApiLog["disburse_trans_refno"] = $tranRefNo;
        $insertApiLog["disburse_trans_type_id"] = $trans_type;
        $insertApiLog["disburse_lead_id"] = $lead_id;
        $insertApiLog["disburse_beneficiary_account_no"] = !empty($beneAccNo) ? $beneAccNo : "";
        $insertApiLog["disburse_beneficiary_ifsc_code"] = !empty($beneIFSC) ? $beneIFSC : "";
        $insertApiLog["disburse_beneficiary_name"] = !empty($beneName) ? $beneName : "";
        $insertApiLog["disburse_api_status_id"] = $apiStatusId;
        $insertApiLog["disburse_payment_reference_no"] = $payment_reference_no;
        $insertApiLog["disburse_bank_reference_no"] = $bank_reference_no;
        $insertApiLog["disburse_request"] = addslashes($apiRequestJson);
        $insertApiLog["disburse_response"] = addslashes($apiResponseJson);
        $insertApiLog["disburse_encrypted_request"] = addslashes($esbApiRequestJson);
        $insertApiLog["disburse_encrypted_response"] = addslashes($esbApiResponseJson);
        $insertApiLog["disburse_errors"] = ($apiStatusId == 3) ? addslashes($curlError) : addslashes($errorMessage);
        $insertApiLog["disburse_request_datetime"] = $apiRequestDateTime;
        $insertApiLog["disburse_response_datetime"] = !empty($apiResponseDateTime) ? $apiResponseDateTime : date("Y-m-d H:i:s");
        $return_log_id = $ci->IntegrationModel->insert("api_disburse_logs", $insertApiLog);

        if ($apiStatusId == 1) {
            $call_description = "ICICI DISBURSEMENT API(Success) <br> LAN : $loan_account_number <br> Customer Account No : $beneAccNo <br>  TransRefNo : $tranRefNo <br> BankRefNo : $bank_reference_no";
        } else {
            $call_description = "ICICI DISBURSEMENT API(Fail) <br> Customer Account No : $beneAccNo <br> TransRefNo : $tranRefNo <br> Error : " . $errorMessage;
        }

        if ($applicationDetails["lead_status_id"] > 0) {
            $lead_followup = [
                'lead_id' => $lead_id,
                'user_id' => $user_id,
                'status' => $applicationDetails["status"],
                'stage' => $applicationDetails["stage"],
                'lead_followup_status_id' => $applicationDetails["lead_status_id"],
                'remarks' => addslashes($call_description),
                'created_on' => date("Y-m-d H:i:s")
            ];
            $ci->IntegrationModel->insert("lead_followup", $lead_followup);
        }



        if (!empty($loan_id)) {
            $ci->IntegrationModel->update("loan", ["loan_id" => $loan_id], array("loan_disbursement_trans_log_id" => $return_log_id, "loan_disbursement_trans_status_id" => $apiStatusId, "loan_disbursement_trans_status_datetime" => date("Y-m-d H:i:s")));
        }

        if ($apiStatusId == 1 && !empty($disb_trans_id)) {
            $ci->IntegrationModel->update("lead_disbursement_trans_log", array("disb_trans_id" => $disb_trans_id), array("disb_trans_status_id" => 5, "disb_trans_updated_by" => $user_id, "disb_trans_updated_on" => date("Y-m-d H:i:s")));
        } else if (in_array($apiStatusId, array(2, 3)) && !empty($disb_trans_id)) {
            $ci->IntegrationModel->update("lead_disbursement_trans_log", array("disb_trans_id" => $disb_trans_id), array("disb_trans_status_id" => 2, "disb_trans_updated_by" => $user_id, "disb_trans_updated_on" => date("Y-m-d H:i:s")));
        }


        $returnResponseData = array();
        $returnResponseData['status'] = $apiStatusId;
        $returnResponseData['log_id'] = $return_log_id;
        $returnResponseData['payment_reference'] = "";

        $returnResponseData['payment_reference'] = $payment_reference_no;

        if (!empty($bank_reference_no)) {
            $returnResponseData['payment_reference'] .= "/" . $bank_reference_no;
        }

        $returnResponseData['bank_reference'] = $bank_reference_no;
        $returnResponseData['error_msg'] = !empty($errorMessage) ? "ICICI DISBURSEMENT API Error : " . $errorMessage : "";

        if ($debug == 1) {
            $returnResponseData['actual_error'] = $insertApiLog["disburse_errors"];
            $returnResponseData['raw_request'] = $apiRequestJson;
            $returnResponseData['raw_response'] = $apiResponseJson;
            $returnResponseData['parse_response'] = $apiResponseData;
        }

        return $returnResponseData;
    }

}

if (!function_exists('icici_disburse_loan_status_api')) {


    function icici_disburse_loan_status_api($lead_id, $request_array) {

        $api_disburse_bypass_mobile = array(); //"9560807913"

        $envSet = ENVIRONMENT;

        $ci = & get_instance();
        $ci->load->helper('integration/integration_config');
        $ci->load->model('Integration/Integration_Model', 'IntegrationModel');

        $apiStatusId = 0;
        $apiRequestJson = "";
        $apiResponseJson = "";
        $esbApiRequestJson = "";
        $esbApiResponseJson = "";
        $apiRequestDateTime = date("Y-m-d H:i:s");
        $apiResponseDateTime = "";
        $errorMessage = "";
        $curlError = "";
        $parseResponseData = "";
        $type = "ICICI_DISBURSEMENT_CALL";
        $sub_type = "TRANSACTION_STATUS";
        $product_id = 1;

        $hardcode_response = false;

        $debug = !empty($_REQUEST['lwtest']) ? 1 : 0;

        $applicationDetails = array();

        $master_payment_mode = array(1 => "IMPS", 2 => "NEFT");

        $bank_reference_no = "";

        $trans_type = "";
        $bank_id = 1; //ICICI
        $method_type_id = 2; //Status API
        $trans_type = !empty($request_array['payment_type_id']) ? $request_array['payment_type_id'] : "";
        $tranRefNo = !empty($request_array['tran_ref_no']) ? $request_array['tran_ref_no'] : "";
        $disb_trans_id = !empty($request_array['disb_trans_id']) ? $request_array['disb_trans_id'] : "";
        $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : "9999";

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

            $apiKey = $apiConfig["ApiKey"];

            $apiPassCode = $apiConfig["ApiPassCode"];

            $apiBCID = $apiConfig["ApiBCID"];

            $RPMiddleWareUrl = $apiConfig["RPMiddleWareUrl"];

            if (empty($lead_id)) {
                throw new Exception("Missing Lead Id.");
            }


            $appDataReturnArr = $ci->IntegrationModel->getLeadDetails($lead_id);

            if ($appDataReturnArr['status'] === 1) {
                $applicationDetails = $appDataReturnArr['app_data'];

                $lead_status_id = $applicationDetails["lead_status_id"];

                if (in_array($applicationDetails['mobile'], $api_disburse_bypass_mobile) && $envSet == 'development') {
//                    $hardcode_response = true;
                }
            }

            $camDataReturnArr = $ci->IntegrationModel->getLeadCAMDetails($lead_id);

            if ($camDataReturnArr['status'] === 1) {
                $camDetails = $camDataReturnArr['cam_data'];
//                traceObject($camDetails);
                if (empty($camDetails)) {
                    throw new Exception("CAM details cannot be empty.");
                }
            }

            $loanDataReturnArr = $ci->IntegrationModel->getLeadLoanDetails($lead_id);

            if ($loanDataReturnArr['status'] === 1) {
                $loanDetails = $loanDataReturnArr['loan_data'];
                $loan_id = !empty($loanDetails["loan_id"]) ? $loanDetails["loan_id"] : 0;
                $loan_account_number = !empty($loanDetails["loan_no"]) ? $loanDetails["loan_no"] : "";
                $loan_disbursement_trans_status_id = !empty($loanDetails["loan_disbursement_trans_status_id"]) ? $loanDetails["loan_disbursement_trans_status_id"] : 0;
                $loan_disbursement_trans_status_datetime = !empty($loanDetails["loan_disbursement_trans_status_datetime"]) ? $loanDetails["loan_disbursement_trans_status_datetime"] : "";
                $loan_disbursement_trans_log_id = !empty($loanDetails["loan_disbursement_trans_log_id"]) ? $loanDetails["loan_disbursement_trans_log_id"] : "";
            }



            $logDataReturnArr = $ci->IntegrationModel->getDisbursementTransDetails(1, $lead_id, $loan_disbursement_trans_log_id, $bank_id);

            if ($logDataReturnArr['status'] === 1) {
                $transLogDetails = $logDataReturnArr['log_data'];
                $trans_type = $transLogDetails['disburse_trans_type_id'];
                $tranRefNo = $transLogDetails['disburse_trans_refno'];
                $payment_reference_no = $transLogDetails['disburse_payment_reference_no'];
                $disburse_bank_reference_no = explode("|" . $transLogDetails['disburse_bank_reference_no']);
                $unique_reference_no = $disburse_bank_reference_no[0];
                $beneAccNo = !empty($transLogDetails["disburse_beneficiary_account_no"]) ? $transLogDetails["disburse_beneficiary_account_no"] : "";
                $beneIFSC = !empty($transLogDetails["disburse_beneficiary_ifsc_code"]) ? $transLogDetails["disburse_beneficiary_ifsc_code"] : "";
                $beneName = !empty($transLogDetails["disburse_beneficiary_name"]) ? $transLogDetails["disburse_beneficiary_name"] : "";
            } else {
                throw new Exception("Invalid Request for check status");
            }


            if (empty($trans_type)) {
                throw new Exception("Missing payment mode type.");
            }

            if (empty($master_payment_mode[$trans_type])) {
                throw new Exception("Invalid payment mode type.");
            }

            if (empty($tranRefNo)) {
                throw new Exception("Missing tranRefNo.");
            }

            if ($trans_type == 2 && empty($unique_reference_no)) {
                throw new Exception("Missing urn for NEFT.");
            }

            if ($loan_disbursement_trans_status_id == 1) {
                throw new Exception("Loan amount has been already disbursed.");
            }


            if (empty($lead_status_id) || !in_array($lead_status_id, array(13))) {
                throw new Exception("Application has been moved to next steps.");
            }

            if ($trans_type == 1) {

                $passCode = $apiPassCode;
                $bcID = $apiBCID;

                $x_priority = "0100";

                $trans_date = date("m/d/Y", strtotime($loan_disbursement_trans_status_datetime));

                $apiRequestJson = '{
                                    "transRefNo": "' . $tranRefNo . '",
                                    "passCode": "' . $passCode . '",
                                    "bcID": "' . $bcID . '",
                                    "recon360":"N",
                                    "date": "' . $trans_date . '"
                                }';
            } else if ($trans_type == 2) {

                $crpId = "PRACHICIB1";
                $crpUsr = "USER3";
                $aggrId = "AGGRID";
                $urn = $unique_reference_no;
                $x_priority = "0010";

                $apiRequestJson = '{
                    "AGGRID": "' . $aggrId . '",
                    "CORPID": "' . $crpId . '",
                    "USERID": "' . $crpUsr . '",
                    "URN": "' . $urn . '",
                    "UNIQUEID":"' . $tranRefNo . '"
                }';
            }


            $apiRequestJson = preg_replace("!\s+!", " ", $apiRequestJson);

            if ($debug == 1) {
//                echo "<br/><br/> ====== = Request Header======<br/><br/>" . json_encode($apiHeaders);
                echo "<br/><br/> ======= Request Plain======<br/><br/>" . $apiRequestJson;
//                echo "<br/><br/> ====== = Request Encrypted======<br/><br/>" . $esbApiRequestJson;
            }

            $encryptedKey = "";
            $encryptedData = "";

            $middleware_return_arr = MiddlewareApiReqEncrypt($RPMiddleWareUrl, 'LWCOMMON', 'ICICENCDEC', $apiRequestJson, $lead_id);

            if ($debug == 1) {
                echo "<br/><br/> ====== = Middleware Encrypt Return======<br/><br/>";
                traceObject($middleware_return_arr);
            }

//
            if ($middleware_return_arr['status'] === 1) {
                if (!empty($middleware_return_arr['output_data'])) {
                    $middleware_return_arr = explode("|", $middleware_return_arr['output_data']);

                    if (!empty($middleware_return_arr[0]) && !empty($middleware_return_arr[1])) {
                        $encryptedKey = $middleware_return_arr[0];
                        $encryptedData = $middleware_return_arr[1];
                    } else {
                        throw new Exception("Middleware Error : missing encrypted data.");
                    }
                } else {
                    throw new Exception("Middleware Error : missing encrypted data..");
                }
            } else {
                throw new Exception("Middleware Error : " . $middleware_return_arr['errors']);
            }


            $requestId = date("YmdHis") . rand(1000, 9999);

            $esbApiRequestJson = '{
                                    "requestId": "' . $requestId . '",
                                    "service": "PaymentApi",
                                    "encryptedKey": "' . $encryptedKey . '",
                                    "oaepHashingAlgorithm": "NONE",
                                    "iv": "",
                                    "encryptedData": "' . $encryptedData . '",
                                    "clientInfo": "",
                                    "optionalParam": ""
                                }';

            $esbApiRequestJson = preg_replace("!\s+!", " ", $esbApiRequestJson);

            $apiHeaders = array(
                "apikey: $apiKey",
                "x-priority: $x_priority",
                "Content-Type:application/json",
                "Content-Length: " . strlen($esbApiRequestJson)
            );

            if ($debug == 1) {
                echo "<br/><br/> ======= Request Header======<br/><br/>" . json_encode($apiHeaders);
                echo "<br/><br/> ======= Request Plain======<br/><br/>" . $apiRequestJson;
                echo "<br/><br/> ======= Request Encrypted======<br/><br/>" . $esbApiRequestJson;
            }

            if ($hardcode_response && $envSet == 'development') {
                $apiResponseJson = '{"disburseLoanAmountRes":{"resHdr":{"consumerContext":{"applicationId":"RUP"},"serviceContext":{"uniqueMsgId":"9999202102111706229434","reqMsgDateTime":"2021-02-11T17:06:22.000000","serviceName":"GetLoanDisburseDtls","serviceVersion":"1"},"providerContext":{"providerId":"CBS","responseMsgDateTime":"2021-02-11T11:36:27.640"},"responseStatus":{"status":"0","esbResDateTime":"2021-02-11 17:06:27.619611"}},"body":{"status":"1102220210000104:Account disbursed sucessfully","transactionId":"UJ3070"}}}';
            } else {
                $curl = curl_init($apiUrl);
                curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $apiHeaders);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $esbApiRequestJson);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($curl, CURLOPT_TIMEOUT, 60);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                $esbApiResponseJson = curl_exec($curl);

                if ($debug == 1) {
                    echo "<br/><br/> ====== = Response Encrypted ======<br/><br/>" . $esbApiResponseJson;
                }
            }

            $apiResponseDateTime = date("Y-m-d H:i:s");

            if (curl_errno($curl) && !$hardcode_response) {
                $curlError = "(" . curl_errno($curl) . ") " . curl_error($curl) . " to url " . $apiUrl;
                curl_close($curl);
                throw new RuntimeException("Something went wrong. Please try after sometime.");
            } else {

                if (isset($curl)) {
                    curl_close($curl);
                }

                if (!$hardcode_response) {

                    $tempRespnse = json_decode($esbApiResponseJson, true);

                    if ($debug == 1) {
                        echo "<br/><br/> ====== = Response Decode Encrypted ======<br/><br/>";
                        traceObject($tempRespnse);
                    }

                    if (empty($tempRespnse['encryptedKey']) || empty($tempRespnse['encryptedData'])) {
                        $tmp_error = "Invalid response received from api.";
                        $tmp_error = str_replace("\\\\r\\\\n", " ", $tmp_error);
                        throw new ErrorException($tmp_error);
                    }

                    $tempRespnse = $tempRespnse['encryptedKey'] . "|" . $tempRespnse['encryptedData'];

                    $middleware_return_arr = MiddlewareApiResDecrypt($RPMiddleWareUrl, 'LWCOMMON', 'ICICENCDEC', $tempRespnse, $lead_id);

                    if ($debug == 1) {
                        echo "<br/><br/> ====== = Response Middleware Decrypted ======<br/><br/>";
                        traceObject($middleware_return_arr);
                    }

                    if ($middleware_return_arr['status'] === 1) {

                        if (!empty($middleware_return_arr['output_data'])) {
                            $apiResponseJson = $middleware_return_arr['output_data'];
                        }
                    } else {
                        throw new ErrorException("Unable to decrypt received response from api.");
                    }
                }

                $apiResponseJson = preg_replace("!\s+!", " ", $apiResponseJson);

                if ($debug == 1) {
                    echo "<br/><br/> ====== = Response Plain ======<br/><br/>" . $apiResponseJson;
                }

                $apiResponseData = json_decode($apiResponseJson, true);

                if (!empty($apiResponseData)) {

                    $apiResponseData = trim_data_array($apiResponseData);

                    if ($trans_type == 1 && isset($apiResponseData['ImpsResponse']['ActCode']) && $apiResponseData['ImpsResponse']['ActCode'] == 0 && trim(strtolower($apiResponseData['ImpsResponse']['Response'])) == 'transaction successful') {

                        if (!empty($apiResponseData['ImpsResponse']['BankRRN'])) {
                            $apiStatusId = 1;
                            $bank_reference_no = $apiResponseData['ImpsResponse']['BankRRN'];
                        } else {
                            throw new ErrorException("BankRRN is not available in api response.");
                        }
                    } else if ($trans_type == 2 && isset($apiResponseData['STATUS']) && trim(strtolower($apiResponseData['STATUS'])) == "success") {//failure needs to be handled
                        if (!empty($apiResponseData['URN']) && !empty($apiResponseData["UTRNUMBER"])) {
                            $apiStatusId = 1;
                            $bank_reference_no = $apiResponseData['URN'] . " | " . $apiResponseData["UNIQUEID"] . " | " . $apiResponseData["UTRNUMBER"];
                        } else {
                            throw new ErrorException("URN or UTRNUMBER is not available in api response.");
                        }
                    } else {

                        if (!empty($apiResponseData['ActCodeDesc'])) {
                            $tmp_error = $apiResponseData['ActCodeDesc'];
                        } else if (!empty($apiResponseData['MESSAGE'])) {
                            $tmp_error = $apiResponseData['MESSAGE'];
                        } else if (!empty($apiResponseData['RESPONSE'])) {
                            $tmp_error = $apiResponseData['RESPONSE'];
                        } else if (!empty($apiResponseData['Response'])) {
                            $tmp_error = $apiResponseData['Response'];
                        } else if (isset($apiResponseData['ImpsResponse']['Response']) && !empty($apiResponseData['ImpsResponse']['Response'])) {
                            $tmp_error = $apiResponseData['ImpsResponse']['Response'];
                        }

                        $tmp_error = !empty($tmp_error) ? $tmp_error : "Some error occurred. Please try again.";

                        throw new ErrorException($tmp_error);
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
            $retrigger_call = ($retrigger_call == 0) ? 0 : 0;
        } catch (Exception $e) {
            $apiStatusId = 4;
            $errorMessage = $e->getMessage();
        }

        $insertApiLog = array();
        $insertApiLog["disburse_user_id"] = $user_id;
        $insertApiLog["disburse_lan_no"] = $loan_account_number;
        $insertApiLog["disburse_bank_id"] = $bank_id;
        $insertApiLog["disburse_method_id"] = $method_type_id;
        $insertApiLog["disburse_trans_refno"] = $tranRefNo;
        $insertApiLog["disburse_trans_type_id"] = $trans_type;
        $insertApiLog["disburse_lead_id"] = $lead_id;
        $insertApiLog["disburse_beneficiary_account_no"] = !empty($beneAccNo) ? $beneAccNo : "";
        $insertApiLog["disburse_beneficiary_ifsc_code"] = !empty($beneIFSC) ? $beneIFSC : "";
        $insertApiLog["disburse_beneficiary_name"] = !empty($beneName) ? $beneName : "";
        $insertApiLog["disburse_api_status_id"] = $apiStatusId;
        $insertApiLog["disburse_payment_reference_no"] = $payment_reference_no;
        $insertApiLog["disburse_bank_reference_no"] = $bank_reference_no;
        $insertApiLog["disburse_request"] = addslashes($apiRequestJson);
        $insertApiLog["disburse_response"] = addslashes($apiResponseJson);
        $insertApiLog["disburse_encrypted_request"] = addslashes($esbApiRequestJson);
        $insertApiLog["disburse_encrypted_response"] = addslashes($esbApiResponseJson);
        $insertApiLog["disburse_errors"] = ($apiStatusId == 3) ? addslashes($curlError) : addslashes($errorMessage);
        $insertApiLog["disburse_request_datetime"] = $apiRequestDateTime;
        $insertApiLog["disburse_response_datetime"] = !empty($apiResponseDateTime) ? $apiResponseDateTime : date("Y-m-d H:i:s");
        $return_log_id = $ci->IntegrationModel->insert("api_disburse_logs", $insertApiLog);

        if ($apiStatusId == 1) {
            $call_description = "ICICI DISBURSEMENT STATUS API(Success) <br> LAN : $loan_account_number <br> Customer Account No : $beneAccNo <br>  TransRefNo : $tranRefNo <br> BankRefNo : $bank_reference_no";
        } else {
            $call_description = "ICICI DISBURSEMENT STATUS API(Fail) <br> Customer Account No : $beneAccNo <br> TransRefNo : $tranRefNo <br> Error : " . $errorMessage;
        }

        if ($applicationDetails["lead_status_id"] > 0) {
            $lead_followup = [
                'lead_id' => $lead_id,
                'user_id' => $user_id,
                'status' => $applicationDetails["status"],
                'stage' => $applicationDetails["stage"],
                'lead_followup_status_id' => $applicationDetails["lead_status_id"],
                'remarks' => addslashes($call_description),
                'created_on' => date("Y-m-d H:i:s")
            ];

            $ci->IntegrationModel->insert("lead_followup", $lead_followup);
        }

        if (!empty($loan_id) && $apiStatusId == 1) {
            $ci->IntegrationModel->update("loan", ["loan_id" => $loan_id], array("loan_disbursement_trans_log_id" => $return_log_id, "loan_disbursement_trans_status_id" => $apiStatusId, "loan_disbursement_trans_status_datetime" => date("Y-m-d H:i:s")));
        }

        if ($apiStatusId == 1 && !empty($disb_trans_id)) {
            $ci->IntegrationModel->update("lead_disbursement_trans_log", array("disb_trans_id" => $disb_trans_id), array("disb_trans_status_id" => 5, "disb_trans_updated_by" => $user_id, "disb_trans_updated_on" => date("Y-m-d H:i:s")));
        } else if (in_array($apiStatusId, array(2, 3)) && !empty($disb_trans_id)) {
            $ci->IntegrationModel->update("lead_disbursement_trans_log", array("disb_trans_id" => $disb_trans_id), array("disb_trans_status_id" => 2, "disb_trans_updated_by" => $user_id, "disb_trans_updated_on" => date("Y-m-d H:i:s")));
        }

        $returnResponseData = array();
        $returnResponseData['status'] = $apiStatusId;
        $returnResponseData['payment_reference'] = $payment_reference_no;
        if (!empty($bank_reference_no)) {
            $returnResponseData['payment_reference'] .= "/" . $bank_reference_no;
        }
        $returnResponseData['bank_reference'] = $bank_reference_no;
        $returnResponseData['log_id'] = $return_log_id;
        $returnResponseData['error_msg'] = !empty($errorMessage) ? "ICICI Status Error : " . $errorMessage : "";

        if ($debug == 1) {
            $returnResponseData['actual_error'] = $insertApiLog["disburse_errors"];
            $returnResponseData['raw_request'] = $apiRequestJson;
            $returnResponseData['raw_response'] = $apiResponseJson;
            $returnResponseData['parse_response'] = $apiResponseData;
        }

        return $returnResponseData;
    }

}

