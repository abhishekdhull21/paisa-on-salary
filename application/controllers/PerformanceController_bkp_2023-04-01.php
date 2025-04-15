<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PerformanceController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Performance_Model', 'Performance');
        date_default_timezone_set('Asia/Kolkata');
        $login = new IsLogin();
        $login->index();
    }

    public function SanctionPerformancePopup() {

        $user_id = $_SESSION['isUserSession']['user_id'];
        $role_id = $_SESSION['isUserSession']['role_id'];

        $label_name = array();
        $today_disburse_cases = 0;
        $today_disburse_amount = 0;
        $monthly_achieve_cases = 0;
        $monthly_achieve_amount = 0;
        $cases_percentage = 0;
        $amount_percentage = 0;
        $principle_amount = 0;
        $principle_received = 0;
        $monthly_target_amount = 0;
        $total_collection_amount = 0;
        $monthly_target_cases = 0;
        $monthly_target_amount = 0;

        $type_id = 0;
        $message = "";

        if (in_array($role_id, array(2, 3))) {
            $type_id = 1;
        } elseif (in_array($role_id, array(7))) {
            $type_id = 2;
        }



        $data = $this->Performance->sanction_popup_model($user_id, $type_id);

        if ($data['status'] == 1) {

            if ($type_id == 1) {

                $today_disburse_cases = $data['data']['today_disburse_cases'] ? $data['data']['today_disburse_cases'] : 0;
                $today_disburse_amount = $data['data']['today_disburse_amount'] ? $data['data']['today_disburse_amount'] : 0;
                $monthly_achieve_cases = $data['data']['monthly_achieve_cases'] ? $data['data']['monthly_achieve_cases'] : 0;
                $monthly_achieve_amount = $data['data']['monthly_sanction_achieve_amount'] ? $data['data']['monthly_sanction_achieve_amount'] : 0;

                $monthly_target_cases = $data['data']['monthly_target_cases'] ? $data['data']['monthly_target_cases'] : 0;
                $monthly_target_amount = $data['data']['monthly_target_amount'] ? $data['data']['monthly_target_amount'] : 0;

                $today_sanction_cases = $data['data']['today_sanction_cases'] ? $data['data']['today_sanction_cases'] : 0;
                $today_sanction_amount = $data['data']['today_sanction_amount'] ? $data['data']['today_sanction_amount'] : 0;

                $monthly_sanction_cases = $data['data']['monthly_sanction_cases'] ? $data['data']['monthly_sanction_cases'] : 0;
                $monthly_sanction_amount = $data['data']['monthly_sanction_amount'] ? $data['data']['monthly_sanction_amount'] : 0;

                $past_days = date('d');
                $remainin_days = date('t') - $past_days;
                $current_run_case = $monthly_achieve_cases / $past_days;
                $current_run_amount = $monthly_achieve_amount / $past_days;
                $required_run_case = ($monthly_target_cases - $monthly_achieve_cases) / $remainin_days;
                $required_run_amount = ($monthly_target_amount - $monthly_achieve_amount) / $remainin_days;

                $label_name['today_heder'] = 'Today Sanction Details';
                $label_name['monthly_heder'] = 'Monthly Sanction Details';
                $label_name['per_heder'] = 'Collection Percentage (%) as on Date';
                $label_name['sub_heder1'] = 'Sanction Cases';
                $label_name['sub_heder2'] = 'Sanction Amount';
            } elseif ($type_id == 2) {

                $today_disburse_cases = $data['data']['today_followup_cases'] ? $data['data']['today_followup_cases'] : 0;
                $today_disburse_amount = $data['data']['today_collection_amount'] ? $data['data']['today_collection_amount'] : 0;

                $monthly_achieve_amount = $data['data']['monthly_sanction_achieve_amount'] ? $data['data']['monthly_sanction_achieve_amount'] : 0;

                $amount_percentage = !empty($monthly_achieve_amount) && !empty($monthly_target_amount) ? number_format(($monthly_achieve_amount / $monthly_target_amount) * 100, 2) : 0;

                $label_name['today_heder'] = 'Today Collection Details';
                $label_name['monthly_heder'] = 'Monthly Collection Details';
                $label_name['per_heder'] = 'Achievement Percentage (%)';
                $label_name['sub_heder1'] = 'Follow Ups';
                $label_name['sub_heder2'] = 'Collected Amount';
            }

            $message = '<head>
                            <title>Table</title>
                            
                            <style>
                                body {font-family: "trebuchet MS", Arial;font-size: 14px;color: #444;}
                                .table-responsive{ background-color: transparent !important;}
                                table {*border-collapse: collapse; border-spacing: 0; width: 100%;}
                                .modal-open .modal .btn-default{float: right;position: absolute;right: 0px;border: none; font-size: 15px;}
                                .modal-open .modal {overflow-x: hidden;overflow-y: auto; width: 51%; margin: 0 auto;}
                                
                                @media( max-width : 768px){
                                .modal-open .modal {
                                    width: 100%;
                                }
                                }


                                .bordered {border: transparent;-moz-border-radius: 6px;-webkit-border-radius: 6px;
                                order-radius: 6px;-webkit-box-shadow: 0 1px 1px #ccc;-moz-box-shadow: 0 1px 1px #ccc;box-shadow: none;
                                width: 100%;margin: 0 auto;background-color: #fff;padding: 10px;
                                float: none;border-collapse: inherit;border-radius: 4px;}
                                .bordered tr:hover {background: #fbf8e9;-o-transition: all 0.1s ease-in-out;-webkit-transition: all 0.1s ease-in-out;-moz-transition: all 0.1s ease-in-out;-ms-transition: all 0.1s ease-in-out;transition: all 0.1s ease-in-out;}
                                .bordered td, .bordered th {border-left: 1px solid #ccc;border-top: 1px solid #ccc;padding: 10px;text-align: left;}.bordered th {background-color: #dce9f9;background-image: -webkit-gradient(linear, left top, left bottom, from(#ebf3fc), to(#dce9f9));background-image: -webkit-linear-gradient(top, #ebf3fc, #dce9f9);background-image:-moz-linear-gradient(top, #ebf3fc, #dce9f9);background-image:-ms-linear-gradient(top, #ebf3fc, #dce9f9);
                                background-image:-o-linear-gradient(top, #ebf3fc, #dce9f9);background-image:linear-gradient(top, #ebf3fc, #dce9f9);-webkit-box-shadow: 0 1px 0 rgba(255,255,255,.8) inset;-moz-box-shadow:0 1px 0 rgba(255,255,255,.8) inset;box-shadow: 0 1px 0 rgba(255,255,255,.8) inset;border-top: none;}.bordered td:first-child, .bordered th:first-child {border-left:none;}
                                .bordered th:first-child {-moz-border-radius: 6px 0 0 0;-webkit-border-radius: 6px 0 0 0;border-radius: 6px 0 0 0;}.bordered th:last-child {-moz-border-radius: 0;-webkit-border-radius: 0;border-radius: 0;}.bordered th:only-child{-moz-border-radius: 6px 6px 0 0;-webkit-border-radius: 6px 6px 0 0;border-radius: 6px 6px 0 0;}
                                .bordered tr:last-child td:first-child {-moz-border-radius: 0 0 0 6px;-webkit-border-radius: 0 0 0 6px;border-radius: 0 0 0 6px;}.bordered tr:last-child td:last-child {-moz-border-radius: 0 0 6px 0;-webkit-border-radius: 0 0 6px 0;border-radius: 0 0 6px 0;}.footer-tabels-text{color:#fff;background:#0363a3 !important;font-size:14px;font-weight:bold;}
                                .no-of-case{color: #0363a3 !important;border: solid 1px #38a7f1 !important;border-right: none !important;border-radius: 0px !important;}
                            </style>
                        </head>
                        
                        <div id="popupModal" class="modal fade" style="padding-left: 380px; margin-top: 2%;">
                        
                                <div class="">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i></button>
                                </div>

                            <div class=" " >
                                <div class="table-responsive"> 
                                    <table class="bordered">
                                        <thead>

                                            <tr>
                                                <th colspan="17" class="footer-tabels-text" style="text-align:center;">Sanction</th>        
                                            </tr>
                                        </thead>
                                        <tr> 
                                            <th colspan="2"  class="no-of-case" style="text-align:center !important; width:70px;">Target</th>
                                            <th colspan="2"  class="no-of-case" style="text-align:center !important; width:70px;">Today</th>
                                            <th colspan="2"  class="no-of-case" style="text-align:center !important; width:70px;">Monthly</th>
                                        </tr>
                                        <tr> 
                                            <td class="no-of-case" style="text-align:center !important;"><strong>Cases </strong></td>
                                            <td class="no-of-case" style="text-align:center !important;"><strong>Amount</strong></td> 

                                            <td class="no-of-case" style="text-align:center !important;"><strong>Cases</strong></td>
                                            <td class="no-of-case" style="text-align:center !important;"><strong>Amount</strong></td>
                                            
                                            <td class="no-of-case" style="text-align:center !important;"><strong>Cases</strong></td>
                                            <td class="no-of-case" style="text-align:center !important;"><strong>Amount</strong></td>
                                        </tr>     
                                        <tr> 
                                            <td class="no-of-case" style="text-align:center !important;"><strong>' . $monthly_target_cases . '</strong></td>
                                            <td class="no-of-case" style="text-align:center !important;"><strong>' . $monthly_target_amount . '</strong></td> 

                                            <td class="no-of-case" style="text-align:center !important;"><strong>' . $today_sanction_cases . '</strong></td>
                                            <td class="no-of-case" style="text-align:center !important;"><strong>' . $today_sanction_amount . '</strong></td>
                                                
                                            <td class="no-of-case" style="text-align:center !important;"><strong>' . $monthly_sanction_cases . '</strong></td>
                                            <td class="no-of-case" style="text-align:center !important;"><strong>' . $monthly_sanction_amount . '</strong></td>
                                        </tr>     
                                    </table>
                                </div>
                            </div> 
                            <div class=" ">
                                <div class="table-responsive"> 
                                    <table class="bordered">
                                        <thead>

                                            <tr>
                                                <th colspan="17" class="footer-tabels-text" style="text-align:center;">Disbursal</th>        
                                            </tr>
                                        </thead>
                                        <tr> 
                                            <th colspan="2"  class="no-of-case" style="text-align:center !important; width:70px;">Target</th>
                                            <th colspan="2"  class="no-of-case" style="text-align:center !important; width:70px;">Today</th>
                                            <th colspan="2"  class="no-of-case" style="text-align:center !important; width:70px;">Monthly</th> 
                                        </tr>
                                        <tr> 
                                            <td class="no-of-case" style="text-align:center !important;"><strong>Cases </strong></td>
                                            <td class="no-of-case" style="text-align:center !important;"><strong>Amount</strong></td> 

                                            <td class="no-of-case" style="text-align:center !important;"><strong>Cases </strong></td>
                                            <td class="no-of-case" style="text-align:center !important;"><strong>Amount </strong></td>

                                            <td class="no-of-case" style="text-align:center !important;"><strong>Cases</strong></td>
                                            <td class="no-of-case" style="text-align:center !important;"><strong>Amount</strong></td> 
                                        </tr> 
                                        <tr> 
                                            <td class="no-of-case" style="text-align:center !important;"><strong>' . ceil($monthly_target_cases) . '</strong></td>
                                            <td class="no-of-case" style="text-align:center !important;"><strong>' . $monthly_target_amount . '</strong></td> 

                                            <td class="no-of-case" style="text-align:center !important;"><strong>' . ceil($today_disburse_cases) . '</strong></td>
                                            <td class="no-of-case" style="text-align:center !important;"><strong>' . $today_disburse_amount . '</strong></td>

                                            <td class="no-of-case" style="text-align:center !important;"><strong>' . ceil($monthly_achieve_cases) . '</strong></td>
                                            <td class="no-of-case" style="text-align:center !important;"><strong>' . $monthly_achieve_amount . '</strong></td> 
                                        </tr> 

                                    </table>
                                </div>
                            </div> 
                            <div class=" ">
                                <div class="table-responsive"> 
                                    <table class="bordered">
                                        <thead>

                                            <tr>
                                                <th colspan="17" class="footer-tabels-text" style="text-align:center;">Run Rate</th>        
                                            </tr>
                                        </thead>
                                        <tr> 
                                            <th colspan="2"  class="no-of-case" style="text-align:center !important; width:70px;">Current</th>
                                            <th colspan="2"  class="no-of-case" style="text-align:center !important; width:70px;">Required</th>
                                        </tr>
                                        <tr> 
                                            <td class="no-of-case" style="text-align:center !important;"><strong>Cases </strong></td>
                                            <td class="no-of-case" style="text-align:center !important;"><strong>Amount</strong></td> 

                                            <td class="no-of-case" style="text-align:center !important;"><strong>Cases </strong></td>
                                            <td class="no-of-case" style="text-align:center !important;"><strong>Amount </strong></td>
                                        </tr> 
                                        <tr> 
                                            <td class="no-of-case" style="text-align:center !important;"><strong>' . ($current_run_case > 0 ? ceil($current_run_case) : 0) . '</strong></td>
                                            <td class="no-of-case" style="text-align:center !important;"><strong>' . ($current_run_amount > 0 ? number_format($current_run_amount, 2) : 0) . '</strong></td> 

                                            <td class="no-of-case" style="text-align:center !important;"><strong>' . ($required_run_case > 0 ? ceil($required_run_case) : 0) . '</strong></td>
                                            <td class="no-of-case" style="text-align:center !important;"><strong>' . ($required_run_amount > 0 ? number_format($required_run_amount, 2) : 0) . '</strong></td>
                                        </tr> 

                                    </table>
                                </div>
                            </div>
                        </div>';

            $response['popup_data'] = $message;
            echo json_encode($response);
        } else {
            return false;
        }
    }

}
