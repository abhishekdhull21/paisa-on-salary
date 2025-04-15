<?php

defined('BASEPATH') or exit('No direct script access allowed');

class LWTestController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function index() {

    }

    public function testpdf() {
        //error_reporting(E_ALL);
        //ini_set("display_errors", 1);
        $this->load->model('Task_Model', 'Tasks');
        $return_array = $this->Tasks->gererateSanctionLetternew($_GET['lead_id']);
    }

    public function apitesting() {
        $return_array = array();
        $this->load->helper('commonfun');
        $api_type = $_GET['apitype'];
        $lead_id = $_GET['leadid'];
        if ($api_type == 1) {
            $this->load->helper('integration/payday_quick_call_api');
            $return_array = payday_quickcall_api_call("LEAD_PUSH", $lead_id);
        } else if ($api_type == 5) {

            $this->load->helper('integration/payday_disbursement_api');

            $request_array = array();
            $request_array['bank_id'] = 1;
            $request_array['payment_mode_id'] = 1;
            $request_array['payment_type_id'] = 1;
            $request_array['bank_active'] = 1;

            $return_array = payday_loan_disbursement_call($lead_id, $request_array);
        } else if ($api_type == 6) {
            $this->load->helper('integration/payday_disbursement_api');
            $request_array = array();
            $request_array["trans_type"] = $_GET['trans_type'];

            $return_array = payday_loan_disbursement_api_call("DisburseLoanStatus", $lead_id, $request_array);
        } else if ($api_type == 7) {
            $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">

                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                <title>Legal Notice :</title>
                            </head>

                            <body>

                                <table width="763" border="0" align="center" cellpadding="0" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif;">
                                    <tr>
                                        <td>
                                            <table width="763" border="0" align="center" style="border:solid 1px #ddd; padding:10px; font-family:Arial, Helvetica, sans-serif; line-height:22px;">
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="0">
                                                            <tr>
                                                                <td colspan="2" align="center"><img src="https://loanwalle.com/public/emailimages/preach-law/image/preach-law-logo.png" alt="preach-law-logo" width="237" height="128" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="52%" align="left"><strong>Ref: Notice/Naman/2022/01</strong></td>
                                                                <td width="48%" align="right"><strong>Date: </strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="left">&nbsp;</td>
                                                                <td align="right">
                                                                    <!-- <p align="right"><strong>Delhi, India</strong></p> -->
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" align="right">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">To,</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p style="margin: 2px 0px;">Mr/Mrs : <span style="text-decoration:underline;"></span></p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p style="margin: 2px 0px;">Loan I’D : <span style="text-decoration:underline;"></span></p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>&nbsp;</td>
                                                                <td>&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p style="margin: 2px 0px;">Subject: Reminder notice for loan amount recovery.</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p style="margin: 2px 0px;"><strong>My Client:</strong> <strong></strong></p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p style="margin: 2px 0px;">M/S Naman Finlease Pvt. Ltd., operating under the brand name of Loanwalle.com.</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p style="margin: 2px 0px;">To whomsoever it may concern,</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p style="margin: 2px 0px;">On instructions and on behalf of my above-named client i.e., M/S Naman Finlease Pvt Ltd., operating under the brand name of &ldquo;Loanwalle.com&rdquo;, having it&rsquo;s registered head office at S-370, LGF, Panchsheel Park, New Delhi- 110017, I hereby serve upon you the following notice:-</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p style="margin: 2px 0px;">You had approached my client for a short-term loan on .</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p style="margin: 2px 0px;">Your repayment amount including the interest and other dues as on  is <img src="https://loanwalle.com/public/emailimages/preach-law/image/inr.png" alt="inr" width="13" height="13" /> , the particulars of which arementioned below:</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#ccc">
                                                                        <tr>
                                                                            <td width="197" valign="top" bgcolor="#FFFFFF">
                                                                                <p style="margin: 2px 0px;"><strong>PARTICULARS</strong></p>
                                                                            </td>
                                                                            <td width="188" valign="top" bgcolor="#FFFFFF">
                                                                                <p style="margin: 2px 0px;"><strong>AMOUNT/DAYS</strong></p>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="197" valign="top" bgcolor="#FFFFFF">
                                                                                <p style="margin: 2px 0px;">Principal Loan</p>
                                                                            </td>
                                                                            <td width="188" valign="top" bgcolor="#FFFFFF">
                                                                                <p style="margin: 2px 0px;"><img src="https://loanwalle.com/public/emailimages/preach-law/image/inr.png" alt="inr" width="13" height="13" /></p>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="197" valign="top" bgcolor="#FFFFFF">
                                                                                <p style="margin: 2px 0px;">Interest</p>
                                                                            </td>
                                                                            <td width="188" valign="top" bgcolor="#FFFFFF">
                                                                                <p style="margin: 2px 0px;"><img src="https://loanwalle.com/public/emailimages/preach-law/image/inr.png" alt="inr" width="13" height="13" /></p>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="197" valign="top" bgcolor="#FFFFFF">
                                                                                <p style="margin: 2px 0px;">Delay in Repayment</p>
                                                                            </td>
                                                                            <td width="188" valign="top" bgcolor="#FFFFFF">
                                                                                <p style="margin: 2px 0px;"></p>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="197" valign="top" bgcolor="#FFFFFF">
                                                                                <p style="margin: 2px 0px;">Late Penalty Interest</p>
                                                                            </td>
                                                                            <td width="188" valign="top" bgcolor="#FFFFFF">
                                                                                <p style="margin: 2px 0px;"><img src="https://loanwalle.com/public/emailimages/preach-law/image/inr.png" alt="inr" width="13" height="13" /></p>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="197" valign="top" bgcolor="#FFFFFF">
                                                                                <p style="margin: 2px 0px;">Total Due</p>
                                                                            </td>
                                                                            <td width="188" valign="top" bgcolor="#FFFFFF">
                                                                                <p style="margin: 2px 0px;"><img src="https://loanwalle.com/public/emailimages/preach-law/image/inr.png" alt="inr" width="13" height="13" /></p>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="197" valign="top" bgcolor="#FFFFFF">
                                                                                <p style="margin: 2px 0px;">Payment Received</p>
                                                                            </td>
                                                                            <td width="188" valign="top" bgcolor="#FFFFFF">
                                                                                <p style="margin: 2px 0px;"><img src="https://loanwalle.com/public/emailimages/preach-law/image/inr.png" alt="inr" width="13" height="13" /></p>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="197" valign="top" bgcolor="#FFFFFF">
                                                                                <p style="margin: 2px 0px;">Final Total</p>
                                                                            </td>
                                                                            <td width="188" valign="top" bgcolor="#FFFFFF">
                                                                                <p style="margin: 2px 0px;"><img src="https://loanwalle.com/public/emailimages/preach-law/image/inr.png" alt="inr" width="13" height="13" /></p>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p style="margin: 2px 0px;">As on , the total amount due and payable by you to my client is <img src="https://loanwalle.com/public/emailimages/preach-law/image/inr.png" alt="inr" width="13" height="13" /></p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p style="margin: 2px 0px;">You are hereby called upon to pay all the updated dues immediately in the absence of which my client will be compelled to initiate legal proceedings against you as per the law.</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p style="margin: 2px 0px;">You are also advised to take note of the fact that any further delay in repayment will be duly updated by my client with all the credit bureaus which will severe disparagement to your further borrowing capacity from any bank or financial institution.</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">Copy of this notice has been retained in my office for further course of actions and recourse.
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p align="right"><strong>Yours truly,</strong></p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" align="right">
                                                                    <p align="right"><strong>Krishna Kumar Mishra</strong><br />
                                                                        (Advocate&amp; Attorney)<br />
                                                                        PREACH LAW LLP</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" align="center">
                                                                    <p align="center"><em>Note: This is system generated demand notice, hence rubber stamp and signature are not required</em></p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" align="center" style="border-top:solid 2px #000;">
                                                                    <p align="center">PREACH LAW LLP<br />
                                                                        Office: E-111-B, Nawada Housing Complex, Uttam Nagar, Delhi – 110059<br />
                                                                        <a href="mailto:admin@preachlaw.com" style="color:#000; text-decoration:none !important;">admin@preachlaw.com</a> | T: 01146574455 | M:+91 9311664455 | <a href="http://www.preachlaw.com/" target="_blank" style="color:#000; text-decoration:none !important;">www.preachlaw.com</a>
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style=" line-height:22px; margin-bottom:5px;"><strong>Regards</strong><br />
                                                <strong style="color:#339;">Krishna Kumar Mishra, Founder Partner</strong><br />
                                                <span style="color:#339;">(Advocate, Consultant &amp; IPR Attorney)</span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><img src="https://loanwalle.com/public/emailimages/law-firm/image/line.jpg" alt="line" style="margin-bottom:10px;" /></td>
                                    </tr>
                                    <tr>
                                        <td style="font-style:italic; line-height:25px;"><strong>PREACH LAW LLP</strong><br />
                                            Reg. Office: B-34, S/F, Arjun Park, New Delhi – 110043<br />
                                            E: <a href="mailto:admin@preachlaw.com" style="color:#000; text-decoration:underline;">admin@preachlaw.com</a> | W: <a href="www.preachlaw.com" target="_blank" style="color:#000; text-decoration:underline;">www.preachlaw.com</a><br />
                                            T: 011-46574455 | M: +91 9311664455 | 9311465113 </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            </body>

                        </html>';

            $return_array = lw_send_email("shubham.agrawal@loanwalle.com", "LEGAL NOTICE", $message); //"","tech.team@loanwalle.com"
        } else if ($api_type == 8) {
            require_once(COMPONENT_PATH . 'CommonComponent.php');

            $CommonComponent = new CommonComponent();

            $return_array = $CommonComponent->call_pan_ocr_api($lead_id);
        } else if ($api_type == 9) {
            $this->load->helper('integration/payday_runo_call_api_helper');
            $return_array = payday_call_management_api_call("PRECOLLX_CAT_SANCTION", $lead_id, array('mobile' => 9560807913));
        } else if ($api_type == 10) {
            require_once(COMPONENT_PATH . 'CommonComponent.php');

            $CommonComponent = new CommonComponent();

            $return_array = $CommonComponent->run_eligibility($lead_id);
        } else {
            die("Invalid Request");
        }


        echo "<pre>";
        print_r($return_array);
    }

    public function report() {
        $this->load->model('Report_Model');
        $result = $this->Report_Model->PaymentAnalysis(1, '01-04-2022');
        echo "<pre>";
        print_r($result);
        exit;
    }

    public function send_letter() {
        $lead_id = $_GET['lead_id'];
        $template = $_GET['templete'];
        $this->load->model('Task_Model');
        $result = $this->Task_Model->$template($lead_id);
        print_r("Sent.......'.$result.'");
    }

    public function test() {
        $lead_id = $_GET['lead_id'];
        $customer_email = 'alam@surya.com';
        $customer_name = 'Alam';

        $this->load->model('Task_Model');
        $enc = $this->Task_Model->sent_loan_closed_noc_letter($lead_id);
        //            $enc = $this->Task_Model->sendSanctionMail($lead_id);
        //            $enc = $this->Task_Model->preApprovedOfferEmailer($customer_email, $customer_name, $lead_id = 0, 2);
        print_r($enc);
    }

    public function encry_test() {
        $encode = $this->encrypt->encode('73458');
        $decode = $this->encrypt->decode('XBcFKAEpAzEEMFNuBmUHc1Y7UnNUJw9BAztTYAE6By4=');
        print_r('LMS enc : ' . $encode);
        echo '<br>';
        print_r($decode);
    }

    public function send_pre_approved_mail() {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $this->load->model('Task_Model', 'Task');

        //            $this->Task->preApprovedOfferEmailer('alam.ansari@surya.com', 'Alam', 4005, 2);
        //            $this->Task->send_Customer_Feedback_Emailer(4005, 'alam.ansari@surya.com', 'Alam');
        //           $res = $this->Task->sent_ekyc_request_email(4005);

        $res = lw_send_email('alam.ansari@surya.com', 'Test', 'Testing');
        print_r($res);
        exit;
    }

    public function fb() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        //            $form_id = $_GET['form_id'];
        //            $min = $_GET['min'];

        $this->load->helper('/integration/payday_fb_call_api');
        //            $res = payday_fb_campaign_api_call('GET_FORM_DATA', $form_id, $min);
        $res = get_fb_page_forms_api('GET_PAGE_FORM');
        echo '<pre>';
        print_r($res);
    }

    public function send_sms() {

        //        ini_set('display_errors', 1);
        //        ini_set('display_startup_errors', 1);
        //        error_reporting(E_ALL);

        $req = array();
        $sms_type_id = $_GET['sms_type_id'];
        $lead_id = $_GET['lead_id'];
        $req = array();
        $sql = 'select LD.lead_id,LC.mobile,LC.first_name as name from leads LD inner join lead_customer LC on (LC.customer_lead_id=LD.lead_id) where LD.lead_id=' . $lead_id;
        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            $result = $result->result_array();
        }
        foreach ($result as $row) {
            $req['lead_id'] = $row['lead_id'];
            $req['mobile'] = $row['mobile'];
            $req['name'] = $row['name'];
            $req['otp'] = rand(1000, 9999);
        }
        $req['mobile'] = 8750256406;
        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $res = $CommonComponent->payday_sms_api($sms_type_id, $req['lead_id'], $req);

        print_r($res);
    }

    public function lead_thankyou_sms() {
        $sms_type_id = 2;
        $lead_id = $_GET['lead_id'];
        $req = array();
        $sql = 'select LD.lead_id,LD.lead_reference_no as reference_no,LC.mobile,LC.first_name as name from leads LD inner join lead_customer LC on (LC.customer_lead_id=LD.lead_id) where LD.lead_id=' . $lead_id;
        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            $result = $result->result_array();
        }
        foreach ($result as $row) {
            $req['lead_id'] = $row['lead_id'];
            $req['refrence_no'] = $row['reference_no'];
            $req['mobile'] = $row['mobile'];
            $req['name'] = $row['name'];
        }
        $req['mobile'] = 8750256406;
        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $res = $CommonComponent->payday_sms_api($sms_type_id, $req['lead_id'], $req);

        print_r($res);
    }

    public function connect_executive_sms() {

        $sms_type_id = 3;
        $lead_id = $_GET['lead_id'];

        $req = array();
        $sql = 'select LD.lead_id,LC.first_name as name,LC.mobile, U.name as executive_name,U.mobile as executive_mobile from leads LD inner join lead_customer LC on (LC.customer_lead_id=LD.lead_id)';
        $sql .= ' inner join users U on (U.user_id=LD.lead_screener_assign_user_id) where LD.lead_id=' . $lead_id;

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            $result = $result->result_array();
        }
        foreach ($result as $row) {
            $req['lead_id'] = $row['lead_id'];
            $req['executive_name'] = $row['executive_name'];
            $req['executive_mobile'] = $row['executive_mobile'];
            $req['mobile'] = $row['mobile'];
            $req['name'] = $row['name'];
        }
        $req['mobile'] = 8750256406;
        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $res = $CommonComponent->payday_sms_api($sms_type_id, $req['lead_id'], $req);

        print_r($res);
    }

    public function lead_rejection_sms() {
        $sms_type_id = 4;
        $lead_id = $_GET['lead_id'];

        $req = array();
        $sql = 'select LD.lead_id,LC.mobile from leads LD inner join lead_customer LC on (LC.customer_lead_id=LD.lead_id) where LD.lead_id=' . $lead_id;

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            $result = $result->result_array();
        }
        foreach ($result as $row) {
            $req['lead_id'] = $row['lead_id'];
            $req['mobile'] = $row['mobile'];
        }
        $req['mobile'] = 8750256406;
        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $res = $CommonComponent->payday_sms_api($sms_type_id, $req['lead_id'], $req);

        print_r($res);
    }

    public function loan_disbursed_sms() {
        $sms_type_id = 5;
        $lead_id = $_GET['lead_id'];

        $req = array();
        $sql = 'select LD.lead_id,LC.first_name as name,LC.mobile, L.loan_no, L.recommended_amount, CB.account, CAM.repayment_amount, CAM.repayment_date from leads LD inner join lead_customer LC on (LC.customer_lead_id=LD.lead_id)';
        $sql .= ' inner join loan L on (L.lead_id=LD.lead_id) inner join credit_analysis_memo CAM on (CAM.lead_id=LD.lead_id) inner join customer_banking CB on (CB.lead_id=LD.lead_id) where LD.lead_id=' . $lead_id;

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            $result = $result->result_array();
        }
        foreach ($result as $row) {
            $req['lead_id'] = $row['lead_id'];
            $req['loan_no'] = $row['loan_no'];
            $req['loan_amount'] = $row['recommended_amount'];
            $req['cust_bank_account_no'] = $row['account'];
            $req['repayment_amount'] = $row['repayment_amount'];
            $req['repayment_date'] = $row['repayment_date'];
            $req['mobile'] = $row['mobile'];
            $req['name'] = $row['name'];
        }
        $req['mobile'] = 8750256406;
        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $res = $CommonComponent->payday_sms_api($sms_type_id, $req['lead_id'], $req);

        print_r($res);
    }

    public function loan_repayment_reminder_sms() {
        $sms_type_id = $_GET['sms_type_id'];
        $lead_id = $_GET['lead_id'];

        $req = array();

        $sql = 'select LD.lead_id, LC.first_name as cust_name,LC.mobile, L.loan_no, CAM.repayment_date, CAM.repayment_amount from leads LD inner join lead_customer LC on (LC.customer_lead_id=LD.lead_id)';
        $sql .= ' inner join loan L on (L.lead_id=LD.lead_id) inner join credit_analysis_memo CAM on (CAM.lead_id=LD.lead_id) where LD.lead_id=' . $lead_id;

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            $result = $result->result_array();
        }

        foreach ($result as $row) {
            $req['pending_days'] = 2;
            $req['lead_id'] = $row['lead_id'];
            $req['loan_no'] = $row['loan_no'];
            $req['name'] = $row['cust_name'];
            $req['repayment_amount'] = $row['repayment_amount'];
            $req['repayment_date'] = $row['repayment_date'];
            $req['mobile'] = $row['mobile'];
        }
        $req['mobile'] = 8750256406;
        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $res = $CommonComponent->payday_sms_api($sms_type_id, $req['lead_id'], $req);

        print_r($res);
    }

    public function lead_apply_contact_sms() {
        $sms_type_id = 7;
        $lead_id = $_GET['lead_id'];
        $req = array();
        $sql = 'select LD.lead_id,LC.mobile,LC.first_name as name from leads LD inner join lead_customer LC on (LC.customer_lead_id=LD.lead_id) where LD.lead_id=' . $lead_id;
        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            $result = $result->result_array();
        }
        foreach ($result as $row) {
            $req['lead_id'] = $row['lead_id'];
            $req['mobile'] = $row['mobile'];
            $req['name'] = $row['name'];
        }
        $req['mobile'] = 8750256406;
        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $res = $CommonComponent->payday_sms_api($sms_type_id, $req['lead_id'], $req);

        print_r($res);
    }

    public function finbox() {
        echo 'Api Called';
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $res = $CommonComponent->finbox_api_call($_GET['lead_id'], "");

        echo '<pre>';
        print_r($res);
        echo 'Api Ended';
    }

    public function fetch_pan_details() {
        echo 'Api Called';
        //        ini_set('display_errors', 1);
        //        ini_set('display_startup_errors', 1);
        //        error_reporting(E_ALL);

        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $res = $CommonComponent->call_pan_verification_api($_GET['lead_id']);

        echo '<pre>';
        print_r($res);
        echo 'Api Ended';
    }

    public function finbox_bureauconnect_api() {
        echo 'Api Called';
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $res = $CommonComponent->call_finbox_bureauconnect_api($_GET['lead_id'], "");

        echo '<pre>';
        print_r($res);
        echo 'Api Ended';
    }

    public function finbox_bank_connect_api() {
        echo 'Api Called';
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $lead_id = $_GET['lead_id'];
        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $res = $CommonComponent->call_finbox_bank_connect_upload_api($lead_id, "");

        if ($res['status'] == 1) {
            $request_array['entity_id'] = $res['data']['entity_id'];
            //echo $request_array['entity_id'];
            $res = $CommonComponent->call_finbox_bank_connect_fetch_api($lead_id, $request_array);
        }
        echo "<pre>";
        print_r($res);
        echo 'Api Ended';
    }

    public function get_sql() {
        $conditions['LD.lead_id'] = 2665;
        $this->load->model('Task_Model', 'Tasks');
        $data = $this->Tasks->getLeadDetails($conditions);
        echo "<pre>";
        print_r(json_encode($data->row()));
    }

    public function send_link() {
        $req = array();
        $sms_type_id = $_GET['sms_type_id'];
        $lead_id = $_GET['lead_id'];

        $req = array();
        $sql = 'select LD.lead_id,LC.mobile,LC.first_name as name from leads LD inner join lead_customer LC on (LC.customer_lead_id=LD.lead_id) where LD.lead_id=' . $lead_id;
        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            $result = $result->result_array();
        }
        foreach ($result as $row) {
            $req['lead_id'] = $row['lead_id'];
            $req['mobile'] = $row['mobile'];
            $req['name'] = $row['name'];
        }

        if ($sms_type_id == 12) {
            $req['esign_link'] = "https://esign.nsdl.com";
        } else if ($sms_type_id == 13) {
            $req['ekyc_link'] = "https://esign.digilocker.com";
        }
        $req['mobile'] = 8750256406;
        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $res = $CommonComponent->payday_sms_api($sms_type_id, $req['lead_id'], $req);

        print_r($res);
    }

    public function update_cam() {
        $lead_id = $_GET['lead_id'];
        $nobel_response_data = $this->db->query('SELECT docs.lead_id as docs_lead_id, api_banking_cart_log.cart_log_id as cart_log_id, api_banking_cart_log.cart_return_novel_doc_id as log_nobel_return_id, api_banking_cart_log.cart_response as nobel_response_data
                FROM `docs`
                INNER join api_banking_cart_log ON api_banking_cart_log.cart_lead_id = docs.lead_id
                where docs.lead_id =' . $lead_id . ' AND docs.docs_master_id = 6 AND docs.docs_active = 1 AND docs.docs_deleted = 0 AND api_banking_cart_log.cart_method_id = 2 AND api_banking_cart_log.cart_api_status_id IN (1,2) AND api_banking_cart_log.cart_active = 1 AND api_banking_cart_log.cart_deleted = 0
                ORDER BY api_banking_cart_log.cart_log_id DESC LIMIT 1');

        if ($nobel_response_data->num_rows() > 0) {

            $api_data = $nobel_response_data->row();

            $nobel_response_data_json = stripslashes($api_data->nobel_response_data);

            $response_data_array = json_decode($nobel_response_data_json, true);
            // echo "<pre>";
            // print_r($response_data_array);die;

            if ($response_data_array['status'] == 'Submitted') {
                $responseArray['success_msg'] = "Transaction is processed but Final Summary output is not available for View.";
            } else if ($response_data_array['status'] == 'In Progress') {
                $responseArray['success_msg'] = "Transaction is uploaded and is In process. Not available for view at this stage.";
            } else if ($response_data_array['status'] == 'Deleted') {
                $responseArray['success_msg'] = "Transaction was deleted";
            } else if (in_array(strtolower($response_data_array['status']), ['downloaded', 'processed'])) {
                // echo "<pre>";
                // print_r($response_data_array);die;

                $account_details = $response_data_array['data'][0];
                $cam_details = $account_details['camAnalysisData'];
                $cam_details_monthly_wise = $account_details['camAnalysisData']['camAnalysisMonthly'];
                $cheque_Bounces = $account_details['chequeBounces'];
                $emi = $account_details['emi'];
                $salary = $account_details['salary'];

                $salary = array_reverse($salary);

                $update_monthly_salary_count = 1;

                $iii = 0;

                $cam_salary_data = $this->db->query('SELECT salary_credit1_date, salary_credit1_amount, salary_credit2_date, salary_credit2_amount, salary_credit3_date, salary_credit3_amount
                FROM `credit_analysis_memo` where lead_id=' . $lead_id);

                $cam_salary_data = $cam_salary_data->row();

                $salary_credit1_date = (empty($cam_salary_data->salary_credit1_date) || $cam_salary_data->salary_credit1_date == 0) ? 0 : $cam_salary_data->salary_credit1_date;
                $salary_credit2_date = (empty($cam_salary_data->salary_credit2_date) || $cam_salary_data->salary_credit2_date == 0) ? 0 : $cam_salary_data->salary_credit2_date;
                $salary_credit3_date = (empty($cam_salary_data->salary_credit3_date) || $cam_salary_data->salary_credit3_date == 0) ? 0 : $cam_salary_data->salary_credit3_date;
                $salary_credit1_amount = (empty($cam_salary_data->salary_credit1_amount) || $cam_salary_data->salary_credit1_amount == 0) ? 0 : $cam_salary_data->salary_credit1_amount;
                $salary_credit2_amount = (empty($cam_salary_data->salary_credit2_amount) || $cam_salary_data->salary_credit2_amount == 0) ? 0 : $cam_salary_data->salary_credit2_amount;
                $salary_credit3_amount = (empty($cam_salary_data->salary_credit3_amount) || $cam_salary_data->salary_credit3_amount == 0) ? 0 : $cam_salary_data->salary_credit3_amount;

                foreach ($salary as $salary_months) {
                    foreach ($salary_months as $key => $value) {
                        if ($update_monthly_salary_count <= 3) {
                            if ($key == 'transactions') {
                                foreach ($salary[$iii]['transactions'] as $key_salary_transactions) {
                                    foreach ($key_salary_transactions as $key_day => $key_balance) {
                                        if ($update_monthly_salary_count == 1) {
                                            if ($key_day == 'transactionDate') {
                                                $response_data['salary_credit1_date'] = (date('Y-m-d', substr($key_balance, 0, -3)));
                                            }
                                            if ($key_day == 'amount') {
                                                $response_data['salary_credit1_amount'] = (($key_balance) ? $key_balance : '-');
                                            }
                                        } else if ($update_monthly_salary_count == 2) {
                                            if ($key_day == 'transactionDate') {
                                                $response_data['salary_credit2_date'] = (date('Y-m-d', substr($key_balance, 0, -3)));
                                            }
                                            if ($key_day == 'amount') {
                                                $response_data['salary_credit2_amount'] = (($key_balance) ? $key_balance : '-');
                                            }
                                        } else if ($update_monthly_salary_count == 3) {
                                            if ($key_day == 'transactionDate') {
                                                $response_data['salary_credit3_date'] = (date('Y-m-d', substr($key_balance, 0, -3)));
                                            }
                                            if ($key_day == 'amount') {
                                                $response_data['salary_credit3_amount'] = (($key_balance) ? $key_balance : '-');
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            break;
                        }
                    }
                    $update_monthly_salary_count++;
                    $iii++;
                }

                $update_data = array();
                //}
                if ($salary_credit1_date == 0) {
                    $update_data['salary_credit1_date'] = $response_data['salary_credit1_date'];
                } else {
                    $update_data['salary_credit1_date'] = $salary_credit1_date;
                }
                if ($salary_credit2_date == 0) {
                    $update_data['salary_credit2_date'] = $response_data['salary_credit2_date'];
                } else {
                    $update_data['salary_credit2_date'] = $salary_credit2_date;
                }
                if ($salary_credit3_date == 0) {
                    $update_data['salary_credit3_date'] = $response_data['salary_credit3_date'];
                } else {
                    $update_data['salary_credit3_date'] = $salary_credit3_date;
                }
                if ($salary_credit1_amount == 0) {
                    $update_data['salary_credit1_amount'] = $response_data['salary_credit1_amount'];
                } else {
                    $update_data['salary_credit1_amount'] = $salary_credit1_amount;
                }
                if ($salary_credit2_amount == 0) {
                    $update_data['salary_credit2_amount'] = $response_data['salary_credit2_amount'];
                } else {
                    $update_data['salary_credit2_amount'] = $salary_credit2_amount;
                }
                if ($salary_credit3_amount == 0) {
                    $update_data['salary_credit3_amount'] = $response_data['salary_credit3_amount'];
                } else {
                    $update_data['salary_credit3_amount'] = $salary_credit3_amount;
                }
                if (!empty($update_data)) {
                    $this->db->update("credit_analysis_memo", $update_data, array("lead_id" => $lead_id));
                }

                /*                 * ******************Check Salary Details in CAM End************************************** */
            } else if (in_array(strtolower($response_data_array['status']), ['rejected'])) {
                $responseArray['error_msg'] = $response_data_array['message'];
            } else if (strpos(strtolower($response_data_array['message']), 'fraud') !== false) {
                $responseArray['error_msg'] = $response_data_array['message'];
            }

            // echo "response_data_array : <pre>"; print_r($response_data_array); exit;
        } else {
            $responseArray['error_msg'] = "Document not found. Please Try Again.";
        }

        return $responseArray;
    }

    public function url_short() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $url = $_GET['url'];

        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $res = $CommonComponent->call_url_shortener_api($url);

        print_r($res["short_url"]);
    }

    public function email_verification() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $lead_id = $_GET['lead_id'];

        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();
        $request_array = array();
        $request_array['email_type'] = $_GET['type'];

        $res = $CommonComponent->call_email_verification_api($lead_id, $request_array);
        echo '<pre>';
        print_r($res);
    }

    public function whatsapp_api() {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $req = array();
        $templete_type_id = $_GET['type_id'];
        $lead_id = $_GET['lead_id'];

        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $res = $CommonComponent->payday_whatsapp_api($templete_type_id, $lead_id);

        echo "<pre>";
        print_r($res);
    }

    public function bureau_api() {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $req = array();
        $lead_id = $_GET['lead_id'];

        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $res = $CommonComponent->call_bre_rule_engine($lead_id);

        echo "<pre>";
        print_r($res);
    }

    public function down_file() {
        $name = "9618_lms_20230515170913621.pdf";
        $ci = &get_instance();
        $ci->load->helper(array('commonfun'));
        move_uploaded_file(downloadDocument($name, 0), base_url("public/uploads/") . $name);
        //file_put_contents(base_url("public/uploads/").$name,$file);
        //print_r($file);
    }

    public function get_file() {
        $name = $_GET['file'];

        $this->load->helper('commonfun');

        $file = file_get_contents(downloadDocument($name, 0));

        echo $file;
    }

    public function check_table_data() {
        $lead_id = 15795;
        $this->load->model('Task_Model', 'Tasks');

        $data = $this->Tasks->getEmploymentDetails($lead_id);

        echo "<pre>";
        print_r($data->row());
    }

    public function bank_analysis_api() {
        $lead_ID = 15795;

        $request_array = array();

        $docs_details = $this->db->query('SELECT docs.docs_id, docs.lead_id, leads.status, leads.stage, leads.lead_status_id FROM `docs` INNER join leads ON leads.lead_id = docs.lead_id where docs.lead_id =' . $lead_id . ' AND docs.docs_master_id = 6 ORDER BY  docs.docs_id DESC LIMIT 1');

        if ($docs_details->num_rows() > 0) {
            $docs = $docs_details->row();
            $doc_id = $docs->docs_id;

            $request_array['doc_id'] = $doc_id;

            require_once(COMPONENT_PATH . "CommonComponent.php");

            $CommonComponent = new CommonComponent();
            echo 'object created';

            $result = $CommonComponent->payday_bank_analysis_upload_api_call($lead_ID, $request_array);

            print_r($result);
        }
    }

    public function upload_test() {

        echo 'LW Tescontroller START';
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        require_once(COMPONENT_PATH . '/s3_bucket/S3_upload.php');

        $new = new S3_upload();
        $res = $new->upload_file('/home/devmunitechuat/public_html/upload/', '1788_1_20230329081119_2635.pdf', 0);
        echo "<pre>";
        print_r($res);
        echo 'LW Tescontroller END';
    }

    public function write_letter() {
        $lead_ID = 15683;
        $this->load->model('Task_Model', 'Tasks');
        $this->Tasks->gererateSanctionLetter($lead_ID);
    }

    public function check_mail() {
        $to = 'akash.kushwaha@surya.com';
        $subject = 'Test Mail';
        $message = 'Its working';

        require_once(COMPONENT_PATH . 'includes/functions.inc.php');

        common_send_email($to, $subject, $message);
    }

    public function send_disbursal_email() {
        $lead_ID = $_GET['lead_id'];
        $this->load->model('Task_Model', 'Tasks');
        $res = $this->Tasks->sendDisbursalMail($lead_ID);
        echo '<pre>';
        print_r($res);
    }

    public function noc_Settled_Payment() {
        $lead_ID = $_GET['lead_id'];
        $this->load->model('Task_Model', 'Tasks');
        $res = $this->Tasks->nocSettledPayment($lead_ID);
        echo '<pre>';
        print_r($res);
    }

    public function noc_Settled_closing() {
        $lead_ID = $_GET['lead_id'];
        $this->load->model('Task_Model', 'Tasks');
        $res = $this->Tasks->sent_loan_closed_noc_letter($lead_ID);
        echo '<pre>';
        print_r($res);
    }

    public function check_crif() {
        require_once(COMPONENT_PATH . "CommonComponent.php");

        $CommonComponent = new CommonComponent();
        $res = $CommonComponent->call_bureau_api(15829);
        print_r($res);
    }

    public function adjust() {
        echo '<pre>';
//        ini_set('display_errors', 1);
//        ini_set('display_startup_errors', 1);
//        error_reporting(E_ALL);
//
        require_once(COMPONENT_PATH . "CommonComponent.php");

        $CommonComponent = new CommonComponent();
        $res = $CommonComponent->call_adjust_api(15927);
        print_r($res);
    }

    public function generate_sanction_letter() {
        $lead_id = $_GET['lead_id'];
        $this->load->model('Task_Model', 'Tasks');
        $data = $this->Tasks->gererateSanctionLetter($lead_id);
        header("Content-Type: {$data['header_content_type']}");
        echo $data['document_body'];
    }

    public function eligibility() {
        echo '<pre>';
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $lead_id = 16047;
        require_once(COMPONENT_PATH . "CommonComponent.php");
        $CommonComponent = new CommonComponent();
        $res = $CommonComponent->run_eligibility($lead_id);
        print_r($res);
    }

    public function smsAnalizer() {
        echo '<pre>';
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $lead_id = 21;
        //$request_array['userIds'] = [1234];
        //$request_array['userId'] = 1234;
        $request_array = ['syncId' => "1696312788209_1234", 'sendAppInfo' => true, '1' => true, 'sendCallLogsInfo' => true, 'sendSms' => true, 'sendExcelPath' => true, 'sendFraudIndicator' => true];
        require_once(COMPONENT_PATH . "CommonComponent.php");
        $CommonComponent = new CommonComponent();
        $res = $CommonComponent->call_payday_sms_analyser('GET_USER_SCOPE', $lead_id, $request_array);
        print_r($res);
    }

    public function getContent() {
        $url = "http://surya.sms.variables.digitap.demo.in.s3.ap-south-1.amazonaws.com/2b7a82215241b5cd/1234/1696312788209_1234/syncData.json?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Date=20231017T090354Z&X-Amz-SignedHeaders=host&X-Amz-Expires=3600&X-Amz-Credential=AKIA325LPZPYI54D42HV%2F20231017%2Fap-south-1%2Fs3%2Faws4_request&X-Amz-Signature=96e3600b05be6e4891bc43cf071df89f7e5aa4f7f244a076d41c360a417db022";
        $obj = json_decode(file_get_contents($url), true);
        echo '<pre>';
        print_r($obj);
        die;
    }

    public function digilocker_create_url() {
        $lead_id = $_GET['lead_id'];
        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        $res = $CommonComponent->call_aadhaar_verification_request_api($lead_id);

        echo "<pre>";

        print_r($res);
    }

    public function smartping() {

        $this->load->helper('integration/payday_smartping_call_api_helper');

        echo '<pre>';
        echo 'CALLED';
//        ini_set('display_errors', 1);
//        ini_set('display_startup_errors', 1);
//        error_reporting(E_ALL);
//        $lead_id = $_GET['lead_id'];

        $request_array = array();
        $request_array['call_type'] = 1;
        $request_array['profile_type'] = 2;
        $request_array['lead_list'] = array(1, 2, 5, 41, 93, 18, 19, 20, 21, 22);
        print_r($request_array);

        $res = payday_call_management_api_call("SMARTPING_BULK_UPLOAD", 0, $request_array);
        print_r($res);
    }

    public function bank_analysis_upload() {
        $lead_id = $_GET['lead_id'];

        $request_array['doc_id'] = $_GET['doc_id'];

        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $res = $CommonComponent->call_payday_bank_analysis("BANK_STATEMENT_UPLOAD", $lead_id, $request_array);

        echo '<pre>';
        print_r($res);
    }

    public function bank_analysis_download() {
        $lead_id = $_GET['lead_id'];

        $request_array['doc_id'] = $_GET['doc_id'];

        require_once(COMPONENT_PATH . 'CommonComponent.php');

        $CommonComponent = new CommonComponent();

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $res = $CommonComponent->call_payday_bank_analysis("BANK_STATEMENT_DOWNLOAD", $lead_id, $request_array);

        echo '<pre>';
        print_r($res['raw_response']);
    }

    public function smart_ping_whatsapp() {
        require_once(COMPONENT_PATH . 'CommonComponent.php');
        $CommonComponent = new CommonComponent();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $request_array = array('smart_ping_whatsapp_flag' => 1);
        $res = $CommonComponent->call_whatsapp_api(14, 16047, $request_array);
        echo '<pre>';
        print_r($res);
        die;
    }

    public function bureau_customer_mobile_number() {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $lead_id = $_GET['lead_id'];
        require_once(COMPONENT_PATH . 'CommonComponent.php');
        $CommonComponent = new CommonComponent();
        $res = $CommonComponent->call_bureau_api($lead_id);
        echo '<pre>';
        print_r($res);
    }

    public function ai_sensy_api_test() {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $lead_id = $_GET['lead_id'];
        require_once(COMPONENT_PATH . 'CommonComponent.php');
        $CommonComponent = new CommonComponent();
        $request_array = array(
            "template_id" => 1,
            "user_id" => 66,
            "api_provider_id" => 2
        );
        $res = $CommonComponent->call_whatsapp_api(1, $lead_id, $request_array);
        echo '<pre>';
        print_r($res);
    }
}
