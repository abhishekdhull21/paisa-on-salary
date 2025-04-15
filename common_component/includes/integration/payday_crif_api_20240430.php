
<?php

function bureau_api_call($method_name = "", $lead_id = 0, $request_array = array()) {
    echo "test";
    common_log_writer(2, "CRIF API started | $lead_id");

    $responseArray = array("status" => 0, "errors" => "");

    $opertion_array = array(
        "GET_BUREAU_SCORE" => 1,
    );

    $method_id = $opertion_array[$method_name];

    if ($method_id == 1) {
        $responseArray = crif_bureau_api_call($lead_id, $request_array);
    } else {
        $responseArray["errors"] = "invalid opertation called";
    }

    return $responseArray;
}
function crif_bureau_api_call($lead_id = 0, $request_array = array()) {

    ini_set('max_execution_time', 3600);
    ini_set("memory_limit", "1024M");

    common_log_writer(2, "crif_inquiry_agent_request started | $lead_id");

    require_once (COMP_PATH . '/includes/integration/integration_config.php');

    $response_array = array("status" => 0, "errors" => "", "cibil_score" => "");

    //INIT VAR(s)

    $envSet = COMP_ENVIRONMENT;
    $customer_id = "";
    $lead_status_id = "";
    $cibil_score = "";
    $cibil_html = "";

    $apiStatusId = 0;
    $apiRequestXml = "";
    $apiResponseXml = "";
    $apiResponseJson = "";
    $apiRequestDateTime = date("Y-m-d H:i:s");
    $apiResponseDateTime = "";
    $errorMessage = "";
    $curlError = "";

    $type = "CRIF_CALL";
    $sub_type = "REQUEST_INIT";

    $hardcode_response = false;

//    if ($envSet == 'development') {
//        $hardcode_response = true;
//    }

    //$debug = !empty($_REQUEST['lwtest']) ? 1 : 0;
    $debug = 1;

    $applicationDetails = array();

    $user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : "";

    $leadModelObj = new LeadModel();

    $REQ_VOL_TYP = "C04";
    $REQ_ACTN_TYP = "AT01";
    $AUTH_FLG = "Y";
    $AUTH_TITLE = "USER";
    $RES_FRMT = "XML/HTML";
    $MEMBER_PRE_OVERRIDE = "N";
    $RES_FRMT_EMBD = "Y";
    $LOS_NAME = "INHOUSE";
    $LOS_VENDER = "";
    $LOS_VERSION = "";
    $REQ_SERVICES_TYPE = "CIR";

    $INQUIRY_UNIQUE_REF_NO = "7869";
    $CREDT_RPT_ID = "";
 
    $CREDT_RPT_TRN_ID = "";
    $CREDT_INQ_PURPS_TYPE = "CP06";
    $CREDT_INQ_PURPS_TYP_DESC = "A12";
    $CREDIT_INQUIRY_STAGE = "COLLECTION";
   
    $BRANCH_ID = "";
    $CREDIT_RPT_TRN_DT_TM = "12:00";
    $CLIENT_CONTRIBUTOR_ID = "PRB0000003";
    $APPLICATION_ID = "8092017181742";
    $ACNT_OPEN_DT = "";
    $LOAN_AMT = "500000";
    $LTV  = "12.3";
    $TERM = "234";
    $LOAN_TYPE =  "A01";
    $LOAN_TYPE_DESC = "";
    
    $APPLICATION_DATETIME = "";
    $LOAN_AMOUNT = "";
    $TEST_FLG = "";
    $NAME1 = $NAME2 = $NAME3 = $NAME4 = "";
    $DOB_DT = "";
    $DOB_DATE = $AGE_AS_ON = $AGE = "";

    

    $ID_TYPE_PAN = "N";
    $ID_TYPE_DL = "N";
    $ID_TYPE_PP = "N";
    $PAN_VALUE = "";
    $PP_VALUE = "";
    $DL_VALUE = "";

    $ADDR_TYPE_PERMANENT = "N";
    $PER_ADDR_VALUE = "";
    $PER_ADDR_CITY = "";
    $PER_ADDR_STATE = "";
    $PER_ADDR_PINCODE = "";

    $ADDR_TYPE_RESIDENCE = "N";
    $RES_ADDR_CITY = "";
    $RES_ADDR_STATE = "";
    $RES_ADDR_PINCODE = "";
    $RES_ADDR_VALUE = "";

    //PHONES						
    $PHONE_TYPE_MOBILE = "N";
    $MOBILE_VALUE = "";

    $EMAIL = "";
    $GENDER = "";
    
    $ACCOUNT_NUMBER = "";

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

       

        // if (empty($NAME1) || $ID_TYPE_PAN != "Y" || $PHONE_TYPE_MOBILE != "Y" || empty($GENDER) || empty($RES_ADDR_VALUE) || empty($RES_ADDR_CITY) || empty($RES_ADDR_STATE) || empty($RES_ADDR_PINCODE)) {
        //     throw new Exception("Missing mandatory fields to call bureau api.");
        // }

        

        $apiRequestJson = '{
    "REQUEST-FILE": {
        "HEADER-SEGMENT": {
            "PRODUCT-TYPE": "CIR PRO V2",
            "PRODUCT-VER": "2.0",
            "USER-ID":"kasar_cpu_prd@kasarcredit.com",
            "USER-PWD":"E2ACF08723F5EBBCC6AE42383D1BEA844EEFA571",
            "REQ-MBR": "NBF0005465",
            "INQ-DT-TM": "15-05-2018 11:1",
            "REQ-VOL-TYPE": "C04",
            "REQ-ACTN-TYPE": "AT01",
            "AUTH-FLG": "Y",
           "AUTH-TITLE": "USER",
            "RES-FRMT": "PDF",
            "MEMBER-PREF-OVERRIDE": "N",
            "RES-FRMT-EMBD": "N",
            "LOS-NAME": "INHOUSE",
            "LOS-VENDOR": "",
            "LOS-VERSION": "",
            "REQ-SERVICES-TYPE": "CIR"
        },
        "INQUIRY": {
            "APPLICANT-SEGMENT": {
                "APPLICANT-ID": "117872334422",
                "FIRST-NAME": "Rohit",
                "MIDDLE-NAME": "kumar",
                "LAST-NAME": "jain",
                "DOB": {
                    "DOB-DT": "05-07-2001",
                    "AGE": "",
                    "AGE-AS-ON": ""
                },
                "RELATIONS": [
              {
                             "TYPE": "K01",
                              "VALUE": "Rohit Kumar"
                                                                        
                }
                                           ],
                "IDS": [
                    {
                        "TYPE": "ID07",
                        "VALUE": "BOSPJ9116H"
                    },
                    {
                    "TYPE": "ID05",
                    "VALUE": "BMMODU67703202"
                    }
                ],
                "ADDRESSES": [
                    {
                        "TYPE": "D05",
                        "ADDRESS-TEXT": "K-83 STREET 26 SEELAMPUR BHAGAT SINGH CHOWK  ",
                        "CITY": "NEW DELHI",
                        "STATE": "DL",
                        "LOCALITY":"NORTH EAST DELHI",
                             "PIN": "110053",
                        "COUNTRY": "INDIA"
                    }
                ],

                "PHONES": [
                    {
                        "TYPE": "P04",
                        "VALUE": "9717882592"
                    }
                ],
                "EMAILS": [
                    {
                        "EMAIL": "CHHABRABHAVISH@GMAIL.COM"
                    }
                ],
                "ACCOUNT-NUMBER": "9146411145"
            },
            "APPLICATION-SEGMENT": {
                "INQUIRY-UNIQUE-REF-NO": "7869",
                "CREDIT-RPT-ID": "",
                "CREDIT-RPT-TRN-DT-TM": "12:00",
                "CREDIT-INQ-PURPS-TYPE": "CP06",
                "CREDIT-INQUIRY-STAGE": "COLLECTION",
                "CLIENT-CONTRIBUTOR-ID": "PRB0000003",
                "BRANCH-ID": "",
                "APPLICATION-ID": "8092017181742",
                "ACNT-OPEN-DT": "",
                "LOAN-AMT": "500000",
                "LTV": "12.3",
                "TERM": "234",
                "LOAN-TYPE": "A01",
                "LOAN-TYPE-DESC": ""
            }
        }
    }
}
';
        
        $apiRequestJson = preg_replace("!\s+!", " ", $apiRequestJson);

            if ($debug == 1) {
                echo "<br/><br/>=======Request JSON=========<br/><br/>";
                echo $apiRequestJson;
            }
 

        $apiHeaders = array(
            "content-type: application/json",
            "userId:  kasar_cpu_prd@kasarcredit.com",
            "password: E2ACF08723F5EBBCC6AE42383D1BEA844EEFA571",
            "mbrid: NBF0005465",
            "productType: CIR PRO V2",
            "productVersion: 2.0",
            "reqVolType: CIR"
        );
        
       if ($debug == 1) {
                echo "<br/><br/>=======Request Header=========<br/><br/>";
                echo json_encode($apiHeaders);
            }

            $apiRequestDateTime = date("Y-m-d H:i:s");

        
        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $apiHeaders);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $apiRequestJson);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $apiResponseJson = curl_exec($curl);
        $apiResponseJson = preg_replace("!\s+!", " ", $apiResponseJson);
        $apiResponseDateTime = date("Y-m-d H:i:s");
        $apiResponseData = json_decode($apiResponseJson, true);

       // $cibil_details = $leadModelObj->getCibilData($lead_id);
        // if($cibil_details['status']==1) {
        //     $apiResponseXml = $cibil_details['cibil_data'][0]['response'];
        // }

      
       if (!$hardcode_response && curl_errno($curl)) { // CURL Error
            $curlError = curl_error($curl);
            curl_close($curl);
            throw new RuntimeException("Something went wrong. Please try after sometimes.");
        } else {

            if (isset($curl)) {
                curl_close($curl);
            }
   
        print_r($apiResponseData); die;

            if (!empty($apiResponseXml)) {

                $tempApiResponseXml = $apiResponseXml;

                $cibil_html = common_extract_value_from_xml('<CONTENT><![CDATA[', ']]></CONTENT>', $tempApiResponseXml);
                $apiResponseXml = str_replace($cibil_html, '', $apiResponseXml);
                $tempApiResponseXml = str_replace($cibil_html, '', $tempApiResponseXml);

                if (strpos(trim($tempApiResponseXml), '<STATUS>') !== false) {
                    $temp_xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $tempApiResponseXml);
                    $temp_xml = @simplexml_load_string($temp_xml);
                    $temp_json = @json_encode($temp_xml);
                    $temp_array = @json_decode($temp_json, true);
                    $apiResponseJson = json_encode($temp_xml);
                    if ($debug) {
                        echo "<br/><br/><br/><br/><br/><br/>=======Response Array=========<br/><br/>";
                        print_r($temp_array);
                    }


                    $header_response_array = !empty($temp_array['INDV-REPORTS']['INDV-REPORT']['HEADER']) ? $temp_array['INDV-REPORTS']['INDV-REPORT']['HEADER'] : "";
                    $status_response_array = !empty($temp_array['INDV-REPORTS']['INDV-REPORT']['STATUS-DETAILS']) ? $temp_array['INDV-REPORTS']['INDV-REPORT']['STATUS-DETAILS'] : "";
                    $accounts_summary_response_array = !empty($temp_array['INDV-REPORTS']['INDV-REPORT']['ACCOUNTS-SUMMARY']) ? $temp_array['INDV-REPORTS']['INDV-REPORT']['ACCOUNTS-SUMMARY'] : "";
                    $alerts_response_array = !empty($temp_array['INDV-REPORTS']['INDV-REPORT']['ALERTS']) ? $temp_array['INDV-REPORTS']['INDV-REPORT']['ALERTS'] : "";
                    $scores_response_array = !empty($temp_array['INDV-REPORTS']['INDV-REPORT']['SCORES']) ? $temp_array['INDV-REPORTS']['INDV-REPORT']['SCORES'] : "";
                    $inquiry_history_response_array = !empty($temp_array['INDV-REPORTS']['INDV-REPORT']['INQUIRY-HISTORY']) ? $temp_array['INDV-REPORTS']['INDV-REPORT']['INQUIRY-HISTORY'] : "";
                    $responses_response_array = !empty($temp_array['INDV-REPORTS']['INDV-REPORT']['RESPONSES']) ? $temp_array['INDV-REPORTS']['INDV-REPORT']['RESPONSES'] : "";
                    $indv_responses_response_array = !empty($temp_array['INDV-REPORTS']['INDV-REPORT']['INDV-RESPONSES']) ? $temp_array['INDV-REPORTS']['INDV-REPORT']['INDV-RESPONSES'] : "";
                    $grp_responses_response_array = !empty($temp_array['INDV-REPORTS']['INDV-REPORT']['GRP-RESPONSES']) ? $temp_array['INDV-REPORTS']['INDV-REPORT']['GRP-RESPONSES'] : "";

                    if (isset($status_response_array['STATUS'][0]['OPTION']) && !empty($status_response_array['STATUS'][0]['OPTION']) && $status_response_array['STATUS'][0]['OPTION'] == "CNS-SCORE" && !empty($status_response_array['STATUS'][0]['OPTION-STATUS']) && $status_response_array['STATUS'][0]['OPTION-STATUS'] == "SUCCESS") {
                        $apiStatusId = 1;
                        if (isset($scores_response_array['SCORE']['SCORE-TYPE']) && !empty($scores_response_array['SCORE']['SCORE-TYPE']) && trim(strtoupper($scores_response_array['SCORE']['SCORE-TYPE'])) == "PERFORM CONSUMER 2.0") {

                            $cibil_score = $scores_response_array['SCORE']['SCORE-VALUE'];
                        }
                    } else {
                        $tmp_error_msg = "NO SCORE FOUND IN BUREAU. PLEASE CHECK THE REPORT.";

//                        if (!empty($status_response_array['STATUS'][2]['OPTION'] == 'CNS_INDV')) {
//                            $tmp_error_msg = !empty($status_response_array['STATUS'][2]['OPTION-STATUS'])?$status_response_array['STATUS'][2]['OPTION-STATUS']:"Some error occurred. Please check after sometime..";
//                        }

                        throw new ErrorException($tmp_error_msg);
                    }
                } else {
                    throw new ErrorException("Please check raw response for error details");
                }
            } else {
                throw new ErrorException("Empty response from CRIF API");
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

    //Preparing response array
    $response_array['status'] = $apiStatusId;
    $response_array['cibil_score'] = $cibil_score;
    $response_array['cibil_html'] = $cibil_html;
    $response_array['errors'] = $errorMessage;
    $response_array['request_xml'] = $apiRequestXml;
    $response_array['response_xml'] = $apiResponseXml;
    $response_array['response_json'] = $apiResponseJson;

    if (!empty($lead_id)) {
        if ($apiStatusId == 1) {
            $lead_remarks = "CRIF API CALL(Success) | Score : " . $cibil_score;
        } else {
            $lead_remarks = "CRIF API CALL(Failed) | Error : " . $errorMessage;
        }
        $leadModelObj->insertApplicationLog($lead_id, $lead_status_id, $lead_remarks);
    }

    if ($apiStatusId == 1) {

        $cibil_data = [
            'cibil_bureau_type' => 2, //CRIF
            'lead_id' => $lead_id,
            'customer_id' => $customer_id,
            'cibil_pancard' => $PAN_VALUE,
            'memberCode' => $apiMemberId,
            'cibilScore' => $cibil_score,
            'cibil_file' => addslashes($cibil_html),
            'applicationId' => !empty($header_response_array['REPORT-ID']) ? $header_response_array['REPORT-ID'] : "",
            'cibil_created_by' => !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : 0,
            'created_at' => date("Y-m-d H:i:s"),
            'cibil_pancard' => $PAN_VALUE,
        ];

        $leadModelObj->insertTable('tbl_cibil', $cibil_data);
    }

    if ($apiStatusId == 1 || $apiStatusId == 2) {
        $cibil_log = [
            'cibil_bureau_type' => 2, //CRIF
            'lead_id' => $lead_id,
            'customer_id' => $customer_id,
            'customer_name' => implode(" ", array($NAME1, $NAME2, $NAME3)),
            'customer_mobile' => $MOBILE_VALUE,
            'pancard' => $PAN_VALUE,
            'loan_amount' => $LOAN_AMOUNT,
            'dob' => $DOB_DATE,
            'gender' => $gender,
            'customer_email' => $EMAIL,
            'city' => $RES_ADDR_CITY,
            'state_id' => $res_state_id,
            'pincode' => $RES_ADDR_PINCODE,
            'api1_request' => addslashes($apiRequestXml),
            'api1_response' => addslashes($apiResponseXml),
//            'api2_response' => addslashes($apiResponseJson),
            'memberCode' => $apiMemberId,
            'cibilScore' => $cibil_score,
            'cibil_file' => addslashes($cibil_html),
//            'applicationId' => !empty($header_response_array['REPORT-ID']) ? $header_response_array['REPORT-ID'] : "",
        ];
        $leadModelObj->insertTable('tbl_cibil_log', $cibil_log);
    }

    if ($apiStatusId == 1 && isset($cibil_score)) {
        $leadModelObj->updateLeadTable($lead_id, ['check_cibil_status' => 1, 'cibil' => $cibil_score]);
    }

    return $response_array;
}



?>
