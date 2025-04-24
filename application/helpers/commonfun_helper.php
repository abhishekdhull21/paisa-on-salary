<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('traceObjectSelf')) {

    function traceObjectSelf($object_passed, $die = false) {
        if (in_array($_SERVER["REMOTE_ADDR"], array("183.83.210.174"))) {
            traceObject($object_passed);
            if ($die) {
                die;
            }
        }
    }

}

if (!function_exists('traceObject')) {

    function traceObject(&$expression) {
        echo("<pre>");
        print_r($expression);
        echo("</pre>");
    }

}

if (!function_exists('trim_data_array')) {


    function trim_data_array($inputstring) {
        if (!is_array($inputstring)) {
            $inputstring = trim($inputstring);
            $inputstring = addslashes($inputstring);
            $inputstring = preg_replace("!\s+!", " ", $inputstring);
            $inputstring = str_replace("Ã¢â‚¬â€œ", " ", $inputstring);
            $inputstring = str_replace("ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Å“", " ", $inputstring);
            $inputstring = preg_replace("!\s+!", " ", $inputstring);
            return $inputstring;
        }
        return array_map('trim_data_array', $inputstring);
    }

}
if (!function_exists('common_parse_full_name')) {

    function common_parse_full_name($full_name = "") {
        $first_name = $middle_name = $last_name = "";

        if (!empty($full_name)) {
            $full_name = preg_replace("!\s+!", " ", $full_name);

            $name_array = explode(" ", $full_name);

            $first_name = $name_array[0];

            for ($i = 1; $i < (count($name_array) - 1); $i++) {
                $middle_name .= " " . $name_array[$i];
            }

            $middle_name = trim($middle_name);
            $last_name = (count($name_array) != 1 && isset($name_array[count($name_array) - 1])) ? $name_array[count($name_array) - 1] : "";
        }
        return array("first_name" => $first_name, "middle_name" => $middle_name, "last_name" => $last_name);
    }

}

if(!function_exists('inscriptionNumber')) {
    function inscriptionNumber($ccNum){
      return str_replace(range(0,9), "*",substr($ccNum, 0, -4)). substr($ccNum,-4);
    }
}

if (!function_exists('ConvertXmlToJson')) {

    function ConvertXmlToJson($xmlString) {
        $return_val = true;
        $error_msg = "";
        $jsonString = "";
        try {

            $xmlString = str_replace(array("\n", "\r", "\t"), '', $xmlString);
            if (empty($xmlString)) {
                throw new Exception("XML not in correct format.#2");
            }

            $xmlString = simplexml_load_string($xmlString);

            if ($xmlString === false) {
                throw new Exception("XML not in correct format.#3");
            }

            $jsonString = json_encode($xmlString);

            if (json_last_error() > 0) {
                throw new Exception("XML not in correct format.#4 | " . json_last_error_msg());
            }
        } catch (Exception $ex) {
            $return_val = false;
            $error_msg = $ex->getMessage();
        }
        return array($return_val, $error_msg, $jsonString);
    }

}
if (!function_exists('ConvertXmlToArray')) {

    function ConvertXmlToArray($xmlString) {
        $return_val = true;
        $error_msg = "";
        $jsonArray = array();

        try {

            $jsonString = ConvertXmlToJson($xmlString);

            if ($jsonString[0] == false) {
                $return_val = false;
                $error_msg = $jsonString[1];
            } else {
                $jsonArray = json_decode($jsonString[2], true);

                if (json_last_error() > 0) {
                    throw new Exception("XML not in correct format.#5 | " . json_last_error_msg());
                }
            }
        } catch (Exception $ex) {
            $return_val = false;
            $error_msg = $ex->getMessage();
        }

        return array($return_val, $error_msg, $jsonArray);
    }

}

if (!function_exists('is_mobile')) {

    function is_mobile($mob, $country_code = 0) {
        if ($country_code == 971) {
            return preg_match("/^[0,5,{0,5}]+[0-9]{7}$/", $mob);
        } else if ($country_code == 0) {
            return preg_match("/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1}){0,1}\d{10,12}$/", $mob);
        } else {
            return preg_match("/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1}){0,1}\d{10,12}$/", $mob);
        }
    }

}

