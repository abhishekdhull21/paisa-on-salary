<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/models/CronJobs/CronCommon_Model.php';

class CronCollection_Model extends CronCommon_Model {

//    public function insert($table, $data) {
//        $this->db->insert($table, $data);
//        return $this->db->insert_id();
//    }
//
//    public function update($table, $conditions, $data) {
//        return $this->db->where($conditions)->update($table, $data);
//    }

    public function emaillog_insert($data) {
        return $this->db->insert('api_email_logs', $data);
    }

    public function getAllDefaulterCollectionApps($defaulter_start_day = 0, $defaulter_days = 0) {

        $current_date = date("Y-m-d");

        $defaulter_end_date = date("Y-m-d", strtotime("-$defaulter_start_day day", strtotime($current_date)));

        $defaulter_start_date = date("Y-m-d", strtotime("-$defaulter_days day", strtotime($defaulter_end_date)));

        $return_apps_array = array();

        $sql = "SELECT LD.lead_id, CONCAT_WS(' ',LC.first_name, LC.middle_name, LC.sur_name) as cust_full_name, LC.first_name, LC.middle_name, LC.sur_name";
        $sql .= " , LC.email,LC.alternate_email, LC.mobile, LC.alternate_mobile, L.loan_no, L.recommended_amount, CAM.loan_recommended, CAM.roi, CAM.tenure, CAM.repayment_date, CAM.disbursal_date, CAM.repayment_amount";
        $sql .= " , L.loan_id";
        $sql .= " FROM leads LD";
        $sql .= " INNER JOIN lead_customer LC ON(LD.lead_id = LC.customer_lead_id AND LC.customer_active=1 AND LC.customer_deleted=0)";
        $sql .= " INNER JOIN credit_analysis_memo CAM ON(LD.lead_id = CAM.lead_id AND CAM.cam_active=1 AND CAM.cam_deleted=0)";
        $sql .= " INNER JOIN loan L ON(L.lead_id = LD.lead_id AND L.loan_active=1 AND L.loan_deleted=0)";
        $sql .= " WHERE LD.lead_status_id IN(14,19) AND LD.lead_data_source_id!=21 AND CAM.repayment_date > '$defaulter_start_date' AND CAM.repayment_date <= '$defaulter_end_date'";
//        echo $sql;
        $tempDetails = $this->db->query($sql);

        if ($tempDetails->num_rows() > 0) {
            $return_apps_array = $tempDetails->result_array();
        }

        return $return_apps_array;
    }

    public function getAllLoansApps() {

        $return_apps_array = array();

        $sql = "SELECT LD.lead_id, L.loan_no";
        $sql .= " FROM leads LD";
        $sql .= " INNER JOIN lead_customer LC ON(LD.lead_id = LC.customer_lead_id AND LC.customer_active=1 AND LC.customer_deleted=0)";
        $sql .= " INNER JOIN credit_analysis_memo CAM ON(LD.lead_id = CAM.lead_id AND CAM.cam_active=1 AND CAM.cam_deleted=0)";
        $sql .= " INNER JOIN loan L ON(L.lead_id = LD.lead_id AND L.loan_active=1 AND L.loan_deleted=0)";
        $sql .= " WHERE LD.lead_status_id IN(14, 16, 17, 18, 19) ORDER BY LD.lead_id ASC";

        $tempDetails = $this->db->query($sql);

        if ($tempDetails->num_rows() > 0) {
            $return_apps_array = $tempDetails->result_array();
        }

        return $return_apps_array;
    }

    public function getAllOpenCaseLoansApps() {

        $return_apps_array = array();

        $sql = "SELECT LD.lead_id, L.loan_no,CAM.disbursal_date,CAM.repayment_date, CURDATE() as currentdate, DATE_ADD(CAM.repayment_date, INTERVAL 60 DAY) as plust_repay_60day";
        $sql .= " FROM leads LD";
        $sql .= " INNER JOIN lead_customer LC ON(LD.lead_id = LC.customer_lead_id AND LC.customer_active=1 AND LC.customer_deleted=0)";
        $sql .= " INNER JOIN credit_analysis_memo CAM ON(LD.lead_id = CAM.lead_id AND CAM.cam_active=1 AND CAM.cam_deleted=0)";
        $sql .= " INNER JOIN loan L ON(L.lead_id = LD.lead_id AND L.loan_active=1 AND L.loan_deleted=0)";
        $sql .= " WHERE LD.lead_status_id IN(14, 19) AND DATE_ADD(CAM.repayment_date, INTERVAL 60 DAY) >= CURDATE()";
        $sql .= " ORDER BY CAM.repayment_date ASC";

        $tempDetails = $this->db->query($sql);

        if ($tempDetails->num_rows() > 0) {
            $return_apps_array = $tempDetails->result_array();
        }

        return $return_apps_array;
    }

}

?>
