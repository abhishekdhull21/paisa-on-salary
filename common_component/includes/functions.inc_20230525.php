<?php

function common_log_writer($type, $data) {

//    if (!empty($data) && (false || $type == 1)) {
    if (!empty($data) && false) {


        $mode = "a+";
        $file_name = 'common_log_' . date("YmdH") . ".log";

        if ($type == 1) {
            $file_name = 'eligibility_' . date("YmdH") . ".log";
        } else if ($type == 2) {
            $file_name = 'crif_' . date("YmdH") . ".log";
        } else if ($type == 3) {
            $file_name = 'signzy_' . date("YmdH") . ".log";
        } else if ($type == 4) {
            $file_name = 'esign_signzy_' . date("YmdH") . ".log";
        } else if ($type == 5) {
            $file_name = 'digilocker_signzy_' . date("YmdH") . ".log";
        } else if ($type == 6) {
            $file_name = 'bre_result_' . date("YmdH") . ".log";
        }

        $error_log_file = fopen(COMP_PATH . "/logs/$file_name", $mode);

        fwrite($error_log_file, PHP_EOL . "---" . date("Y-m-d H:i:s") . "---" . $data . PHP_EOL);
        fclose($error_log_file);
    }
}

function common_extract_value_from_xml($str1, $str2, $xml) {
    $stringExist = strpos($str1, $xml);
    if ($stringExist == false && $stringExist != 0) {
        $raw_data_string[0] = '';
    } else {
        $raw_data_string = explode($str1, trim($xml));
        $raw_data_string = explode($str2, trim($raw_data_string[1]));
    }
    return $raw_data_string[0];
}

function common_trim_data_array($inputstring) {

    if (!is_array($inputstring)) {
        $inputstring = trim($inputstring);
        $inputstring = addslashes($inputstring);
        $inputstring = preg_replace("!\s+!", " ", $inputstring);
        $inputstring = str_replace("Ã¢â‚¬â€œ", " ", $inputstring);
        $inputstring = str_replace("ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Å“", " ", $inputstring);
        $inputstring = preg_replace("!\s+!", " ", $inputstring);
        return $inputstring;
    }

    return array_map('common_trim_data_array', $inputstring);
}

//function common_parse_name($full_name = "") {
//    $first_name = $middle_name = $last_name = "";
//    if (!empty($full_name)) {
//        $first_name = substr($full_name, 0, (strpos($full_name, " ") !== false) ? strpos($full_name, " ") : strlen($full_name));
//        $full_name = trim(str_replace($first_name, "", $full_name));
//        $last_name = !empty($full_name) ? substr($full_name, (strrpos($full_name, " ", -1) !== false) ? strrpos($full_name, " ", -1) : 0, strlen($full_name)) : "";
//        $last_name = trim($last_name);
//        $full_name = trim(str_replace($last_name, "", $full_name));
//        $middle_name = trim($full_name);
//    }
//    return array("first_name" => $first_name, "middle_name" => $middle_name, "last_name" => $last_name);
//}