if (!function_exists('display_data')) {

    function display_data($data, $type = 0) {

        $display_data = "-";

        if (!empty($data)) {
            $display_data = $data;
        }

        return $display_data;
    }

}

if (!function_exists('display_date_format')) {

    function display_date_format($data, $type = 0) {

        $display_data = "-";

        if (!empty($data) && !strpos($data, '0000-00-00')) {

            $display_data = date("d-m-Y H:i", strtotime($data));

            if ($type == 1) {
                $display_data = date("d-m-Y H:i:s", strtotime($data));
            }
            if ($type == 2) {
                $display_data = date("d-m-Y", strtotime($data));
            }
        }

        return $display_data;
    }

}
if (!function_exists('getIpAddress')) {

    function getIpAddress() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } else if (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } else if (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } else if (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }

}

if (!function_exists('strongPassword')) {


    function strongPassword($pwd, $username) {
        $return_array = array(true, "");
        $return_val = true;
        $error = "";
        if (strlen($pwd) < 8) {
            $error .= "New password too short (min 8 chars). | ";
            $return_val = false;
        } else if (strlen($pwd) > 25) {
            $error .= "New password too long (max 25 chars). | ";
            $return_val = false;
        }

        if (!preg_match("#[0-9]+#", $pwd) && !preg_match("#\W+#", $pwd)) {
            $error .= "New password must include at least one number or one symbol. | ";
            $return_val = false;
        }

        if (!preg_match("#[a-z]+#", $pwd) && !preg_match("#[A-Z]+#", $pwd)) {
            $error .= "New password must include at least one letter. | ";
            $return_val = false;
        }

        $alphabets_lower = "abcdefghijklmnopqrstuvwxyz";
        $alphabets_upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $numbers = "123456789";
        for ($i = 0; $i < strlen($alphabets_lower) - 4; $i++) {
            $tmp_sub_str = substr($alphabets_lower, $i, 4);
            if (strpos($pwd, $tmp_sub_str) >= 0 && strpos($pwd, $tmp_sub_str) !== false) {
                $error .= "Do not include 4 or more small identical characters like abcd. | ";
                $return_val = false;
            }
        }

        for ($i = 0; $i < strlen($alphabets_upper) - 4; $i++) {
            $tmp_sub_str = substr($alphabets_upper, $i, 4);
            if (strpos($pwd, $tmp_sub_str) >= 0 && strpos($pwd, $tmp_sub_str) !== false) {
                $error .= "Do not include 4 or more capital identical characters like ABCD. | ";
                $return_val = false;
            }
        }

        for ($i = 0; $i < strlen($numbers) - 4; $i++) {
            $tmp_sub_str = substr($numbers, $i, 4);
            if (strpos($pwd, $tmp_sub_str) >= 0 && strpos($pwd, $tmp_sub_str) !== false) {
                $error .= "Do not include 4 or more identical characters like 1234. | ";
                $return_val = false;
            }
        }

        if (preg_match('/(.)\\1{2}/', $pwd)) {
            $error .= "Do not include 3 same characters consecutively like xxx or 111. | ";
            $return_val = false;
        }

        if (strpos($pwd, $username) >= 0 && strpos($pwd, $username) !== false) {
            $error .= "Do not include username in password. | ";
            $return_val = false;
        }

        if (strpos($pwd, " ") >= 0 && strpos($pwd, " ") !== false) {
            $error .= "Do not include spaces in password. | ";
            $return_val = false;
        }

        $return_array[0] = $return_val;
        $return_array[1] = rtrim($error, " | ");

        return $return_array;
    }

}

function unformatMoney($str) {
    return preg_replace("/[,\s]/", "", preg_replace("/^[0]{1,20}/", "", $str));
}

