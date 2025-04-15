<?php

// defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class CustomerDetails extends REST_Controller {

    // public $white_listed_ips = array("208.109.63.229");

    public function __construct() {
        parent::__construct();
        $this->load->model('Lead_Model', 'Lead');
        date_default_timezone_set('Asia/Kolkata');
        define('created_on', date('Y-m-d H:i:s'));
        define('created_date', date('Y-m-d'));
        ini_set('max_execution_time', 3600);
        ini_set("memory_limit", "1024M");
    }


    public function getLoan_post() {
        $input_data = file_get_contents("php://input");
        //return $this->response($input_data, REST_Controller::HTTP_OK);
        if ($input_data) {
            $jsonInput = json_decode($input_data, true);
            $post = $this->security->xss_clean($jsonInput);
        } else {
            $post = $this->security->xss_clean($_POST);
        }

        // print_r($post); die;

        $query = $this->db->select('lead_id, first_name, mobile, email, loan_amount,loan_no')->where('pancard', $post['panNumber'])->where('status', 'DISBURSED')->where('stage', 'S14')->from('leads')->order_by('lead_id', 'DESC')->limit(1)->get();
        $result = $query->row();
        if (isset($result) && !empty($result->lead_id)) {
            $res = array('Status' => 1, 'Message' => 'Record found successfully.');
            $data = $this->Lead->getLoanRepaymentDetails($result->lead_id);
            $res['data'] = $data;
            return json_encode($this->response($res, REST_Controller::HTTP_OK));
        } else {
            return json_encode($this->response(['Status' => 2, 'Message' => 'No PAN found.'], REST_Controller::HTTP_OK));
        }
    }



    public function SendOtp_post() {

        $input_data = file_get_contents("php://input");
        //return $this->response($input_data, REST_Controller::HTTP_OK);
        if ($input_data) {
            $jsonInput = json_decode($input_data, true);
            $post = $this->security->xss_clean($jsonInput);
        } else {
            $post = $this->security->xss_clean($_POST);
        }


        $queryLead = $this->db->select('lead_id, first_name, mobile')->where('pancard', $post['pancard'])->where('status', 'DISBURSED')->where('stage', 'S14')->from('leads')->order_by('lead_id', 'DESC')->limit(1)->get();
        $resultLead = $queryLead->row();

        $Panlead = $resultLead->lead_id;
        $mobileLead = $resultLead->mobile;
        $firstname_Lead = $resultLead->first_name;
        $last_four_mob = substr($mobileLead, -4);


        $currentDate = date("Y-m-d");

        $queryOtp = $this->db->select('lot_lead_id,lot_mobile_no')->where('lot_mobile_no', $mobileLead)->where("DATE_FORMAT(lot_otp_trigger_time, '%Y-%m-%d') =", $currentDate)->where('lot_lead_id', $Panlead)->from('leads_otp_trans')->get();
        $resultotp = $queryOtp->row();



        if ($queryOtp->num_rows() > 2) {
            $res = array('Status' => 2, 'Message' => 'You can attempt max 3 time.');
            return json_encode($this->response($res, REST_Controller::HTTP_OK));
        } else {
            if (isset($Panlead) && ($Panlead > 0)) {
                $otp = rand(1000, 9999);
                $insertDataOTP = array(
                    'lot_lead_id' => $Panlead,
                    'lot_mobile_no' => $mobileLead,
                    'lot_mobile_otp' => $otp,
                    'lot_mobile_otp_type' => 2,
                    'lot_otp_trigger_time' => date('Y-m-d H:i:s'),
                );

                $this->db->insert('leads_otp_trans', $insertDataOTP);
                $this->db->insert_id();
                require_once(COMPONENT_PATH . 'CommonComponent.php');
                $CommonComponent = new CommonComponent();
                $sms_input_data = array();
                $sms_input_data['mobile'] = $mobileLead;
                $sms_input_data['name'] = $firstname_Lead;
                $sms_input_data['otp'] = $otp;
                $CommonComponent->payday_sms_api(1, $Panlead, $sms_input_data);


                return json_encode($this->response(['Status' => 1, 'Message' => 'Otp send on your register mobile no. #######' . $last_four_mob], REST_Controller::HTTP_OK));
            } else {
                return json_encode($this->response(['Status' => 2, 'Message' => 'Enter Wrong Otp.'], REST_Controller::HTTP_OK));
            }
        }
    }

    public function verifyOtp_post() {
        $input_data = file_get_contents("php://input");
        //return $this->response($input_data, REST_Controller::HTTP_OK);
        if ($input_data) {
            $jsonInput = json_decode($input_data, true);
            $post = $this->security->xss_clean($jsonInput);
        } else {
            $post = $this->security->xss_clean($_POST);
        }


        $queryLead = $this->db->select('lead_id, first_name, otp, mobile, email, loan_amount,loan_no')->where('pancard', $post['panNumber'])->where('status', 'DISBURSED')->where('stage', 'S14')->from('leads')->order_by('lead_id', 'DESC')->limit(1)->get();
        $resultLead = $queryLead->row();
        $mobileLead = $resultLead->mobile;
        $last_four_mob = substr($mobileLead, -4);
        $Panlead = $resultLead->lead_id;
        $PanOtp = $resultLead->otp;

        $currentDate = date("Y-m-d");

        if (isset($Panlead) && $Panlead > 0) {
            $queryOtp = $this->db->select('*')->where('lot_mobile_otp', $post['otp'])->where('lot_lead_id', $Panlead)->where("DATE_FORMAT(lot_otp_trigger_time, '%Y-%m-%d') =", $currentDate)->from('leads_otp_trans')->order_by('lot_lead_id', 'DESC')->limit(1)->get();
            $resultOtp = $queryOtp->row();

            if (isset($resultOtp->lot_otp_verify_flag) && $resultOtp->lot_otp_verify_flag == 1) {
                return json_encode($this->response(['Status' => 2, 'Message' => 'Otp is not valid please resend otp.'], REST_Controller::HTTP_OK));
            }
            if (isset($resultOtp->lot_mobile_otp) && $resultOtp->lot_mobile_otp == $post['otp']) {

                $data = $this->Lead->getLoanRepaymentDetails($resultLead->lead_id);

                $order_id = $this->createRazorPayOrderID($data["repayment_data"]);
                $res = array('Status' => 1, 'Message' => 'Otp verify on your register mobile no. ######' . $last_four_mob, "order_id" => $order_id["order_id"]);

                if ($order_id["Status"] != 1) {
                    return json_encode($this->response(['Status' => 1, 'Message' => 'Order Id not created'], REST_Controller::HTTP_OK));
                }

                $res['data'] = $data;

                return json_encode($this->response($res, REST_Controller::HTTP_OK));
            } else {
                return json_encode($this->response(['Status' => 2, 'Message' => 'Enter Wrong Otp.'], REST_Controller::HTTP_OK));
            }
        } else {
            return json_encode($this->response(['Status' => 2, 'Message' => 'No Data found.'], REST_Controller::HTTP_OK));
        }
    }

    function createRazorPayOrderID($data) {
        $curl1 = curl_init();
        $rp_amount = round(($data['total_due_amount'] * 100), 2);
        $loan_no = $data['loan_no'];
        $return_data = array();

        curl_setopt_array($curl1, array(
            CURLOPT_URL => 'https://api.razorpay.com/v1/orders',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
              "amount": ' . $rp_amount . ',
              "currency": "INR",
              "receipt": "' . $loan_no . '",
              "notes": {
                  "orderid": "' . $loan_no . '"
              },
              "partial_payment": true,
              "first_payment_min_amount": 100
          }',
            CURLOPT_HTTPHEADER => array(
                'Authorization:  Basic cnpwX2xpdmVfNU9ieGljUGpyZjJseFM6WkdnOUJuejg3ZjVGZFpjZUFTT3pOS0Qw',
                'Content-Type:  application/json'
            ),
        ));

        $response1 = curl_exec($curl1);

        curl_close($curl1);
        $ress = json_decode($response1, true);
        $return_data['Status'] = 1;
        $return_data['order_id'] = isset($ress['id']) ? $ress['id'] : null;
        $return_data['rzp_amount'] = $rp_amount;
        return $return_data;
    }
}
