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
        } else if ($type == 7) {
            $file_name = 'bank_analysis_' . date("YmdH") . ".log";
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

function common_send_email(
    $to_email,
    $subject,
    $message,
    $bcc_email = "",
    $cc_email = "",
    $from_email = "",
    $reply_to = "",
    $attachment_path = "",
    $fileName = "",
    $file_move = ""
) {
    $status = 0;
    $error = "";

    if (empty($to_email) || empty($subject) || empty($message)) {
        return ["status" => 0, "error" => "Email, subject, and message are required."];
    }

    $active_id = 5;
    $ci = &get_instance();

    if (empty($from_email)) {
        $from_email = "info@" . getenv('MAIL_GUN_DOMAIN');
    }

    try {
        switch ($active_id) {
            case 0: // CodeIgniter SMTP
                $config = [
                    'protocol' => 'smtp',
                    'smtp_host' => 'smtp.mailgun.org',
                    'smtp_user' => "https://api.mailgun.net/v3/" . getenv('MAIL_GUN_DOMAIN') . "/messages",
                    'smtp_pass' => getenv('MAIL_GUN_API_KEY'),
                    'smtp_port' => 587,
                    'mailtype' => 'html',
                    'charset' => 'UTF-8',
                    'priority' => 1,
                    'newline' => "\r\n",
                    'wordwrap' => true
                ];

                $ci->load->library('email', $config);
                $ci->email->initialize($config);

                $ci->email->from($from_email);
                $ci->email->to($to_email);
                if (!empty($bcc_email)) $ci->email->bcc($bcc_email);
                $ci->email->cc(['info@' . getenv('MAIL_GUN_DOMAIN'), 'tech.support@' . getenv('MAIL_GUN_DOMAIN')]);
                $ci->email->subject($subject);
                $ci->email->message($message);

                $status = $ci->email->send() ? 1 : 0;
                if (!$status) $error = $ci->email->print_debugger();
                break;

            case 2: // Mailgun direct curl
                $url = "https://api.mailgun.net/v3/" . getenv('MAIL_GUN_DOMAIN') . "/messages";
                $fields = [
                    'from' => $from_email,
                    'to' => $to_email,
                    'subject' => $subject,
                    'text' => $message
                ];

                if (!empty($bcc_email)) $fields['bcc'] = $bcc_email;
                if (!empty($cc_email)) $fields['cc'] = $cc_email;
                if (!empty($reply_to)) $fields['h:Reply-To'] = $reply_to;

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_USERPWD => 'api:' . getenv('MAIL_GUN_API_KEY'),
                    CURLOPT_POSTFIELDS => $fields
                ]);

                $response = curl_exec($curl);
                $error_msg = curl_error($curl);
                curl_close($curl);

                $result = json_decode($response, true);
                if (isset($result['message']) && stripos($result['message'], "Queued") !== false) {
                    $status = 1;
                } else {
                    $error = $result['message'] ?? $error_msg ?? "Unknown error occurred";
                }
                break;

            case 3: // SendGrid (template)
                // Intentionally left out since it's incomplete in your version
                $error = "SendGrid handler not implemented completely.";
                break;

            case 4: // Alternate SMTP (incomplete)
                $config = [
                    'protocol' => 'smtp',
                    'smtp_host' => 'smtp.mailgun.org',
                    'smtp_user' => '',
                    'smtp_pass' => '',
                    'smtp_port' => 587,
                    'mailtype' => 'html',
                    'charset' => 'UTF-8',
                    'priority' => 1,
                    'newline' => "\r\n",
                    'wordwrap' => true
                ];

                $ci->load->library('email', $config);
                $ci->email->initialize($config);
                $ci->email->from($from_email);
                $ci->email->to($to_email);
                if (!empty($bcc_email)) $ci->email->bcc($bcc_email);
                if (!empty($cc_email)) $ci->email->cc($cc_email);
                $ci->email->subject($subject);
                $ci->email->message($message);

                $status = $ci->email->send() ? 1 : 0;
                if (!$status) $error = $ci->email->print_debugger();
                break;

            case 5: // Mailgun with HTML and attachment support
                $url = 'https://api.mailgun.net/v3/' . getenv('MAIL_GUN_DOMAIN') . '/messages';
                $fields = [
                    'from' => $from_email,
                    'to' => $to_email,
                    'subject' => $subject
                ];

                if (stripos($message, 'DOCTYPE html') !== false || stripos($message, '<html') !== false) {
                    $fields['html'] = $message;
                } else {
                    $fields['text'] = $message;
                }

                if (!empty($cc_email)) $fields['cc'] = $cc_email;
                if (!empty($attachment_path) && file_exists($attachment_path)) {
                    $fields['attachment'] = new CURLFile($attachment_path);
                }

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_USERPWD => 'api:' . getenv('MAIL_GUN_API_KEY'),
                    CURLOPT_POSTFIELDS => $fields
                ]);

                $response = curl_exec($curl);
                $error_msg = curl_error($curl);
                curl_close($curl);

                $result = json_decode($response, true);
                if (isset($result['message']) && stripos($result['message'], "Queued") !== false) {
                    $status = 1;
                } else {
                    $error = $result['message'] ?? $error_msg ?? "Unknown error sending mail.";
                }
                break;

            default:
                $error = "Invalid email method specified.";
                break;
        }
    } catch (Exception $e) {
        $error = "Exception: " . $e->getMessage();
    }

    return ["status" => $status, "error" => $error];
}


