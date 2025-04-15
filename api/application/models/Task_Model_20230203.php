<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Task_Model extends CI_Model {

    function __construct() {

        parent::__construct();

        define("ip", $this->input->ip_address());

        date_default_timezone_set('Asia/Kolkata');

        define("todayDate", date('Y-m-d'));

        define("tableLeads", "leads");

        define("currentDate", date('Y-m-d'));

        define("created_at", date('Y-m-d H:i:s'));

        define("updated_at", date('Y-m-d H:i:s'));

        define("server", $_SERVER['SERVER_NAME']);

        define("localhost", "public/images/");

        define("live", base_url() . "upload/");

        /////////// define role ///////////////////////////////////////



        define('screener', "SANCTION QUICKCALLER");

        define('creditManager', "Sanction & Telecaller");

        define('headCreditManager', "Sanction Head");

        define('admin', "Client Admin");

        define('teamDisbursal', "Disbursal");

        define('teamClosure', "Account and MIS");

        define('teamCollection', "Collection");
    }

    private $table = 'leads';
    private $table_state = 'tbl_state';
    private $table_disburse = 'tbl_disburse';

    public function selectdata($conditions = null, $data = null, $table = null) {

        return $this->db->select($data)->where($conditions)->from($table)->get();
    }

    public function insert($table = null, $data = null) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function update($conditions = null, $table = null, $data = null) {
        return $this->db->where($conditions)->update($table, $data);
    }

    public function gettotalleadsCount($table) {

        $sql = "Select count(lead_id) as total from $table where application_no != null and application_no !=''";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {

                return $row['total'];
            }
        } else {

            return "0";
        }
    }

    public function getProductCode($product_id) {

        $sql = "SELECT product_code FROM tbl_product WHERE product_id='$product_id'";

        $data1 = $this->db->query($sql);

        return $data1->result_array();
    }

    public function getAllDataFromPincode($pincode) {

        $sql = "select mc.m_city_id as city_id,mc.m_city_name as city_name,ms.m_state_id as state_id,ms.m_state_name as state_name from master_pincode mp inner join master_city mc on mp.m_pincode_city_id=mc.m_city_id inner join master_state ms on mc.m_city_state_id=ms.m_state_id  where mp.m_pincode_value='$pincode'";

        $data1 = $this->db->query($sql);

        return $data1->result_array();
    }

    public function CheckUserStatus($lead_id) {

        $sql = "SELECT status FROM leads WHERE lead_id='$product_id'";

        $data1 = $this->db->query($sql);

        return $data1->result_array();
    }

    public function generateReferenceCode($lead_id, $first_name, $last_name, $mobile) {

        $code_mix = array($lead_id[rand(0, strlen($lead_id) - 1)], $first_name[rand(0, strlen($first_name) - 1)], $first_name[rand(0, strlen($first_name) - 1)], $last_name[rand(0, strlen($last_name) - 1)], $last_name[rand(0, strlen($last_name) - 1)], $mobile[rand(0, strlen($mobile) - 1)], $mobile[rand(0, strlen($mobile) - 1)]);

        shuffle($code_mix);

        $referenceID = "#BL";

        foreach ($code_mix as $each) {

            $referenceID .= $each;
        }

        $referenceID = str_replace(" ", "X", $referenceID);

        $referenceID = strtoupper($referenceID);

        return $referenceID;
    }

    //get single data from db



    public function getcustId($table, $column, $id, $getval) {

        $id = strtoupper($id);

        //'master_state','m_state_id',$sql['cif_residence_state_id'],'m_state_name')
        //echo  "============= SELECT $getval from $table where $column='$id' ";          

        $query = $this->db->query("SELECT $getval from $table where $column='$id'  ");

        if ($query->num_rows() > 0) {

            foreach ($query->result_array() as $row) {

                //echo "<pre>";print_r($row);

                return $row[$getval];
            }
        } else {

            return "0";
        }
    }

    public function sendOTPForUserRegistrationVerification($data) {

        $mobile = $data['mobile'];

        $otp = $data['otp'];

        $name = !empty($data['name']) ? $data['name'] : "User";

        $message = "Dear Mr/Ms $name,\nYour mobile verification\nOTP is: " . $otp . ".\nPlease don't share it with anyone - LW (Naman Finlease)";

        $username = urlencode("namanfinl");

        $password = urlencode("ASX1@#SD");

        $type = 0;

        $dlr = 1;

        $destination = $mobile;

        $source = "LWAPLY";

        $message = urlencode($message);

        $entityid = 1201159134511282286;

        $tempid = 1207161976462053311;

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

        curl_close($ch);
    }

    public function sendOTPAppliedSuccessfully($data) {
        $title = $data['title'];
        $name = $data['name'];
        $mobile = $data['mobile'];
        $message = "Dear " . $title . " " . $name . ",\nYour loan application is\nsuccessfully submitted.\nWe will get back to you soon.\n- Loanwalle (Naman Finlease)";
        $username = urlencode("namanfinl");
        $password = urlencode("ASX1@#SD");
        $type = 0;
        $dlr = 1;
        $destination = $mobile;
        $source = "LWALLE";
        $message = urlencode($message);
        $entityid = 1201159134511282286;
        $tempid = 1207161976525243363;
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
        curl_close($ch);
    }

    public function sendOTPAppliedSuccessfully1($data) {
        $title = $data['title'];
        $name = $data['name'];
        $mobile = $data['mobile'];
        $message = "Dear " . $title . " " . $name . ",\nYour loan application is\nsuccessfully submitted.\nWe will get back to you soon.\n- Loanwalle (Naman Finlease)";
        $username = urlencode("namanfinl");
        $password = urlencode("ASX1@#SD");
        $type = 0;
        $dlr = 1;
        $destination = $mobile;
        $source = "LWALLE";
        $message = urlencode($message);
        $entityid = 1201159134511282286;
        $tempid = 1207161976525243363;
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
        curl_close($ch);
    }

    public function common_parse_full_name($full_name = "") {
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

    public function insertApplicationLog($lead_id, $lead_status_id, $remark) {

        if (empty($lead_id) || empty($lead_status_id) || empty($remark)) {
            return null;
        }

        $user_id = 0;

        if (isset($_SESSION['isUserSession']['user_id']) && !empty($_SESSION['isUserSession']['user_id'])) {
            $user_id = $_SESSION['isUserSession']['user_id'];
        }

        $insert_log_array = array();
        $insert_log_array['lead_id'] = $lead_id;
        $insert_log_array['user_id'] = $user_id;
        $insert_log_array['lead_followup_status_id'] = $lead_status_id;
        $insert_log_array['remarks'] = addslashes($remark);
        $insert_log_array['created_on'] = date("Y-m-d H:i:s");

        return $this->db->insert('lead_followup', $insert_log_array);
    }

    public function get_lead_details($lead_id) {
        $result_array = array("status" => 0);

        $conditions['LD.lead_id'] = $lead_id;

        $this->db->select('LD.*, LC.first_name, LC.middle_name, LC.sur_name, L.loan_noc_letter_sent_datetime');
        $this->db->from('leads LD');
        $this->db->join('lead_customer LC', "LC.customer_lead_id = LD.lead_id");
        $this->db->join('loan L', "L.lead_id = LD.lead_id");

        if (!empty($lead_id)) {
            $this->db->where($conditions);
        } else {
            return [];
        }

        $tempDetails = $this->db->get();

        if (!empty($tempDetails->num_rows())) {
            $row = $tempDetails->row_array();
            $result_array['status'] = 1;

            $data['lead_id'] = $this->encrypt->encode($row['lead_id']);
            $data['full_name'] = trim($row['first_name'] . " " . $row['middle_name'] . " " . $row['sur_name']);
            $data['mobile'] = $row['mobile'];
            $data['email'] = $row['email'];
            $data['status'] = $row['status'];
            $data['lead_status_id'] = $row['lead_status_id'];
            $data['feedback_link_sent_on'] = $row['loan_noc_letter_sent_datetime'];

            $result_array['data']['lead_details'] = $data;
        }

        $master_feedback_questions = $this->get_master_feedback_questions();
        $result_array['data']['master_feedback_questions'] = $master_feedback_questions['data']['master_feedback_questions'];

        $master_feedback_answers = $this->get_master_feedback_answers();
        $result_array['data']['master_feedback_answers'] = $master_feedback_answers['data']['master_feedback_answers'];

        $customer_feedback = $this->get_customer_feedback_main($lead_id);
        $result_array['data']['customer_feedback'] = $customer_feedback['data']['customer_feedback'];

        return $result_array;
    }

    public function get_master_feedback_questions() {
        $result_array = array("status" => 0);

        $conditions['MFQ.mfq_active'] = 1;
        $conditions['MFQ.mfq_deleted'] = 0;

        $this->db->select('MFQ.mfq_id, MFQ.mfq_question');
        $this->db->from('master_feedback_questions MFQ');
        $this->db->where($conditions);

        $tempDetails = $this->db->get();

        if (!empty($tempDetails->num_rows())) {
            $data_array = array();
            $result_array['status'] = 1;
            foreach ($tempDetails->result_array() as $row) {
                $data['question_id'] = $row['mfq_id'];
                $data['question'] = $row['mfq_question'];

                $data_array[] = $data;
            }

            $result_array['data']['master_feedback_questions'] = $data_array;
        }

        return $result_array;
    }

    public function get_master_feedback_answers() {
        $result_array = array("status" => 0);

        $conditions['MFA.mfa_active'] = 1;
        $conditions['MFA.mfa_deleted'] = 0;

        $this->db->select('MFA.mfa_id, MFA.mfa_answer, MFA.mfa_icons');
        $this->db->from('master_feedback_answers MFA');
        $this->db->where($conditions);

        $tempDetails = $this->db->get();

        if (!empty($tempDetails->num_rows())) {
            $data_array = array();
            $result_array['status'] = 1;
            foreach ($tempDetails->result_array() as $row) {
                $data['answer_id'] = $row['mfa_id'];
                $data['answer'] = $row['mfa_answer'];
                $data['icons'] = $row['mfa_icons'];

                $data_array[] = $data;
            }

            $result_array['data']['master_feedback_answers'] = $data_array;
        }

        return $result_array;
    }

    public function get_customer_feedback_main($lead_id) {
        $result_array = array("status" => 0);

        $conditions['CFM.cfm_active'] = 1;
        $conditions['CFM.cfm_deleted'] = 0;
        $conditions['CFM.cfm_lead_id'] = $lead_id;

        $this->db->select('CFM.cfm_id, CFM.cfm_lead_id, CFM.cfm_created_on');
        $this->db->from('customer_feedback_main CFM');
        $this->db->where($conditions);

        $tempDetails = $this->db->get();

        if (!empty($tempDetails->num_rows())) {
            $result_array['status'] = 1;
            $row = $tempDetails->row_array();

            $data['cfm_id'] = $row['cfm_id'];
            $data['lead_id'] = $row['cfm_lead_id'];
            $data['created_on'] = $row['cfm_created_on'];

            $result_array['data']['customer_feedback'] = $data;
        }

        return $result_array;
    }

    public function get_repeat_customer_details($lead_id) {
        $result_array = array('status' => 0);

        $conditions['LD.lead_id'] = $lead_id;
        $conditions['LD.lead_active'] = 1;
        $conditions['LD.lead_deleted'] = 0;

        $this->db->select('LD.*, LC.first_name, LC.middle_name, LC.sur_name, L.loan_closure_date');
        $this->db->from('leads LD');
        $this->db->join('lead_customer LC', "LC.customer_lead_id = LD.lead_id AND LC.customer_active = 1 AND LC.customer_deleted = 0");
        $this->db->join('loan L', 'L.lead_id=LD.lead_id');

        if (!empty($lead_id)) {
            $this->db->where($conditions);
        }

        $tempDetails = $this->db->get();

        if (!empty($tempDetails->num_rows())) {
            $result_array['status'] = 1;
            $result_array['data']['lead_details'] = $tempDetails->row_array();
        }

        $lead_customer = $this->get_lead_customer_details($lead_id);
        $result_array['data']['lead_customer_details'] = $lead_customer['data']['lead_customer_details'];

        $customer_employment = $this->get_customer_employment_details($lead_id);
        $result_array['data']['customer_employment_details'] = $customer_employment['data']['customer_employment_details'];

        $customer_banking = $this->get_customer_banking_details($lead_id);
        $result_array['data']['customer_banking_details'] = $customer_banking['data']['customer_banking_details'];

        $customer_reference = $this->get_customer_reference_details($lead_id);
        $result_array['data']['customer_reference_details'] = $customer_reference['data']['customer_reference_details'];

        $cam = $this->get_cam_details($lead_id);
        $result_array['data']['cam_details'] = $cam['data']['cam_details'];

        return $result_array;
    }

    public function get_lead_customer_details($lead_id) {
        $result_array = array('status' => 0);

        $conditions['LD.lead_id'] = $lead_id;
        $conditions['LD.lead_active'] = 1;
        $conditions['LD.lead_deleted'] = 0;

        $this->db->select('LC.*');
        $this->db->from('leads LD');
        $this->db->join('lead_customer LC', "LC.customer_lead_id = LD.lead_id AND LC.customer_active = 1 AND LC.customer_deleted = 0");

        if (!empty($lead_id)) {
            $this->db->where($conditions);
        }

        $tempDetails = $this->db->get();

        if (!empty($tempDetails->num_rows())) {
            $result_array['status'] = 1;
            $result_array['data']['lead_customer_details'] = $tempDetails->row_array();
        }

        return $result_array;
    }

    public function get_customer_employment_details($lead_id) {
        $result_array = array('status' => 0);

        $conditions['LD.lead_id'] = $lead_id;
        $conditions['LD.lead_active'] = 1;
        $conditions['LD.lead_deleted'] = 0;

        $this->db->select('CE.*');
        $this->db->from('leads LD');
        $this->db->join('customer_employment CE', "CE.lead_id = LD.lead_id AND CE.emp_active = 1 AND CE.emp_deleted = 0");

        if (!empty($lead_id)) {
            $this->db->where($conditions);
        }

        $tempDetails = $this->db->get();

        if (!empty($tempDetails->num_rows())) {
            $result_array['status'] = 1;
            $result_array['data']['customer_employment_details'] = $tempDetails->row_array();
        }

        return $result_array;
    }

    public function get_customer_banking_details($lead_id) {
        $result_array = array('status' => 0);

        $conditions['LD.lead_id'] = $lead_id;
        $conditions['LD.lead_active'] = 1;
        $conditions['LD.lead_deleted'] = 0;
        $conditions['CB.account_status_id'] = 1;

        $this->db->select('CB.*');
        $this->db->from('leads LD');
        $this->db->join('customer_banking CB', "CB.lead_id = LD.lead_id AND CB.customer_banking_active = 1 AND CB.customer_banking_deleted = 0");

        if (!empty($lead_id)) {
            $this->db->where($conditions);
        }

        $tempDetails = $this->db->get();

        if (!empty($tempDetails->num_rows())) {
            $result_array['status'] = 1;
            $result_array['data']['customer_banking_details'] = $tempDetails->row_array();
        }

        return $result_array;
    }

    public function get_cam_details($lead_id) {
        $result_array = array('status' => 0);

        $conditions['LD.lead_id'] = $lead_id;
        $conditions['LD.lead_active'] = 1;
        $conditions['LD.lead_deleted'] = 0;

        $this->db->select('CAM.*');
        $this->db->from('leads LD');
        $this->db->join('credit_analysis_memo CAM', "CAM.lead_id = LD.lead_id AND CAM.cam_active = 1 AND CAM.cam_deleted = 0");

        if (!empty($lead_id)) {
            $this->db->where($conditions);
        }

        $tempDetails = $this->db->get();

        if (!empty($tempDetails->num_rows())) {
            $result_array['status'] = 1;
            $result_array['data']['cam_details'] = $tempDetails->row_array();
        }

        return $result_array;
    }

    public function get_customer_reference_details($lead_id) {
        $result_array = array('status' => 0);

        $conditions['LD.lead_id'] = $lead_id;
        $conditions['LD.lead_active'] = 1;
        $conditions['LD.lead_deleted'] = 0;

        $this->db->select('LCR.*');
        $this->db->from('leads LD');
        $this->db->join('lead_customer_references LCR', "LCR.lcr_lead_id = LD.lead_id AND LCR.lcr_active = 1 AND LCR.lcr_deleted = 0");

        if (!empty($lead_id)) {
            $this->db->where($conditions);
        }

        $tempDetails = $this->db->get();

        if (!empty($tempDetails->num_rows())) {
            $result_array['status'] = 1;
            $result_array['data']['customer_reference_details'] = $tempDetails->result_array();
        }

        return $result_array;
    }

}

?>
