<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Performance_Model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
    }

    public function sanction_popup_model($user_id, $type_id) {

        $return_data = array('status' => 0, 'data' => array(), 'message' => '');

        $current_date = date('Y-m-01');
        $to_date = date('Y-m-d');

        $query = "SELECT UTA.uta_user_id, UTA.uta_user_target_amount, UTA.uta_user_target_cases, (SELECT SUM(CAM.loan_recommended) FROM leads LD INNER JOIN credit_analysis_memo CAM ON(LD.lead_id=CAM.lead_id) WHERE LD.lead_credit_assign_user_id=UTA.uta_user_id AND DATE_FORMAT(CAM.disbursal_date, '%M-%y') = DATE_FORMAT(NOW(), '%M-%y') AND LD.lead_id=CAM.lead_id AND LD.lead_status_id IN(14, 16, 17, 19)) as uta_user_achieve_amount, ";
        $query .= " (SELECT COUNT(LD.lead_id) FROM leads LD INNER JOIN credit_analysis_memo CAM ON(LD.lead_id=CAM.lead_id) WHERE LD.lead_credit_assign_user_id=UTA.uta_user_id AND DATE_FORMAT(CAM.disbursal_date, '%M-%y') = DATE_FORMAT(NOW(), '%M-%y') AND LD.lead_id=CAM.lead_id AND LD.lead_status_id IN(14, 16, 17, 19)) as uta_user_achieve_cases ";
        $query .= " FROM user_target_allocation_log UTA INNER JOIN users U ON(UTA.uta_user_id=U.user_id) INNER JOIN user_roles UR ON(UTA.uta_user_id=UR.user_role_user_id) ";
        $query .= " WHERE U.user_status_id=1  AND UR.user_role_type_id=3 AND UTA.uta_type_id=1 AND DATE_FORMAT(UTA.uta_created_on, '%M-%y') = DATE_FORMAT(NOW(), '%M-%y') AND UTA.uta_active=1 AND UTA.uta_deleted=0 AND UTA.uta_user_id=$user_id";

        $monthly_data = $this->db->query($query)->row_array();

        if ($type_id == 1) {
            $query1 = "SELECT COUNT(LD.lead_id) as total_cases, SUM(CAM.loan_recommended) as loan_amount, LD.lead_credit_assign_user_id ";
            $query1 .= "FROM leads LD INNER JOIN credit_analysis_memo CAM ON(LD.lead_id=CAM.lead_id) INNER JOIN loan L ON(LD.lead_id=L.lead_id) ";
            $query1 .= "WHERE LD.lead_active=1 AND LD.lead_status_id IN(14, 16, 17, 18, 19) AND LD.lead_credit_assign_user_id='$user_id' AND CAM.disbursal_date = '$to_date'";

            $daily_data = $this->db->query($query1)->row_array();

            $query1 = "SELECT COUNT(LD.lead_id) as total_cases, SUM(CAM.loan_recommended) as loan_amount, LD.lead_credit_assign_user_id ";
            $query1 .= "FROM leads LD INNER JOIN credit_analysis_memo CAM ON(LD.lead_id=CAM.lead_id) INNER JOIN loan L ON(LD.lead_id=L.lead_id) ";
            $query1 .= "WHERE LD.lead_active=1 AND LD.lead_status_id IN(12, 13, 25, 30, 35, 37, 14, 16, 17, 18, 19) AND LD.lead_credit_assign_user_id='$user_id' AND CAM.disbursal_date = '$to_date'";

            $daily_sanction_data = $this->db->query($query1)->row_array();
        } elseif ($type_id == 2) {

            $query2 = "SELECT * FROM (SELECT (SELECT COUNT(LCR.lcf_lead_id) FROM loan_collection_followup LCR WHERE LCR.lcf_user_id='$user_id' AND LCR.lcf_type_id=1 AND LCR.lcf_active=1 AND DATE(LCR.lcf_created_on) = DATE(NOW())) as counts, ";
            $query2 .= "(SELECT SUM(COL.received_amount) FROM collection COL WHERE COL.payment_verification=1 AND COL.collection_active=1 AND DATE(COL.date_of_recived) = DATE(NOW()) AND COL.collection_executive_user_id='$user_id') as collected_amount) AS report";

            $daily_collection_data = $this->db->query($query2)->row_array();
        }

        if (!empty($monthly_data) || !empty($daily_data) && !empty($daily_collection_data)) {
            $return_data['data']['today_disburse_cases'] = ($daily_data['total_cases'] > 0 ? $daily_data['total_cases'] : 0);
            $return_data['data']['today_disburse_amount'] = ($daily_data['loan_amount'] > 0 ? $daily_data['loan_amount'] : 0);
            $return_data['data']['today_sanction_cases'] = ($daily_sanction_data['total_cases'] > 0 ? $daily_sanction_data['total_cases'] : 0);
            $return_data['data']['today_sanction_amount'] = ($daily_sanction_data['loan_amount'] > 0 ? $daily_sanction_data['loan_amount'] : 0);
            $return_data['data']['today_followup_cases'] = ($daily_collection_data['counts'] > 0 ? $daily_collection_data['counts'] : 0);
            $return_data['data']['today_collection_amount'] = ($daily_collection_data['collected_amount'] > 0 ? $daily_collection_data['collected_amount'] : 0);
            $return_data['data']['monthly_achieve_cases'] = $monthly_data['uta_user_achieve_cases'];
            $return_data['data']['monthly_sanction_achieve_amount'] = $monthly_data['uta_user_achieve_amount'];
            $return_data['data']['monthly_target_cases'] = $monthly_data['uta_user_target_cases'];
            $return_data['data']['monthly_target_amount'] = $monthly_data['uta_user_target_amount'];

//            $return_data['data']['uta_user_loan_total_cases'] = $monthly_data['uta_user_loan_total_cases'];
//            $return_data['data']['uta_user_loan_closed_cases'] = $monthly_data['uta_user_loan_closed_cases'];
//            $return_data['data']['uta_user_loan_total_principle'] = $monthly_data['uta_user_loan_total_principle'];
//            $return_data['data']['uta_user_loan_principle_received'] = $monthly_data['uta_user_loan_principle_received'];
//            $return_data['data']['uta_user_target_followups'] = $monthly_data['uta_user_target_followups'];
//            $return_data['data']['uta_user_achieve_followups'] = $monthly_data['uta_user_achieve_followups'];
//            $return_data['data']['uta_user_loan_total_received'] = $monthly_data['uta_user_loan_total_received'];

            $return_data['status'] = 1;
        }

        return $return_data;
    }

}

?>
