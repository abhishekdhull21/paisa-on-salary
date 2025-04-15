<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CronMiscellaneousController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $this->load->model('CronJobs/CronMiscellaneous_Model', 'MiscellaneousModel');
    }

    public function kycLoanDocs() {
        $start_datetime = date("d-m-Y H:i:s");
        $time_close = intval(date("Hi"));

        if ($time_close > 1743) {
            echo "here";
            die;
        }

        $doc_path = '/home/fintechcloud/public_html/';
        $get_doc_path = '/home/fintechcloud/public_html/upload/';

        $kyc_counter['kyc_success'] = array();

        $request_data = array();

        $total_loans = 0;

        if (true) {

            if (!is_dir($doc_path . 'kyc1/')) {
                mkdir($doc_path . 'kyc1/', 0777, TRUE);
            }

//            $request_data['start_date'] = $start_date;
//            $request_data['end_date'] = $end_date;
//            $tempLoanData = $this->MiscellaneousModel->get_customer_loan($request_data);
            $tempLoanData = $this->MiscellaneousModel->get_customer_loan();
            //traceObject($tempLoanData);
            $counter = 0;
            if (!empty($tempLoanData['status'])) {

                $document_push_array = array();

                $total_loans = count($tempLoanData['loan']);

                foreach ($tempLoanData['loan'] as $row) {

                    $loan_no = $row['loan_no'];

                    if (empty($loan_no)) {
                        continue;
                    }
                    $document_push_array[$loan_no] = array();

                    $request_data['lead_id'] = $row['lead_id'];
                    $request_data['loan_no'] = $row['loan_no'];
                    $request_data['pancard'] = $row['pancard'];

                    $tempLoanKycDocs = $this->MiscellaneousModel->get_loans_kyc_documents($request_data);

                    if (!is_dir($doc_path . 'kyc1/' . $loan_no . '/')) {
                        mkdir($doc_path . 'kyc1/' . $loan_no . '/', 0777, TRUE);
                    }

                    if (!empty($tempLoanKycDocs['status'])) {

                        $update_kyc_loan_flag = 0;

                        foreach ($tempLoanKycDocs['docs'] as $docs) {
                            $docs_sub_type = preg_replace("!\s+!", "", $docs['sub_docs_type']);
                            $docs_sub_type = trim($docs_sub_type);
                            $docs_sub_type = str_replace(array(" ", "-", ":", "(", ")", ".", "'"), '_', $docs_sub_type);
                            $docs_sub_type = strtoupper($docs_sub_type);

                            if (empty($document_push_array[$loan_no][$docs_sub_type]) && !empty($docs['file'])) {

                                $ext = pathinfo($docs['file'], PATHINFO_EXTENSION);

                                $document_name = $docs_sub_type . '.' . $ext;

                                $image_upload_dir = $doc_path . 'kyc1/' . $loan_no . '/' . $document_name;

                                if (file_exists($get_doc_path . $docs['file'])) {

                                    $flag = file_put_contents($image_upload_dir, file_get_contents($get_doc_path . $docs['file']));

                                    if ($flag) {
                                        $document_push_array[$loan_no][$docs_sub_type] = $docs['docs_id'];
                                        $kyc_counter['kyc_success'][$loan_no][$docs_sub_type] = $document_name;
                                        $update_kyc_loan_flag = 1;
                                    }
                                }
                            }
                        }

                        if ($update_kyc_loan_flag == 1) {
                            $counter++;
                            $this->MiscellaneousModel->update('test_kyc_loan', ['kyc_loan_no' => $loan_no], ['kyc_loan_done' => 1, 'kyc_loan_done_datetime' => date("Y-m-d H:i:s")]);
                        }
                    }
                }
            }
        }

        $email = "shubham.agrawal@loanwalle.com";
        $subject = "PROD-KYC DOCS - start time :" . $start_datetime . " | end time : " . date("d-m-Y H:i:s");
        $message = "total loans : $total_loans | kyc_success = " . $counter;

        lw_send_email($email, $subject, $message);
    }

    public function aadhaarMasked() {

        $lead_remarks = '';

        $time_close = intval(date("Hi"));

        if ($time_close > 1742) {
            echo "here";
            die;
        }

        require_once (COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $disbursal_start_date = "2022-05-25";
        $disbursal_end_date = "2022-06-15";

        $tempDetails = $this->MiscellaneousModel->get_loan_list($disbursal_start_date, $disbursal_end_date);

        $start_datetime = date("d-m-Y H:i:s");

        $masked_counter = array('masked_success' => 0, 'masked_failed' => 0);

        $api_call_doc_ids = array();

        if (!empty($tempDetails)) {

            foreach ($tempDetails['loan'] as $customer_data) {

                if (empty($api_call_doc_ids[$customer_data['docs_id']]) && !empty($customer_data['lead_id']) && !empty($customer_data['docs_id']) && !empty($customer_data['docs_master_id'])) {

                    $api_call_doc_ids[$customer_data['docs_id']] = $customer_data['docs_id'];

                    $masked_aadhaar_return = $CommonComponent->call_aadhaar_masked_api($customer_data['lead_id'], $customer_data['docs_master_id'], $customer_data['docs_id']);

                    if ($masked_aadhaar_return['status'] == 5) {
                        $masked_counter['masked_success']++;
                    } else {
                        $masked_counter['masked_failed']++;
                    }
                }
            }
        }


        $email = "shubham.agrawal@loanwalle.com";
        $subject = "PROD-AADHAAR MASKED - start time :" . $start_datetime . " | end time : " . date("d-m-Y H:i:s");
        $message = "masked_success=" . $masked_counter['masked_success'] . " | masked_failed=" . $masked_counter['masked_failed'];

        lw_send_email($email, $subject, $message);
    }

    public function aadhaarMaskedAllCases() {

        $get_file_data='';

        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $cron_name = "aadhaarMaskedAllCases";

        $current_datetime = date('Y-m-d H:i:s', strtotime('-30 minutes', strtotime(date("Y-m-d H:i:s"))));
        $check_datetime = date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime(date("Y-m-d H:i:s"))));

        // $tempDetails = $this->MiscellaneousModel->get_cron_logs($cron_name, $current_datetime, $check_datetime);

        // if (!empty($tempDetails['status'])) {
        //     echo "Already Cron in prcoess";
        //     die;
        // }

        // $cron_insert_id = $this->MiscellaneousModel->insert_cron_logs($cron_name);

        require_once (COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $tempDetails = $this->MiscellaneousModel->get_aadhaar_docs();

        $start_datetime = date("d-m-Y H:i:s");

        $masked_counter = array('masked_success' => 0, 'masked_failed' => 0);

        $api_call_doc_ids = array();

        echo "<pre>";

       if (!empty($tempDetails['status'])) {

            foreach ($tempDetails['docs'] as $customer_data) {
                if (empty($api_call_doc_ids[$customer_data['docs_id']]) && !empty($customer_data['lead_id']) && !empty($customer_data['docs_id']) && !empty($customer_data['docs_master_id'])) {

                    $api_call_doc_ids[$customer_data['docs_id']] = $customer_data['docs_id'];

                    $masked_aadhaar_return = $CommonComponent->call_aadhaar_masked_api($customer_data['lead_id'], $customer_data['docs_master_id'], $customer_data['docs_id']);

                    if ($masked_aadhaar_return['status'] == 5) {
                        
                        $lead_remarks = "AADHAAR MASKED API CALL(Success) | AADHAAR NO : ". $masked_aadhaar_return['aadhaar_no'] ." | Customer Name : " . $masked_aadhaar_return['customer_full_name'];
                        
                        $lead_data = $this->MiscellaneousModel->select(['lead_id' => $customer_data['lead_id']], "lead_id, lead_status_id", 'leads');

                        if ($lead_data->num_rows() > 0) {
                            $lead_data = $lead_data->row_array();
                            $lead_id = $lead_data['lead_id'];
                            $lead_status_id = $lead_data['lead_status_id'];
                        }

                        if (!empty($masked_aadhaar_return['aadhaar_masked_url'])) {
                            if (!empty($masked_aadhaar_return['aadhaar_docs_data'])) {
                                
                                $aadhaar_doc_data = $masked_aadhaar_return['aadhaar_docs_data'];

                                $file_basename = basename($masked_aadhaar_return['aadhaar_masked_url']);

                                $file_name = "MASK_" . rand(1000000, 9999999) . $file_basename;
                            }
                            $get_file_data = file_get_contents($masked_aadhaar_return['aadhaar_masked_url']);

                            if (!empty($get_file_data)) {

                                $doc_flag = file_put_contents(UPLOAD_PATH . $file_name, $get_file_data);

                                $tmp_file_ext = pathinfo(UPLOAD_PATH . $file_name, PATHINFO_EXTENSION);

                                $upload_file = uploadDocument(UPLOAD_PATH . $file_name, $lead_id, 2, $tmp_file_ext);

                                if ($upload_file['status'] == 1) {

                                    print_r($upload_file);

                                    $docs_update_array = array();
                                    $docs_update_array['file'] = $upload_file['file_name'];
                                    $docs_update_array['docs_aadhaar_masked'] = 1;

                                    $this->MiscellaneousModel->update('docs', ['lead_id' => $lead_id, 'docs_id' => $customer_data['docs_id']], $docs_update_array);

                                    $lead_remarks .= "<br>Result : Aadhaar masked image stored.";
                                    unlink(UPLOAD_PATH . $file_name);
                                } else {

                                    $lead_remarks .= "<br>Result : Aadhaar masked image not stored.";
                                }
                            } else {

                                $lead_remarks .= "<br>Result : Aadhaar masked image not fetched.";
                            }
                        } else {
                            $lead_remarks .= "<br>Result : Aadhaar masked image URL not fetched.";
                        }
                        $user_id = 0;

                        if (isset($_SESSION['isUserSession']['user_id']) && !empty($_SESSION['isUserSession']['user_id'])) {
                            $user_id = $_SESSION['isUserSession']['user_id'];
                        }

                        $insert_log_array = array();
                        $insert_log_array['lead_id'] = $lead_id;
                        $insert_log_array['user_id'] = $user_id;
                        $insert_log_array['lead_followup_status_id'] = $lead_status_id;
                        $insert_log_array['remarks'] = addslashes($lead_remarks);
                        $insert_log_array['created_on'] = date("Y-m-d H:i:s");
                        $this->MiscellaneousModel->insert($insert_log_array, 'lead_followup');
                        $masked_counter['masked_success']++;
                    } else {
                        $masked_counter['masked_failed']++;
                    }
                }
            }
        }

        // if (!empty($cron_insert_id)) {
        //     $this->MiscellaneousModel->update_cron_logs($cron_insert_id, $masked_counter['masked_success'], $masked_counter['masked_failed']);
        // }

        // $email = "shubham.agrawal@loanwalle.com";
        // $subject = "PROD-$cron_name - start time :" . $start_datetime . " | end time : " . date("d-m-Y H:i:s");
        // $message = "masked_success=" . $masked_counter['masked_success'] . " | masked_failed=" . $masked_counter['masked_failed'];

        //lw_send_email($email, $subject, $message);
    }

    public function renameDocs() {

        $start_datetime = date("d-m-Y H:i:s");

        $time_close = intval(date("Hi"));

        if ($time_close > 1741) {
            echo "here";
            die;
        }

        $final_doc_path = '/home/fintechcloud/public_html/upload/';
        $get_doc_path = '/home/fintechcloud/public_html/advlms/upload/';

        $tempLoanData = $this->MiscellaneousModel->get_all_documents();

        $counter = 0;

        if (!empty($tempLoanData['status'])) {

            $total_loans = count($tempLoanData['docs']);

            foreach ($tempLoanData['docs'] as $row) {

                $docs_id = $row['docs_id'];
                $lead_id = $row['lead_id'];
                $file_name = $row['file'];
                $docs_rename_status = $row['docs_rename_status'];

                if (!empty($docs_rename_status)) {
                    continue;
                }

                if (!empty($file_name)) {

                    $ext = pathinfo($file_name, PATHINFO_EXTENSION);

                    $document_name = $lead_id . '_' . $docs_id . '_adv_' . date("YmodHis") . '_' . rand(1000, 9999) . '.' . strtolower($ext);

                    $image_upload_dir = $final_doc_path . $document_name;

                    if (file_exists($get_doc_path . $file_name)) {

                        $flag = file_put_contents($image_upload_dir, file_get_contents($get_doc_path . $file_name));

                        if ($flag) {

                            $counter++;
                            $this->MiscellaneousModel->update('docs', ['docs_id' => $docs_id], ['docs_rename_status' => 1, 'file' => $document_name]);
                        }
                    }
                }
            }
        }

        $email = "shubham.agrawal@loanwalle.com";
        $subject = "PROD-Advance Salary KYC DOCS - start time :" . $start_datetime . " | end time : " . date("d-m-Y H:i:s");
        echo $message = "total loans : $total_loans | counter = " . $counter;

        lw_send_email($email, $subject, $message);
        die("Done");
    }

    public function renameCollectionDocs() {

        $start_datetime = date("d-m-Y H:i:s");

        $time_close = intval(date("Hi"));

        if ($time_close > 1805) {
            echo "here";
            die;
        }

        $final_doc_path = '/home/fintechcloud/public_html/upload/';
        $get_doc_path = '/home/fintechcloud/public_html/advlms/upload/';

        $tempLoanData = $this->MiscellaneousModel->get_all_collection_documents();

        $counter = 0;

        if (!empty($tempLoanData['status'])) {

            $total_loans = count($tempLoanData['colldocs']);

            foreach ($tempLoanData['colldocs'] as $row) {

                $docs_id = $row['id'];
                $lead_id = $row['lead_id'];
                $file_name = $row['docs'];
                $docs_rename_status = $row['collection_docs_rename_status'];
//
                if (!empty($docs_rename_status)) {
                    continue;
                }

                if (!empty($file_name)) {

                    $ext = pathinfo($file_name, PATHINFO_EXTENSION);

                    $document_name = $lead_id . '_' . $docs_id . '_adv_' . date("YmdHis") . '_' . rand(1000, 9999) . '.' . strtolower($ext);

                    $image_upload_dir = $final_doc_path . $document_name;

                    if (file_exists($get_doc_path . $file_name)) {

                        $flag = file_put_contents($image_upload_dir, file_get_contents($get_doc_path . $file_name));

                        if ($flag) {

                            $counter++;
                            $this->MiscellaneousModel->update('collection', ['id' => $docs_id], ['collection_docs_rename_status' => 1, 'docs' => $document_name]);
                        }
                    }
                }
            }
        }

        $email = "shubham.agrawal@loanwalle.com";
        $subject = "PROD-Advance Collection KYC DOCS - start time :" . $start_datetime . " | end time : " . date("d-m-Y H:i:s");
        echo $message = "total loans : $total_loans | counter = " . $counter;

        lw_send_email($email, $subject, $message);
        die("Done");
    }

    public function getCollectionDocs() {

        $start_datetime = date("d-m-Y H:i:s");

        $time_close = intval(date("Hi"));

        if ($time_close > 1505) {
            echo "here";
            die;
        }

        $final_doc_path = '/home/fintechcloud/public_html/collex_docs/';
        $get_doc_path = '/home/fintechcloud/public_html/upload/';

        $tempLoanData = $this->MiscellaneousModel->get_all_collection_documents();

        $counter = 0;

        if (!empty($tempLoanData['status'])) {

            $total_loans = count($tempLoanData['colldocs']);

            foreach ($tempLoanData['colldocs'] as $row) {

                $docs_id = $row['id'];
                $lead_id = $row['lead_id'];
                $file_name = $row['docs'];
                $loan_no = $row['loan_no'];

                if (!empty($file_name)) {

                    $ext = pathinfo($file_name, PATHINFO_EXTENSION);

                    $document_name = $loan_no . "_" . $lead_id . '_' . $docs_id . '.' . strtolower($ext);

                    $image_upload_dir = $final_doc_path . $document_name;

                    if (file_exists($get_doc_path . $file_name)) {

                        $flag = file_put_contents($image_upload_dir, file_get_contents($get_doc_path . $file_name));

                        if ($flag) {

                            $counter++;
                            $this->MiscellaneousModel->update('collection', ['id' => $docs_id], ['collection_type' => 1]);
                        }
                    }
                }
            }
        }

        $email = "shubham.agrawal@loanwalle.com";
        $subject = "PROD-Collection PAYMENT DOCS - start time :" . $start_datetime . " | end time : " . date("d-m-Y H:i:s");
        echo $message = "total loans : $total_loans | counter = " . $counter;

        lw_send_email($email, $subject, $message);
        die("Done");
    }

    public function updateFatherName() {

        $start_datetime = date("d-m-Y H:i:s");

        $time_close = intval(date("Hi"));

        if ($time_close > 1355) {
            echo "here";
            die;
        }

        $tempDetails = $this->db->query('SELECT poi_veri_id, poi_veri_lead_id, poi_veri_response, poi_veri_proof_no FROM  api_poi_verification_logs WHERE poi_veri_method_id=1 AND poi_veri_api_status_id=1 AND poi_veri_active=1');

        $total_loans = 0;
        $counter = 0;

        if (!empty($tempDetails->num_rows())) {

            $panDetails = $tempDetails->result_array();

            foreach ($panDetails as $pan_data) {

                $total_loans++;
                $lead_id = $pan_data['poi_veri_lead_id'];
                $poi_veri_id = $pan_data['poi_veri_id'];
                $panResponse = json_decode($pan_data['poi_veri_response'], true);
                $father_name = "";

                if (!empty($panResponse)) {

                    $father_name = strtoupper(trim($panResponse['response']['result']['fatherName']));

                    if (!empty($father_name)) {
                        $counter++;
                        $this->MiscellaneousModel->update('lead_customer', ['customer_lead_id' => $lead_id], ['father_name' => $father_name]);
                        $this->MiscellaneousModel->update('api_poi_verification_logs', ['poi_veri_id' => $poi_veri_id], ['poi_veri_father_name' => $father_name]);
                    }
                }
            }
        }

        $email = "shubham.agrawal@loanwalle.com";
        $subject = "PROD-Father name - start time :" . $start_datetime . " | end time : " . date("d-m-Y H:i:s");
        echo $message = "total loans : $total_loans | counter = " . $counter;

        lw_send_email($email, $subject, $message);
        die("Done");
    }

}