if (!function_exists('lw_send_email')) {

    function lw_send_email($to_email, $subject, $message, $bcc_email = "", $cc_email = "", $from_email = "", $reply_to = null, $attchement_path = "", $attachement_name = "", $file_name="") {
        $status = 0;
        $error = "";
        $active_id = 5;
        if ($reply_to === null) {
            $reply_to = getenv("MAIN_EMAIL");
        }

        if (empty($to_email) || empty($subject) || empty($message)) {
            $error = "Please check email id, subject and message when sent email";
        } else {

            if (empty($from_email)) {
                $from_email = getenv('MAIN_EMAIL');
            }

            $ci = & get_instance();
            if ($active_id == 1) {

                $config = array();
                $config['protocol'] = "smtp";
                $config['smtp_host'] = "smtp.mailgun.org";
                $config['smtp_user'] = "postmaster@paisaonsalary.com";
                $config['smtp_pass'] = "7d8a311a3141e4cb5f050cd73ea58e9a-2175ccc2-4ddf8189";
                $config['smtp_port'] = 587;
                $config['mailtype'] = "html";
                $config['charset'] = "UTF-8";
                $config['priority'] = 1;
                $config['newline'] = "\r\n";
                $config['wordwrap'] = TRUE;

                $ci->load->library('email', $config);

                $ci->email->initialize($config);

                $ci->email->set_newline("\r\n");

                $ci->email->from($from_email);

                if (!empty($bcc_email)) {
                    $ci->email->bcc($bcc_email);
                }
                if (!empty($cc_email)) {
                    $ci->email->cc($cc_email);
                }

                $ci->email->to($to_email);

                $ci->email->subject($subject);

                $ci->email->message($message);

                if ($ci->email->send()) {
                    $status = 1;
                } else {
                    $error = "Some error occurred";
                }
            } else if ($active_id == 2) {

                if (empty($from_email)) {
                    $from_email = INFO_EMAIL;
                }

                $apiUrl = "https://api.mailgun.net/v3/paisaonsalary.com/messages";

                $request_array = array(
                    "from" => $from_email,
                    "to" => $to_email,
                    "subject" => $subject,
                    "html" => $message
                );

                if (!empty($bcc_email)) {
                    $request_array["bcc"] = $bcc_email;
                }

                if (!empty($cc_email)) {
                    $request_array["cc"] = $cc_email;
                }

                if (!empty($reply_to)) {
                    $request_array["h:Reply-To"] = $reply_to;
                }

                if (!empty($attchement_path) && !empty($attachement_name)) {
                    $request_array['attachment[1]'] = curl_file_create($attchement_path . $attachement_name, 'application/pdf', 'sanction_letter.pdf');
                }

                $apiHeaders = array(
                    "Authorization: Basic " . base64_encode("api:ac285223954fe82eaa5c4f048572ccda-19806d14-111aeb0b"),
                    "Content-Type:multipart/form-data",
                );

                $curl = curl_init($apiUrl);
                curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $apiHeaders);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $request_array);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

                $response = curl_exec($curl);

                $return_array = json_decode($response, true);

                if ($return_array['message'] == "Queued. Thank you.") {
                    $status = 1;
                } else {
                    $error = $return_array['message'];
                }
            } else if ($active_id == 4) {

                $config = array();
                $config['protocol'] = "smtp";
                $config['smtp_host'] = "smtp.mailgun.org";
                $config['smtp_user'] = "postmaster@paisaonsalary.com";
                $config['smtp_pass'] = "7d8a311a3141e4cb5f050cd73ea58e9a-2175ccc2-4ddf8189";
                $config['smtp_port'] = 587;
                $config['mailtype'] = "html";
                $config['charset'] = "UTF-8";
                $config['priority'] = 1;
                $config['newline'] = "\r\n";
                $config['wordwrap'] = TRUE;

                $ci->load->library('email', $config);

                $ci->email->initialize($config);

                $ci->email->set_newline("\r\n");

                $ci->email->from($from_email);

                if (!empty($bcc_email)) {
                    $ci->email->bcc($bcc_email);
                }
                if (!empty($cc_email)) {
                    $ci->email->cc($cc_email);
                }

                $ci->email->to($to_email);

                $ci->email->subject($subject);

                $ci->email->message($message);

                if ($ci->email->send()) {
                    $status = 1;
                } else {
                    $error = "Some error occurred";
                }
            } else if ($active_id == 5) {
                $apiKey = getenv('MAIL_GUN_API_KEY');
                $domain = getenv('MAIL_GUN_DOMAIN');
                $apiUrl = "https://api.mailgun.net/v3/$domain/messages";
            
                $postFields = [
                    'from' => BRAND_NAME." <$from_email>",
                    'to' => $to_email,
                    'subject' => $subject,
                    'html' => $message,
                ];
            
                if (!empty($reply_to)) {
                    $postFields['h:Reply-To'] = $reply_to;
                }
            
                // Process CC
                if (!empty($cc_email)) {
                    $cc_email = explode(',', $cc_email);
                    $cc_filtered = array_filter(array_map('trim', $cc_email), fn($email) => strtolower($email) !== strtolower($to_email));
                    if (!empty($cc_filtered)) {
                        $postFields['cc'] = implode(',', $cc_filtered);
                    }
                }
            
                // Process BCC
                if (!empty($bcc_email)) {
                    $bcc_email = explode(',', $bcc_email);
                    $bcc_filtered = array_filter(array_map('trim', $bcc_email), fn($email) => strtolower($email) !== strtolower($to_email));
                    if (!empty($bcc_filtered)) {
                        $postFields['bcc'] = implode(',', $bcc_filtered);
                    }
                }
            
                // Process Attachment
                if (!empty($attachement_name)) {
                    $fileContents = downloadDocument($attachement_name, 1);
                    $tempFile = tempnam(sys_get_temp_dir(), 'attach_');
                    file_put_contents($tempFile, $fileContents);
                    $postFields['attachment'] = new CURLFile($tempFile, 'application/pdf', $file_name);
                }
            
                // Setup cURL
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => $apiUrl,
                    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                    CURLOPT_USERPWD => "api:$apiKey",
                    CURLOPT_POST => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POSTFIELDS => $postFields,
                    CURLOPT_CONNECTTIMEOUT => 20,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_SSL_VERIFYHOST => 2,
                ]);
            
                $response = curl_exec($curl);
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                $curlError = curl_error($curl);
            
                curl_close($curl);
            
                // Remove temp file if used
                if (!empty($tempFile) && file_exists($tempFile)) {
                    unlink($tempFile);
                }
            
                // Logging
                $logData = [
                    'to' => $to_email,
                    'cc' => $postFields['cc'] ?? '',
                    'bcc' => $postFields['bcc'] ?? '',
                    'subject' => $subject,
                    'status_code' => $httpCode,
                    'response' => $response,
                    'error' => $curlError
                ];
            
                file_put_contents(__DIR__ . '/mailgun_log.txt', json_encode($logData, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);
            
                if ($curlError) {
                    $error = "cURL Error: $curlError";
                } elseif ($httpCode >= 400) {
                    $responseDecoded = json_decode($response, true);
                    $error = $responseDecoded['message'] ?? 'Unknown Mailgun error';
                } else {
                    $status = 1; // success
                }
            }
        }

        $return_array = array("status" => $status, "error" => $error);

        return $return_array;
    }

    if (!function_exists('lw_send_sms')) {

//            function lw_send_sms($lead_id, $mobile, $sms_content, $m_sms_primary_id, $sms_template_id, $sms_source, $log_insert_flag = true) {
        function lw_send_sms($lead_id, $mobile, $sms_content, $m_sms_primary_id = 0, $log_insert_flag = true) {
            $return_array = array("status" => 0, "error" => "");
            $active_id = 1;
            $sms_data = array();

            $ci = & get_instance();
            $ci->load->database();

            if (!empty($m_sms_primary_id)) {
                $sql = "SELECT m_st_id as m_sms_primary_id, m_st_template_id, m_st_template_source, m_st_description, m_st_content";
                $sql .= " From master_sms_template";
                $sql .= " where m_st_id='$m_sms_primary_id' ";

                $query = $ci->db->query($sql);

                if (!empty($query->num_rows())) {
                    $row = $query->row_array();
                    $sms_data['sms_primary_id'] = $row['m_sms_primary_id'];
                    $sms_data['sms_temp_id'] = $row['m_st_template_id'];
                    $sms_data['sms_source'] = $row['m_st_template_source'];
                }
            }

            if (empty($mobile)) {
                $return_array['error'] = "Please check customer mobile when sent sms.";
            } else if (empty($sms_content)) {
                $return_array['error'] = "Please check sms content when sent sms.";
            } else if (empty($sms_data['sms_temp_id'])) {
                $return_array['error'] = "Please check sms template Id when sent sms.";
            } else if (empty($sms_data['sms_source'])) {
                $return_array['error'] = "Please check sms source when sent sms.";
            } else {
                if ($active_id == 1) {

                    $username = urlencode("namanfinl");
                    $password = urlencode("ASX1@#SD");
                    $type = 0;
                    $dlr = 1;
                    $destination = $mobile;
                    $source = $sms_data['sms_source'];
                    $message = urlencode($sms_content);
                    $entityid = 1201159134511282286;
                    $tempid = $sms_data['sms_temp_id'];

                    $data = "username=$username&password=$password&type=$type&dlr=$dlr&destination=$destination&source=$source&message=$message&entityid=$entityid&tempid=$tempid";
                    $url = "http://sms6.rmlconnect.net/bulksms/bulksms?";

                    $ch = curl_init();
                    curl_setopt_array($ch, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $data
                    ));

                    $output = curl_exec($ch);

                    if ($output) {
                        unset($return_array['error']);
                        $return_array['status'] = 1;
                    } else {
                        $return_array['error'] = "Some error occurred";
                    }
                    curl_close($ch);

                    if ($log_insert_flag) {
                        $sms_data_log['lsl_lead_id'] = $lead_id;
                        $sms_data_log['lsl_sms_type_id'] = $sms_data['sms_primary_id'];
                        $sms_data_log['lsl_sms_mobile'] = $mobile;
                        $sms_data_log['lsl_sms_content'] = $sms_content;
                        $sms_data_log['lsl_api_status_id'] = $return_array['status'];
                        $sms_data_log['lsl_errors'] = (($return_array['error']) ? $return_array['error'] : "");
                        $sms_data_log['lsl_user_id'] = $_SESSION['isUserSession']['user_id'];
                        $sms_data_log['lsl_created_on'] = date('Y-m-d H:i:s');
                        $sms_data_log['lsl_active'] = 1;
                        $sms_data_log['lsl_deleted'] = 0;

                        $ci->db->insert('lead_sms_logs', $sms_data_log);
                    }
                }
            }
            return $return_array;
        }

    }
}

