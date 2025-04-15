<?php

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class InstantJourneyController extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Instant_Model', 'InstantModel');
        $this->load->model('Task_Model', 'Tasks');
        date_default_timezone_set('Asia/Kolkata');
        define('created_on', date('Y-m-d H:i:s'));
        define('updated_on', date('Y-m-d H:i:s'));
    }

    public function instantAppVersionCheck_post() {

        $input_data = file_get_contents("php://input");

        if ($input_data) {
            $post = $this->security->xss_clean(json_decode($input_data, true));
        } else {
            $post = $this->security->xss_clean($_POST);
        }

       $headers = $this->input->request_headers();
       $token = $this->_token();
       $header_validation = (($headers['Accept'] == "application/json") && ($token['token_Leads'] == base64_decode($headers['Auth'])));

       if ($_SERVER['REQUEST_METHOD'] == 'POST' && $header_validation) {

            $this->form_validation->set_data($post);

            $this->form_validation->set_rules("version", "Version", "required|trim");
            $this->form_validation->set_rules("app_name", "App Name", "required|trim");

            if ($this->form_validation->run() == FALSE) {
                return json_encode($this->response(['Status' => 0, 'Message' => strip_tags(validation_errors())], REST_Controller::HTTP_OK));
            } else {
                $version = $post['version'];
                $app_name = $post['app_name'];

                $current_version = 1;
                //$current_version = 3;

                if (in_array($app_name, array("BLD"))) {

                    if ($version == $current_version) {
                        return json_encode($this->response(['Status' => 1, 'Message' => "Success", 'version' => $version], REST_Controller::HTTP_OK));
                    } else {
                        return json_encode($this->response(['Status' => 0, 'Message' => "Please update the new version", 'version' => $version], REST_Controller::HTTP_OK));
                    }
                } else {
                    return json_encode($this->response(['Status' => 0, 'Message' => "Invalid App Name", 'version' => $version], REST_Controller::HTTP_OK));
                }
            }
       } else {
           return json_encode($this->response(['Status' => 0, 'Message' => 'Request Method Post Failed.'], REST_Controller::HTTP_OK));
       }
    }

    public function appLogin_post() {
        $input_data = file_get_contents("php://input");

        if ($input_data) {
            $post = $this->security->xss_clean(json_decode($input_data, true));
        } else {
            $post = $this->security->xss_clean($_POST);
        }
        
       $headers = $this->input->request_headers();
       $token = $this->_token();
       $header_validation = (($headers['Accept'] == "application/json") && ($token['token_Leads'] == base64_decode($headers['Auth'])));

       if ($_SERVER['REQUEST_METHOD'] == 'POST' && $header_validation) {
            $this->form_validation->set_data($post);

            $this->form_validation->set_rules("mobile", "Mobile", "required|trim|numeric");

            if ($this->form_validation->run() == FALSE) {
                return json_encode($this->response(['Status' => 0, 'Message' => strip_tags(validation_errors())], REST_Controller::HTTP_OK));
            } else {
                $mobile = $post['mobile'];

                require_once (COMPONENT_PATH . 'CommonComponent.php');

                $CommonComponent = new CommonComponent();

                $otp = rand(1111, 9999);

                $insertDataOTP = array(
                    'lot_lead_id' => NULL,
                    'lot_mobile_no' => $mobile,
                    'lot_mobile_otp' => $otp,
                    'lot_mobile_otp_type' => NULL,
                    'lot_otp_trigger_time' => date('Y-m-d H:i:s'),
                );

                $this->db->insert('leads_otp_trans', $insertDataOTP);

                $lead_otp_id = $this->db->insert_id();

                $data = [
                    "name" => $first_name,
                    "mobile" => $mobile,
                    "otp" => $otp
                ];

                $sms_input_data = array();
                $sms_input_data['mobile'] = $mobile;
                $sms_input_data['name'] = "Customer";
                $sms_input_data['otp'] = $otp;

                $CommonComponent->payday_sms_api(1, $lead_id, $sms_input_data);

                if ($lead_otp_id) {
                    return json_encode($this->response(['Status' => 1, 'Message' => 'OTP sent successfully.'], REST_Controller::HTTP_OK));
                } else {
                    return json_encode($this->response(['Status' => 0, 'Message' => 'Failed.'], REST_Controller::HTTP_OK));
                }
            }
       } else {
           return json_encode($this->response(['Status' => 0, 'Message' => 'Request Method Post Failed.'], REST_Controller::HTTP_OK));
       }
    }
}