function common_parse_name($full_name = "") {
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

function common_send_email($to_email, $subject, $message, $bcc_email = "", $cc_email = "", $from_email = "", $reply_to = "") {
    $status = 0;
    $error = "";
    $active_id = 5;

    if (empty($to_email) || empty($subject) || empty($message)) {
        $error = "Please check email id, subject and message when sent email";
    } else {

        if (empty($from_email)) {
            $from_email = "info@surya.com";
        }

        $ci = & get_instance();
        if ($active_id == 1) {

            $config = array();
            $config['protocol'] = "smtp";
            $config['smtp_host'] = "smtp.mailgun.org";
            $config['smtp_user'] = "info@surya.com";
            $config['smtp_pass'] = "";
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
                $from_email = "info@surya.com";
            }

            $apiUrl = "https://api.mailgun.net/v3/surya.com/messages";

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

            $apiHeaders = array(
                "Authorization: Basic " . base64_encode("api:"),
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
        } else if ($active_id == 3) {

            $apiUrl = ""; // https://api.sendgrid.com/v3/mail/send

            $apiHeaders = array(
                "Authorization: Bearer ",
                "Accept: application/json",
                "Content-Type: application/json",
            );

            $apiRequestArray = [];

            $send_email_array = [];

            $send_email_array["to"] = [["email" => $to_email]];

            if (!empty($cc_email)) {

                $cc_email = explode(",", $cc_email);

                $sent_cc_email = [];
                foreach ($cc_email as $email_data) {
                    $sent_cc_email[] = ["email" => trim($email_data)];
                }

                $send_email_array["cc"] = $sent_cc_email;
            }

            if (!empty($bcc_email)) {

                $bcc_email = explode(",", $bcc_email);

                $sent_bcc_email = [];
                foreach ($bcc_email as $email_data) {
                    $sent_bcc_email[] = ["email" => trim($email_data)];
                }

                $send_email_array["bcc"] = $sent_bcc_email;
            }

            $apiRequestArray["personalizations"] = [$send_email_array];

            $apiRequestArray["from"] = ["email" => $from_email, "name" => "Bharataloan.com"];

            $apiRequestArray["reply_to"] = array("email" => $reply_to);

            $apiRequestArray["subject"] = $subject;

            $apiRequestArray["content"] = [[
            "type" => "text/html",
            "value" => "$message"
            ]];

            if (!empty($attchement_path) && !empty($attachement_name)) {
                $apiRequestArray['attachments'] = [
                    [
                        "content" => base64_encode(file_get_contents($attchement_path . $attachement_name)),
                        "type" => "application/pdf",
                        "filename" => "sanction_letter.pdf",
                        "disposition" => "attachment"
                    ]
                ];
            }

            $apiResponseJson = json_encode($apiRequestArray);
            $apiResponseJson = preg_replace("!\s+!", " ", $apiResponseJson);

            $curl = curl_init($apiUrl);
            curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $apiHeaders);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $apiResponseJson);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

            $response = curl_exec($curl);

            if (empty($response)) {
                $status = 1;
            } else {
                $return_array = json_decode($response, true);
                $error = isset($return_array['errors'][0]['message']) ? $return_array['errors'][0]['message'] : "Some error occourred.";
            }
        } else if ($active_id == 4) {

            $config = array();
            $config['protocol'] = "smtp";
            $config['smtp_host'] = "smtp.sendgrid.net";
            $config['smtp_user'] = "apikey";
            $config['smtp_pass'] = "";
            $config['smtp_port'] = 25;
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

            $apiUrl = "https://api.sendgrid.com/v3/mail/send";

            $apiHeaders = array(
                "Authorization: Bearer ",
                "Accept: application/json",
                "Content-Type: application/json",
            );

            $apiRequestArray = [];

            $send_email_array = [];

            $send_email_array["to"] = [["email" => $to_email]];

            if (!empty($cc_email)) {

                $cc_email = explode(",", $cc_email);

                $sent_cc_email = [];
                foreach ($cc_email as $email_data) {

                    if (trim(strtolower($to_email)) == trim(strtolower($email_data))) {
                        continue;
                    }
                    $sent_cc_email[] = ["email" => trim($email_data)];
                }

                if (!empty($sent_cc_email)) {
                    $send_email_array["cc"] = $sent_cc_email;
                }
            }

            if (!empty($bcc_email)) {

                $bcc_email = explode(",", $bcc_email);

                $sent_bcc_email = [];
                foreach ($bcc_email as $email_data) {
                    if (trim(strtolower($to_email)) == trim(strtolower($email_data))) {
                        continue;
                    }
                    $sent_bcc_email[] = ["email" => trim($email_data)];
                }

                if (!empty($sent_bcc_email)) {
                    $send_email_array["bcc"] = $sent_bcc_email;
                }
            }

            $apiRequestArray["personalizations"] = [$send_email_array];

            $apiRequestArray["from"] = ["email" => $from_email, "name" => "Bharat Loan"];

            if (!empty($reply_to)) {
                $apiRequestArray["reply_to"] = array("email" => $reply_to);
            }

            $apiRequestArray["subject"] = $subject;

            $apiRequestArray["content"] = [[
            "type" => "text/html",
            "value" => "$message"
            ]];

            if (!empty($attchement_path) && !empty($attachement_name)) {
                $apiRequestArray['attachments'] = [
                    [
                        "content" => base64_encode(file_get_contents($attchement_path . $attachement_name)),
                        "type" => "application/pdf",
                        "filename" => "sanction_letter.pdf",
                        "disposition" => "attachment"
                    ]
                ];
            }

            $apiResponseJson = json_encode($apiRequestArray);
            $apiResponseJson = preg_replace("!\s+!", " ", $apiResponseJson);
//                echo $apiResponseJson . "<br/><br/>";
            $curl = curl_init($apiUrl);
            curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $apiHeaders);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $apiResponseJson);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

            $response = curl_exec($curl);