if (!function_exists('insertBharatDetails')) {

    function insertBharatDetails() {
        $ci = & get_instance();
        $db2 = $ci->load->database('milti_DB', TRUE);
        $query = $db2->query('select * from test;')->result_array();
        return $query;
    }

}

if (!function_exists('uploadDocument')) {

    function uploadDocument($file_obj, $lead_id = 0, $flag = 0, $ext = '') {
        $ci = & get_instance();

        $ci->load->library(array('S3_upload'));
        if ($flag == 1) {
            $extension = $ext;
        } else if ($flag == 2) {
            $extension = $ext;
        } else {
            $file_name = $file_obj["file_name"]['name'];
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $extension = strtolower($extension);
        }

        $new_name = $lead_id . '_lms_' . date('YmdHis') . rand(111, 999) . '.' . $extension;

        if ($flag == 1) {
            $upload = $ci->s3_upload->upload_file($file_obj, $new_name, $flag);
        } else if ($flag == 2) {
            $upload = $ci->s3_upload->upload_file($file_obj, $new_name);
        } else {
            $upload = $ci->s3_upload->upload_file($file_obj["file_name"]["tmp_name"], $new_name);
        }

        $return_status = 0;

        if ($upload) {
            $return_status = 1;
        }

        $return_array = ["status" => $return_status, "file_name" => $new_name];
        return $return_array;
    }

}


if (!function_exists('downloadDocument')) {

    function downloadDocument($file_name, $flag = 0) {
        $ci = & get_instance();

        $ci->load->library(array('S3_upload'));

        $upload = $ci->s3_upload->get_file($file_name, $flag);
        return $upload;
    }

}