function common_lead_thank_you_email($lead_id, $email, $name, $reference_no) {

    $return_array = array();

    if (empty($lead_id) || empty($email) || empty($name) || empty($reference_no)) {
        $return_array['Status'] = 0;
        $return_array['Message'] = 'Lead id required.';
        return $return_array;
    } else {

        $subject = 'Thank You. - paisaonsalary';

        $html = '<!DOCTYPE html>
                        <html xmlns="http://www.w3.org/1999/xhtml">
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                <title>Thank You</title>
                            </head>
                            <body>
                                <table width="400" border="0" align="center" style="font-family:Arial, Helvetica, sans-serif; border:solid 1px #ddd; padding:10px; background:#f9f9f9;">
                                    <tr>
                                        <td width="775" align="center"><img src="https://www.paisaonsalary.com/public/images/brand_logo.png"></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;"><table width="418" border="0" style="text-align:center; padding:20px; background:#fff;">
                                                <tr>
                                                    <td style="font-size:16px;"><img src="https://www.paisaonsalary.com/public/emailimages/Thank_you/thank-you.png" width="150" height="150" alt="thank-you"></td>
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
                                                    <td><p style="line-height:25px; margin:0px;">Thank you for showing interest in paisaonsalary.</p></td>
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
                                            <a href="https://www.facebook.com/paisaonsalary-105632195732824" target="_blank"><img src="https://www.paisaonsalary.com/public/image/paisaonsalary-facebook.png" class="socil-t" alt="paisaonsalary-facebook" style="width:30px;"></a>
                                            <a href="https://twitter.com/paisaonsalary" target="_blank"><img src="https://www.paisaonsalary.com/public/image/paisaonsalary-twitter.png" class="socil-t" alt="paisaonsalary-twitter" style="width:30px;"></a>
                                            <a href="https://www.linkedin.com/company/paisaonsalary" target="_blank"><img src="https://www.paisaonsalary.com/public/image/paisaonsalary-linkdin.png" class="socil-t" alt="paisaonsalary-linkdin" style="width:30px;"></a>
                                            <a href="https://www.instagram.com/paisaonsalary_india" target="_blank"><img src="https://www.paisaonsalary.com/public/image/paisaonsalary-instagram.png" class="socil-t" alt="paisaonsalary-instagram" style="width:30px;"></a>
                                            <a href="https://www.youtube.com/channel/UCUwrJB1IMvDiMctHHRKDLxw" target="_blank"><img src="https://www.paisaonsalary.com/public/image/paisaonsalary-youtube.png" class="socil-t" alt="paisaonsalary-youtube" style="width:30px;"></a>
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

if (!function_exists('uploadDocument')) {

/*     function uploadDocument() { //$file_obj, $lead_id = 0, $flag = 0, $ext = ''
        require_once (COMP_PATH . '/s3_bucket/S3_library.php');

        $s3_upload = new S3_upload();

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
            $upload = $s3_conn->upload_file($file_obj, $new_name, $flag);
        } else if ($flag == 2) {
            $upload = $s3_conn->upload_file($file_obj, $new_name);
        } else {
            $upload = $s3_conn->upload_file($file_obj["file_name"]["tmp_name"], $new_name);
        }

        $return_status = 0;

        if ($upload) {
            $return_status = 1;
        }

        $return_array = ["status" => $return_status, "file_name" => $new_name];
        return $return_array;
    } */

}


if (!function_exists('downloadDocument')) {

    function downloadDocument($file_name, $flag = 0) {
        $ci = & get_instance();

        $ci->load->library(array('S3_upload'));

        $upload = $s3_conn->get_file($file_name, $flag);
        return $upload;
    }

}
?>
