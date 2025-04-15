<?php

defined('BASEPATH') or exit('No direct script access allowed');

class SupportController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Task_Model', 'Tasks');
        $this->load->model('Support_Model', 'support');
        define('created_on', date('Y-m-d H:i:s'));
        set_time_limit(300);
        date_default_timezone_set('Asia/Kolkata');
        ini_set('max_execution_time', 3600);
        ini_set("memory_limit", "1024M");
        $login = new IsLogin();
        $login->index();
    }

    public function eKYCReset() {
        $lead_id = $_GET['lead_id'];
        $agent = $_SESSION['isUserSession']['labels'];

        $data = [
            'ekyc_active' => 0,
            'ekyc_deleted' => 1
        ];

        if (empty($lead_id)) {
            echo "Lead_id Missing.!!!";
            exit;
        }

        if ($agent == 'CA') {
            $this->db->where('ekyc_lead_id', $lead_id)->update('api_ekyc_logs', $data);
            echo "eKYC Reset Successfully.!!!";
        } else {
            echo "Access Denied.!!!";
        }
    }

    public function eSignReset() {
        $lead_id = $_GET['lead_id'];
        $agent = $_SESSION['isUserSession']['labels'];

        $data = [
            'esign_active' => 0,
            'esign_deleted' => 1
        ];

        if (empty($lead_id)) {
            echo "Lead_id Missing.!!!";
            exit;
        }

        if ($agent == 'CA') {
            $this->db->where('esign_lead_id', $lead_id)->update('api_esign_logs', $data);
            echo "eSign Reset Successfully.!!!";
        } else {
            echo "Access Denied.!!!";
        }
    }

    public function personalDetails() {
        $this->load->view('Support/personalDetails');
    }
    
    public function employmentDetails() {
        $this->load->view('Support/employmentDetails');
    }
    
    public function referenceDetails() {
        $this->load->view('Support/referenceDetails');
    }
    
    public function docsDetails() {
        $this->load->view('Support/docsDetails');
    }
    
    public function camDetails() {
        $this->load->view('Support/camDetails');
    }
    
    public function bankDetails() {
        $this->load->view('Support/bankDetails');
    }
    
    public function searchBankId() {
        $lead_id = trim($_POST['lead_id']);        
        $return_array = array('status' => 0);        
        $search_data = $this->db->select('CB.*')->from('customer_banking CB')->where('CB.lead_id',$lead_id)->get();       
        if ($search_data->num_rows() > 0) {
            $data['status'] = 1;
            $data['leadInfo'] = $search_data->row_array();
            $data['bank_type_list'] = $this->db->select('m_bank_type_id,m_bank_type_name')->from('master_bank_type')->where('m_bank_type_active',1)->get()->result_array();
            $this->load->view('Support/bankDetails', $data);
        } else { 
            $this->session->set_flashdata('error', 'Record not found.');
            $this->load->view('Support/bankDetails');
        }
    }
    
    public function searchLeadId() {
        $lead_id = trim($_POST['lead_id']);        
        $return_array = array('status' => 0);        
        $search_data = $this->db->select('LD.*,LC.*')->from('leads LD')->join('lead_customer LC', 'LC.customer_lead_id = LD.lead_id ', 'INNER')->where('LD.lead_id', $lead_id)->get();       
        if ($search_data->num_rows() > 0) {
            $data['status'] = 1;
            $data['leadInfo'] = $search_data->row_array();
            $data['religion_list'] = $this->db->select('religion_id,religion_name')->from('master_religion')->where('religion_active',1)->get()->result_array();
            $data['marital_status_list'] = $this->db->select('m_marital_status_id,m_marital_status_name')->from('master_marital_status')->where('m_marital_status_active',1)->get()->result_array();
            $data['occupation_list'] = $this->db->select('m_occupation_id,m_occupation_name')->from('master_occupation')->where('m_occupation_active',1)->get()->result_array();
            $state_id = $data['leadInfo']['current_state'];
            $city_id = $data['leadInfo']['current_city'];
            $data['state_list'] = $this->db->select('m_state_id,m_state_name')->from('master_state')->where('m_state_active',1)->get()->result_array();
            $data['city_list'] = $this->db->select('m_city_id,m_city_name')->from('master_city')->where('m_city_state_id',$state_id)->where('m_city_active',1)->get()->result_array();
            $data['pincode_list'] = $this->db->select('m_pincode_id,m_pincode_value')->from('master_pincode')->where('m_pincode_city_id',$city_id)->where('m_pincode_active',1)->get()->result_array();
            $this->load->view('Support/personalDetails', $data);
        } else { 
            $this->session->set_flashdata('error', 'Record Not found.');
            $this->load->view('Support/personalDetails');
        }
    }
    
    public function searchCamId() {
        $lead_id = trim($_POST['lead_id']);        
        $return_array = array('status' => 0);        
        $search_data = $this->db->select('CAM.*')->from('credit_analysis_memo CAM')->where('CAM.lead_id',$lead_id)->get();       
        if ($search_data->num_rows() > 0) {
            $data['status'] = 1;
            $data['leadInfo'] = $search_data->row_array();
            $this->load->view('Support/camDetails', $data);
        } else { 
            $this->session->set_flashdata('error', 'Record not found.');
            $this->load->view('Support/camDetails');
        }
    }
    
    public function searchEmploymentId() {
        $lead_id = trim($_POST['lead_id']);        
        $return_array = array('status' => 0);        
        $search_data = $this->db->select('*')->from('customer_employment')->where('lead_id',$lead_id)->get();       
        if($search_data->num_rows() > 0) {
            $data['status'] = 1;
            $data['leadInfo'] = $search_data->row_array();
            $state_id = $data['leadInfo']['state_id'];
            $data['state_list'] = $this->db->select('m_state_id,m_state_name')->from('master_state')->where('m_state_active',1)->get()->result_array();
            $data['city_list'] = $this->db->select('m_city_id,m_city_name')->from('master_city')->where('m_city_state_id',$state_id)->where('m_city_active',1)->get()->result_array();
            $data['designation_list'] = $this->db->select('m_designation_id,m_designation_name')->from('master_designation')->where('m_designation_active',1)->get()->result_array();
            $data['department_list'] = $this->db->select('department_id,department_name')->from('master_department')->where('department_active',1)->get()->result_array();
            $data['company_type_list'] = $this->db->select('m_company_type_id,m_company_type_name')->from('master_company_type')->where('m_company_type_active',1)->get()->result_array();
            $data['salary_mode_list'] = $this->db->select('m_salary_mode_id,m_salary_mode_name')->from('master_salary_mode')->where('m_salary_mode_active',1)->get()->result_array();
            $this->load->view('Support/employmentDetails',$data);
        } else { 
            $this->session->set_flashdata('error', 'Record Not found.');
            $this->load->view('Support/employmentDetails');
        }
    }
    
    public function searchReferenceId() {
        $lead_id = trim($_POST['lead_id']);        
        $return_array = array('status' => 0);        
        $search_data = $this->db->select('LR.*,MRT.mrt_name')->from('lead_customer_references LR')->join('master_relation_type MRT', 'MRT.mrt_id = LR.lcr_relationType ', 'LEFT')->where('LR.lcr_active',1)->where('LR.lcr_lead_id',$lead_id)->get();       
        if($search_data->num_rows() > 0) {
            $data['status'] = 1;
            $data['leadInfo'] = $search_data->result_array();            
            $this->load->view('Support/referenceDetails',$data);
        } else { 
            $this->session->set_flashdata('error', 'Record Not found.');
            $this->load->view('Support/referenceDetails');
        }
    }    
    
    public function getReferenceId($ref_id) {
        $refId = $this->encrypt->decode($ref_id);
        $data['referenceInfo'] = $this->db->select('*')->from('lead_customer_references')->where('lcr_id',$refId)->get()->row_array();
        $data['relation_list'] = $this->db->select('mrt_id,mrt_name')->from('master_relation_type')->where('mrt_active',1)->get()->result_array();
        $this->load->view('Support/referenceDetails',$data);
    }
    
    public function searchDocsId() {
        $lead_id = trim($_POST['lead_id']);        
        $return_array = array('status' => 0);        
        $search_data = $this->db->select('*')->from('docs')->where('docs_active',1)->where('lead_id',$lead_id)->get();       
        if($search_data->num_rows() > 0) {
            $data['status'] = 1;
            $data['leadInfo'] = $search_data->result_array();            
            $this->load->view('Support/docsDetails',$data);
        } else { 
            $this->session->set_flashdata('error', 'Record Not found.');
            $this->load->view('Support/docsDetails');
        }
    } 
    
    public function docsDelete() {
        $docs_id = $this->encrypt->decode($_POST['docs_id']);
        $check_data = $this->db->select('*')->from('docs')->where('docs_active',1)->where('docs_id',$docs_id)->get();       
        if($check_data->num_rows() > 0) {
            $response = $this->db->where(['docs_id'=>$docs_id])->update('docs',['docs_active'=>0,'docs_deleted'=>1]);
            if($response) {
                $json['msg'] = 'Successfully deleted.';
            } else {
                $json['err'] = 'Not updated.';
            }
            echo json_encode($json);
        } else { 
            $this->session->set_flashdata('error', 'Record Not found.');
            $this->load->view('Support/docsDetails');
        }
    } 
    
    public function updatePersonalDetail() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $user_id                       = $_SESSION['isUserSession']['user_id'];
                $check_email                   = $this->input->post('check_email');
                $check_alternate_email         = $this->input->post('check_alternate_email');
                $check_mobile                  = $this->input->post('check_mobile');
                $check_alternate_mobile        = $this->input->post('check_alternate_mobile');                
                $lead_id                       = $this->input->post('lead_id');
                $email                         = $this->input->post('email');
                $alternate_email               = $this->input->post('alternate_email');
                $mobile                        = $this->input->post('mobile');
                $alternate_mobile              = $this->input->post('alternate_mobile');
                $loan_amount                   = $this->input->post('loan_amount');
                $pancard                       = $this->input->post('pancard');
                $gender                        = $this->input->post('gender');
                $dob                           = $this->input->post('dob');
                $religion_id                   = $this->input->post('religion_id');
                $marital_status_id             = $this->input->post('marital_status_id');
                $spouse_name                   = $this->input->post('spouse_name');
                $spouse_occupation_id          = $this->input->post('spouse_occupation_id');               
                $current_house                 = $this->input->post('current_house');                
                $current_locality              = $this->input->post('current_locality');
                $current_landmark              = $this->input->post('current_landmark');
                $current_state                 = $this->input->post('current_state');
                $current_city                  = $this->input->post('current_city');
                $residence_pincode             = $this->input->post('residence_pincode');                
                $lead_followup_remark          = $this->input->post('lead_followup_remark');
                //echo $check_email.'@'.$check_alternate_email.'@'.$check_mobile.'@'.$check_alternate_mobile;die;
                $data = [
                    'user_id'=>$user_id,
                    'check_email' => $check_email,
                    'check_alternate_email' => $check_alternate_email,
                    'check_mobile' => $check_mobile,
                    'check_alternate_mobile' => $check_alternate_mobile,
                    'email' => $email,
                    'alternate_email' => $alternate_email,
                    'mobile' => $mobile,
                    'alternate_mobile' => $alternate_mobile,
                    'loan_amount' => $loan_amount,
                    'pancard' => $pancard,
                    'gender' => $gender,
                    'dob' => $dob,
                    'religion_id' => $religion_id,
                    'marital_status_id' => $marital_status_id,
                    'spouse_name' => $spouse_name,
                    'spouse_occupation_id' => $spouse_occupation_id,
                    'current_house' => $current_house,
                    'current_locality' => $current_locality,
                    'current_landmark' => $current_landmark,
                    'current_state' => $current_state,
                    'current_city' => $current_city,
                    'residence_pincode' => $residence_pincode,
                    'lead_followup_remark' => $lead_followup_remark,
                    'updated_by' => $user_id,
                    'lead_id' => $lead_id,
                    'updated_on' => timestamp
                ];
                $response = $this->support->updatePersonalData($data);
                if($response) {
                    $json['msg'] = 'Successfully updated.';
                } else {
                    $json['err'] = 'Not updated.';
                }
                echo json_encode($json);
            }
        } else {
            echo "Session Expired. Please login first.";
            $login->index();
        }
    }
    
    public function updateEmploymentDetail() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->form_validation->set_rules('emp_email', 'Email', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $user_id                       = $_SESSION['isUserSession']['user_id'];                
                $lead_id                       = $this->input->post('lead_id');
                $employer_name                 = $this->input->post('employer_name');
                $emp_email                     = $this->input->post('emp_email');                
                $emp_house                     = $this->input->post('emp_house');
                $emp_street                    = $this->input->post('emp_street');
                $emp_landmark                  = $this->input->post('emp_landmark');
                $state                         = $this->input->post('state');
                $city                          = $this->input->post('city');
                $emp_pincode                   = $this->input->post('emp_pincode');
                $emp_residence_since           = $this->input->post('emp_residence_since');
                $emp_designation               = $this->input->post('emp_designation');
                $emp_department                = $this->input->post('emp_department');
                $emp_employer_type             = $this->input->post('emp_employer_type');
                $salary_mode                   = $this->input->post('salary_mode');                
                $lead_followup_remark          = $this->input->post('lead_followup_remark');
                $data = [
                    'user_id'=>$user_id,
                    'employer_name' => $employer_name,
                    'emp_email' => $emp_email,
                    'emp_house' => $emp_house,
                    'emp_street' => $emp_street,
                    'emp_landmark' => $emp_landmark,
                    'state' => $state,
                    'city' => $city,
                    'emp_pincode' => $emp_pincode,
                    'emp_residence_since' => $emp_residence_since,
                    'emp_designation' => $emp_designation,
                    'emp_department' => $emp_department,
                    'emp_employer_type' => $emp_employer_type,
                    'salary_mode' => $salary_mode,
                    'lead_followup_remark' => $lead_followup_remark,
                    'updated_by' => $user_id,
                    'lead_id' => $lead_id,
                    'updated_on' => timestamp
                ];
                $response = $this->support->updateEmploymentData($data);
                if($response) {
                    $json['msg'] = 'Successfully updated.';
                } else {
                    $json['err'] = 'Not updated.';
                }
                echo json_encode($json);
            }
        } else {
            echo "Session Expired. Please login first.";
            $login->index();
        }
    }
    
    public function updateReferenceDetail() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->form_validation->set_rules('lcr_name', 'Name', 'trim|required');
            $this->form_validation->set_rules('lcr_mobile', 'Mobile', 'trim|required');
            $this->form_validation->set_rules('lcr_relationType', 'Relation Type', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $user_id                       = $_SESSION['isUserSession']['user_id'];                
                $lcr_id                        = $this->input->post('lcr_id');
                $lead_id                       = $this->input->post('lead_id');
                $lcr_name                      = $this->input->post('lcr_name');
                $lcr_mobile                    = $this->input->post('lcr_mobile');                
                $lcr_relationType              = $this->input->post('lcr_relationType');
                $lead_followup_remark          = $this->input->post('lead_followup_remark');
                $data = [
                    'user_id'=>$user_id,
                    'lcr_id'=>$lcr_id,
                    'lcr_name' => $lcr_name,
                    'lcr_mobile' => $lcr_mobile,
                    'lcr_relationType' => $lcr_relationType,
                    'lead_followup_remark' => $lead_followup_remark,
                    'updated_by' => $user_id,
                    'lead_id' => $lead_id,
                    'updated_on' => timestamp
                ];
                $response = $this->support->updateReferenceData($data);
                if($response) {
                    $json['msg'] = 'Successfully updated.';
                } else {
                    $json['err'] = 'Not updated.';
                }
                echo json_encode($json);
            }
        } else {
            echo "Session Expired. Please login first.";
            $login->index();
        }
    }
    
    public function updateBankDetail() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
            $this->form_validation->set_rules('ifsc_code', 'IFSC Code', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $user_id               = $_SESSION['isUserSession']['user_id'];                
                $bank_name             = $this->input->post('bank_name');
                $lead_id               = $this->input->post('lead_id');
                $ifsc_code             = $this->input->post('ifsc_code');
                $beneficiary_name      = $this->input->post('beneficiary_name');
                $account_status        = $this->input->post('account_status');                
                $account               = $this->input->post('account');
                $account_type          = $this->input->post('account_type');
                $branch                = $this->input->post('branch');
                $lead_followup_remark  = $this->input->post('lead_followup_remark');
                $data = [
                    'user_id'=>$user_id,
                    'bank_name'=>$bank_name,
                    'ifsc_code' => $ifsc_code,
                    'beneficiary_name' => $beneficiary_name,
                    'account_status' => $account_status,
                    'account' => $account,
                    'account_type' => $account_type,
                    'lead_followup_remark' => $lead_followup_remark,
                    'updated_by' => $user_id,
                    'lead_id' => $lead_id,
                    'updated_on' => timestamp
                ];
                $response = $this->support->updateBankData($data);
                if($response) {
                    $json['msg'] = 'Successfully updated.';
                } else {
                    $json['err'] = 'Not updated.';
                }
                echo json_encode($json);
            }
        } else {
            echo "Session Expired. Please login first.";
            $login->index();
        }
    }
    
    public function updateDocsDetail() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->form_validation->set_rules('pancard', 'Pan Number', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $user_id                       = $_SESSION['isUserSession']['user_id']; 
                $lead_id                       = $this->input->post('lead_id');
                $pancard                       = $this->input->post('pancard');
                $lead_followup_remark          = $this->input->post('lead_followup_remark');
                $data = [
                    'user_id'=>$user_id,
                    'pancard' => $pancard,
                    'lead_followup_remark' => $lead_followup_remark,
                    'updated_by' => $user_id,
                    'lead_id' => $lead_id,
                    'updated_on' => timestamp
                ];
                $response = $this->support->updateDocsData($data);
                if($response) {
                    $json['msg'] = 'Successfully updated.';
                } else {
                    $json['err'] = 'Not updated.';
                }
                echo json_encode($json);
            }
        } else {
            echo "Session Expired. Please login first.";
            $login->index();
        }
    }
    
    public function updateCAMDetail() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->form_validation->set_rules('salary_credit1_date', 'Salary Date 1', 'trim|required');
            $this->form_validation->set_rules('salary_credit1_amount', 'Salary Amount 1', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {
                $user_id                       = $_SESSION['isUserSession']['user_id']; 
                $lead_id                       = $this->input->post('lead_id');
                $salary_credit1_date           = $this->input->post('salary_credit1_date');
                $salary_credit1_amount         = $this->input->post('salary_credit1_amount');
                $salary_credit2_date           = $this->input->post('salary_credit2_date');
                $salary_credit2_amount         = $this->input->post('salary_credit2_amount');
                $salary_credit3_date           = $this->input->post('salary_credit3_date');
                $salary_credit3_amount         = $this->input->post('salary_credit3_amount');
                $next_pay_date                 = $this->input->post('next_pay_date');
                $median_salary                 = $this->input->post('median_salary');
                $remark                        = $this->input->post('remark');
                $lead_followup_remark          = $this->input->post('lead_followup_remark');
                $data = [
                    'user_id'=>$user_id,
                    'salary_credit1_date' => $salary_credit1_date,
                    'salary_credit1_amount' => $salary_credit1_amount,
                    'salary_credit2_date' => $salary_credit2_date,
                    'salary_credit2_amount' => $salary_credit2_amount,
                    'salary_credit3_date' => $salary_credit3_date,
                    'salary_credit3_amount' => $salary_credit3_amount,
                    'next_pay_date' => $next_pay_date,
                    'median_salary' => $median_salary,
                    'remark' => $remark,
                    'lead_followup_remark' => $lead_followup_remark,
                    'updated_by' => $user_id,
                    'lead_id' => $lead_id,
                    'updated_on' => timestamp
                ];
                $response = $this->support->updateCAMData($data);
                if($response) {
                    $json['msg'] = 'Successfully updated.';
                } else {
                    $json['err'] = 'Not updated.';
                }
                echo json_encode($json);
            }
        } else {
            echo "Session Expired. Please login first.";
            $login->index();
        }
    }
}
