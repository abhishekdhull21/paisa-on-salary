<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/models/CronJobs/CronCommon_Model.php';

class CronSanction_Model extends CronCommon_Model {

//    public function __construct() {
//        parent::__construct();
//        date_default_timezone_set('Asia/Kolkata');
//    }

    public function emaillog_insert($data) {
        return $this->db->insert('api_email_logs', $data);
    }

    public function get_lead_new_list() {

        $return_array = ['status' => 0];

        $sql = "SELECT LD.lead_id, LD.user_type, LD.email, LD.mobile, LD.first_name";
        $sql .= " FROM leads LD";
        $sql .= " WHERE LD.lead_status_id=1 AND (LD.lead_screener_assign_user_id IS NULL OR LD.lead_screener_assign_user_id=0) AND LD.lead_data_source_id NOT IN(21,27,33) AND LD.utm_source!='repeatnf' ORDER BY LD.lead_id ASC";

        $tempDetails = $this->db->query($sql);

        if (!empty($tempDetails->num_rows())) {
            $return_array['status'] = 1;
            $return_array['data'] = $tempDetails->result_array();
        }

        return $return_array;
    }

    public function get_users_lead_list() {
        $current_date = date("Y-m-d");
        $return_array = ['status' => 0];

        $sql = "SELECT U.user_id, U.name, U.email, U.mobile, SUM(IF(LD.lead_id > 0,1,0)) as total_leads, ";
        $sql .= " (SELECT IF(LAA.ula_user_status=1,1,0) FROM user_lead_allocation_log LAA WHERE LAA.ula_user_id=U.user_id AND DATE(LAA.ula_created_on)='$current_date' AND LAA.ula_active=1 ORDER BY LAA.ula_id DESC LIMIT 1) as user_active_flag,";
        $sql .= " (SELECT IF(LFR.ula_user_case_type>0,LFR.ula_user_case_type,0) FROM user_lead_allocation_log LFR WHERE LFR.ula_user_id=U.user_id AND DATE(LFR.ula_created_on)='$current_date' AND LFR.ula_user_status=1 AND LFR.ula_active=1 ORDER BY LFR.ula_id DESC LIMIT 1) as user_active_case_type";
        $sql .= " FROM users U INNER JOIN user_roles UR ON(U.user_id=UR.user_role_user_id AND UR.user_role_type_id=2)";
        $sql .= " LEFT JOIN leads LD ON(LD.lead_screener_assign_user_id>0 AND LD.lead_screener_assign_user_id=U.user_id AND LD.lead_status_id IN(2,3))";
        $sql .= " WHERE U.user_id=UR.user_role_user_id AND UR.user_role_type_id=2 AND U.user_is_loanwalle=0";
        $sql .= " AND U.user_active=1 AND U.user_status_id=1 AND UR.user_role_active=1";
        $sql .= " GROUP BY U.user_id ORDER BY total_leads ASC";
//        echo "<br/>" . $sql;
        $tempDetails = $this->db->query($sql);

        if (!empty($tempDetails->num_rows())) {
            $return_array['status'] = 1;
            $return_array['data'] = $tempDetails->result_array();
        }

        return $return_array;
    }

}

?>