//                traceObject($response);

            if (empty($response)) {
                $status = 1;
            } else {
                $return_array = json_decode($response, true);
                $error = isset($return_array['errors'][0]['message']) ? $return_array['errors'][0]['message'] : "Some error occourred.";
            }
        }
    }

    $return_array = array("status" => $status, "error" => $error);

    return $return_array;
}

function common_lead_thank_you_email($lead_id, $email, $name, $reference_no) {

    $return_array = array();

    if (empty($lead_id) || empty($email) || empty($name) || empty($reference_no)) {
        $return_array['Status'] = 0;
        $return_array['Message'] = 'Lead id required.';
        return $return_array;
    } else {

        $subject = 'Thank You. - Bharat Loan';

        $html = '<!DOCTYPE html>
                        <html xmlns="http://www.w3.org/1999/xhtml">
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                <title>Thank You</title>
                            </head>
                            <body>
                                <table width="400" border="0" align="center" style="font-family:Arial, Helvetica, sans-serif; border:solid 1px #ddd; padding:10px; background:#f9f9f9;">
                                    <tr>
                                        <td width="775" align="center"><img src="https://www.surya.com/public/images/brand_logo.png"></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;"><table width="418" border="0" style="text-align:center; padding:20px; background:#fff;">
                                                <tr>
                                                    <td style="font-size:16px;"><img src="https://www.surya.com/public/emailimages/Thank_you/thank-you.png" width="150" height="150" alt="thank-you"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:16px;">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="412" style="font-size:16px;"><h2 style="margin:0px; color:#116a97;">Thank You</h2></td>
                                                </tr>
                                                <tr>
                                                    <td width="412" style="font-size:16px;"><h2 style="margin:0px; color:#116a97;">Dear ' . $name . '</h2></td>
                                                </tr>

                                                <tr>
                                                    <td><p style="line-height:25px; margin:0px;">Thank you for showing interest in Bharat Loan.</p></td>
                                                </tr>
                                                <tr>
                                                    <td><p style="line-height:25px; margin:0px;">We have received your loan application <strong style="color:#116a97;">' . $reference_no . '</strong> successfully. Please note the same for future communication.</p></td>
                                                </tr>
                                            </table></td>
                                    </tr>
                                    <tr>
                                        <td align="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="center">Follow Us On</td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <a href="https://www.facebook.com/surya-105632195732824" target="_blank"><img src="https://www.surya.com/public/image/surya-facebook.png" class="socil-t" alt="surya-facebook" style="width:30px;"></a>
                                            <a href="https://twitter.com/suryas" target="_blank"><img src="https://www.surya.com/public/image/surya-twitter.png" class="socil-t" alt="surya-twitter" style="width:30px;"></a>
                                            <a href="https://www.linkedin.com/company/surya" target="_blank"><img src="https://www.surya.com/public/image/surya-linkdin.png" class="socil-t" alt="surya-linkdin" style="width:30px;"></a>
                                            <a href="https://www.instagram.com/surya_india" target="_blank"><img src="https://www.surya.com/public/image/surya-instagram.png" class="socil-t" alt="surya-instagram" style="width:30px;"></a>
                                            <a href="https://www.youtube.com/channel/UCUwrJB1IMvDiMctHHRKDLxw" target="_blank"><img src="https://www.surya.com/public/image/surya-youtube.png" class="socil-t" alt="surya-youtube" style="width:30px;"></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">For Latest Updates &amp; Offers</td>
                                    </tr>
                                </table>
                            </body>
                        </html>';

        $email_status = common_send_email($email, $subject, $html);

        if ($email_status) {
            $return_array['email_status'] = $email_status;
            $return_array['Status'] = 1;
            $return_array['Message'] = 'Email sent successfully.';
        }

        return $return_array;
    }
}

?>
