<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2024-01-15 12:52:19 --> Query error: Expression #1 of ORDER BY clause is not in SELECT list, references column 'devmunitechuat_lms.LD.lead_screener_assign_datetime' which is not in SELECT list; this is incompatible with DISTINCT - Invalid query: SELECT DISTINCT `LD`.`lead_id`, DATE_FORMAT(LD.lead_disbursal_recommend_datetime, "%d-%m-%Y %H:%i:%s") as disbursal_recommend, `LD`.`loan_no`, `LD`.`customer_id`, `LD`.`application_no`, `LD`.`lead_reference_no`, `LD`.`lead_data_source_id`, `LD`.`first_name`, `C`.`middle_name`, `C`.`sur_name`, CONCAT_WS(" ", `LD`.`first_name`, `C`.`middle_name`, C.sur_name) as cust_full_name, `LD`.`email`, `C`.`alternate_email`, `C`.`gender`, `LD`.`mobile`, `C`.`alternate_mobile`, `LD`.`obligations`, `LD`.`promocode`, `LD`.`purpose`, `LD`.`user_type`, `LD`.`pancard`, `LD`.`loan_amount`, `LD`.`tenure`, `LD`.`cibil`, `CE`.`income_type`, `CE`.`salary_mode`, `CE`.`monthly_income`, `LD`.`source`, `LD`.`utm_source`, DATE_FORMAT(C.dob, "%d-%m-%Y") AS dob, `LD`.`state_id`, `LD`.`city_id`, `LD`.`lead_branch_id`, `ST`.`m_state_name`, `CT`.`m_city_name`, `CT`.`m_city_trial_sourcing`, `LD`.`pincode`, `LD`.`status`, `LD`.`stage`, `LD`.`lead_status_id`, `LD`.`schedule_time`, `LD`.`created_on`, `LD`.`coordinates`, `LD`.`ip`, `LD`.`imei_no`, `LD`.`term_and_condition`, `LD`.`application_status`, `LD`.`lead_fi_residence_status_id`, `LD`.`lead_fi_office_status_id`, `LD`.`scheduled_date`, `CAM`.`loan_recommended` as `sanctionedAmount`, `LD`.`lead_credit_assign_user_id`, `LD`.`lead_screener_assign_user_id`, `LD`.`lead_disbursal_assign_user_id`, `user_screener`.`name` as `screenedBy`, DATE_FORMAT(LD.lead_screener_assign_datetime, "%d-%m-%Y %H:%i:%s") as screenedOn, `user_sanction`.`name` as `sanctionAssignTo`, DATE_FORMAT(LD.lead_credit_approve_datetime, "%d-%m-%Y %H:%i:%s") as sanctionedOn, `L`.`loan_status_id`, `L`.`loan_recovery_status_id`, DATE_FORMAT(LD.lead_disbursal_approve_datetime, "%d-%m-%Y %H:%i:%s"), `L`.`loan_disbursement_trans_status_id`, `C`.`customer_religion_id`, `religion`.`religion_name`, `C`.`father_name`, `branch`.`m_branch_name`, `C`.`aadhar_no`, `C`.`customer_digital_ekyc_flag`, `C`.`customer_marital_status_id`, `C`.`customer_spouse_name`, `C`.`customer_spouse_occupation_id`, `C`.`customer_qualification_id`, `C`.`customer_docs_available`, `CAM`.`repayment_date`, `CAM`.`cam_sanction_letter_esgin_on`, `MRT`.`m_marital_status_name` as `marital_status`, `MOC`.`m_occupation_name` as `occupation`, `MQ`.`m_qualification_name` as `qualification`
FROM `leads` `LD`
LEFT JOIN `lead_customer` `C` ON `C`.`customer_lead_id` = `LD`.`lead_id`
LEFT JOIN `customer_employment` `CE` ON `CE`.`lead_id` = `LD`.`lead_id` AND `CE`.`emp_active`=1 AND `CE`.`emp_deleted`=0
LEFT JOIN `credit_analysis_memo` `CAM` ON `CAM`.`lead_id` = `LD`.`lead_id` AND `CAM`.`cam_active`=1 AND `CAM`.`cam_deleted`=0
LEFT JOIN `loan` `L` ON `L`.`lead_id` = `LD`.`lead_id` AND `L`.`loan_active`=1 AND `L`.`loan_deleted`=0
LEFT JOIN `master_state` `ST` ON `ST`.`m_state_id` = `LD`.`state_id`
LEFT JOIN `master_city` `CT` ON `CT`.`m_city_id` = `LD`.`city_id`
LEFT JOIN `master_religion` `religion` ON `religion`.`religion_id` = `C`.`customer_religion_id`
LEFT JOIN `master_qualification` `MQ` ON `MQ`.`m_qualification_id` = `C`.`customer_qualification_id`
LEFT JOIN `master_occupation` `MOC` ON `MOC`.`m_occupation_id` = `C`.`customer_spouse_occupation_id`
LEFT JOIN `master_marital_status` `MRT` ON `MRT`.`m_marital_status_id` = `C`.`customer_marital_status_id`
LEFT JOIN `master_branch` `branch` ON `branch`.`m_branch_id` = `LD`.`lead_branch_id`
LEFT JOIN `master_data_source` `DS` ON `DS`.`data_source_id` = `LD`.`lead_data_source_id`
LEFT JOIN `users` `user_screener` ON `user_screener`.`user_id` = `LD`.`lead_screener_assign_user_id`
LEFT JOIN `users` `user_sanction` ON `user_sanction`.`user_id` = `LD`.`lead_credit_assign_user_id`
WHERE `LD`.`stage` = 'S2'
AND `LD`.`lead_active` = 1
AND `LD`.`lead_deleted` = 0
ORDER BY `LD`.`lead_screener_assign_datetime` DESC
 LIMIT 20
ERROR - 2024-01-15 12:52:19 --> Severity: error --> Exception: Call to a member function result() on bool /home/devmunitechuat/public_html/application/views/Tasks/GetLeadTaskList.php 332
ERROR - 2024-01-15 12:52:22 --> Query error: Expression #1 of ORDER BY clause is not in SELECT list, references column 'devmunitechuat_lms.LD.lead_screener_assign_datetime' which is not in SELECT list; this is incompatible with DISTINCT - Invalid query: SELECT DISTINCT `LD`.`lead_id`, DATE_FORMAT(LD.lead_disbursal_recommend_datetime, "%d-%m-%Y %H:%i:%s") as disbursal_recommend, `LD`.`loan_no`, `LD`.`customer_id`, `LD`.`application_no`, `LD`.`lead_reference_no`, `LD`.`lead_data_source_id`, `LD`.`first_name`, `C`.`middle_name`, `C`.`sur_name`, CONCAT_WS(" ", `LD`.`first_name`, `C`.`middle_name`, C.sur_name) as cust_full_name, `LD`.`email`, `C`.`alternate_email`, `C`.`gender`, `LD`.`mobile`, `C`.`alternate_mobile`, `LD`.`obligations`, `LD`.`promocode`, `LD`.`purpose`, `LD`.`user_type`, `LD`.`pancard`, `LD`.`loan_amount`, `LD`.`tenure`, `LD`.`cibil`, `CE`.`income_type`, `CE`.`salary_mode`, `CE`.`monthly_income`, `LD`.`source`, `LD`.`utm_source`, DATE_FORMAT(C.dob, "%d-%m-%Y") AS dob, `LD`.`state_id`, `LD`.`city_id`, `LD`.`lead_branch_id`, `ST`.`m_state_name`, `CT`.`m_city_name`, `CT`.`m_city_trial_sourcing`, `LD`.`pincode`, `LD`.`status`, `LD`.`stage`, `LD`.`lead_status_id`, `LD`.`schedule_time`, `LD`.`created_on`, `LD`.`coordinates`, `LD`.`ip`, `LD`.`imei_no`, `LD`.`term_and_condition`, `LD`.`application_status`, `LD`.`lead_fi_residence_status_id`, `LD`.`lead_fi_office_status_id`, `LD`.`scheduled_date`, `CAM`.`loan_recommended` as `sanctionedAmount`, `LD`.`lead_credit_assign_user_id`, `LD`.`lead_screener_assign_user_id`, `LD`.`lead_disbursal_assign_user_id`, `user_screener`.`name` as `screenedBy`, DATE_FORMAT(LD.lead_screener_assign_datetime, "%d-%m-%Y %H:%i:%s") as screenedOn, `user_sanction`.`name` as `sanctionAssignTo`, DATE_FORMAT(LD.lead_credit_approve_datetime, "%d-%m-%Y %H:%i:%s") as sanctionedOn, `L`.`loan_status_id`, `L`.`loan_recovery_status_id`, DATE_FORMAT(LD.lead_disbursal_approve_datetime, "%d-%m-%Y %H:%i:%s"), `L`.`loan_disbursement_trans_status_id`, `C`.`customer_religion_id`, `religion`.`religion_name`, `C`.`father_name`, `branch`.`m_branch_name`, `C`.`aadhar_no`, `C`.`customer_digital_ekyc_flag`, `C`.`customer_marital_status_id`, `C`.`customer_spouse_name`, `C`.`customer_spouse_occupation_id`, `C`.`customer_qualification_id`, `C`.`customer_docs_available`, `CAM`.`repayment_date`, `CAM`.`cam_sanction_letter_esgin_on`, `MRT`.`m_marital_status_name` as `marital_status`, `MOC`.`m_occupation_name` as `occupation`, `MQ`.`m_qualification_name` as `qualification`
FROM `leads` `LD`
LEFT JOIN `lead_customer` `C` ON `C`.`customer_lead_id` = `LD`.`lead_id`
LEFT JOIN `customer_employment` `CE` ON `CE`.`lead_id` = `LD`.`lead_id` AND `CE`.`emp_active`=1 AND `CE`.`emp_deleted`=0
LEFT JOIN `credit_analysis_memo` `CAM` ON `CAM`.`lead_id` = `LD`.`lead_id` AND `CAM`.`cam_active`=1 AND `CAM`.`cam_deleted`=0
LEFT JOIN `loan` `L` ON `L`.`lead_id` = `LD`.`lead_id` AND `L`.`loan_active`=1 AND `L`.`loan_deleted`=0
LEFT JOIN `master_state` `ST` ON `ST`.`m_state_id` = `LD`.`state_id`
LEFT JOIN `master_city` `CT` ON `CT`.`m_city_id` = `LD`.`city_id`
LEFT JOIN `master_religion` `religion` ON `religion`.`religion_id` = `C`.`customer_religion_id`
LEFT JOIN `master_qualification` `MQ` ON `MQ`.`m_qualification_id` = `C`.`customer_qualification_id`
LEFT JOIN `master_occupation` `MOC` ON `MOC`.`m_occupation_id` = `C`.`customer_spouse_occupation_id`
LEFT JOIN `master_marital_status` `MRT` ON `MRT`.`m_marital_status_id` = `C`.`customer_marital_status_id`
LEFT JOIN `master_branch` `branch` ON `branch`.`m_branch_id` = `LD`.`lead_branch_id`
LEFT JOIN `master_data_source` `DS` ON `DS`.`data_source_id` = `LD`.`lead_data_source_id`
LEFT JOIN `users` `user_screener` ON `user_screener`.`user_id` = `LD`.`lead_screener_assign_user_id`
LEFT JOIN `users` `user_sanction` ON `user_sanction`.`user_id` = `LD`.`lead_credit_assign_user_id`
WHERE `LD`.`stage` = 'S2'
AND `LD`.`lead_active` = 1
AND `LD`.`lead_deleted` = 0
ORDER BY `LD`.`lead_screener_assign_datetime` DESC
 LIMIT 20
ERROR - 2024-01-15 12:52:22 --> Severity: error --> Exception: Call to a member function result() on bool /home/devmunitechuat/public_html/application/views/Tasks/GetLeadTaskList.php 332
ERROR - 2024-01-15 12:52:26 --> Query error: Expression #1 of ORDER BY clause is not in SELECT list, references column 'devmunitechuat_lms.LD.lead_screener_assign_datetime' which is not in SELECT list; this is incompatible with DISTINCT - Invalid query: SELECT DISTINCT `LD`.`lead_id`, DATE_FORMAT(LD.lead_disbursal_recommend_datetime, "%d-%m-%Y %H:%i:%s") as disbursal_recommend, `LD`.`loan_no`, `LD`.`customer_id`, `LD`.`application_no`, `LD`.`lead_reference_no`, `LD`.`lead_data_source_id`, `LD`.`first_name`, `C`.`middle_name`, `C`.`sur_name`, CONCAT_WS(" ", `LD`.`first_name`, `C`.`middle_name`, C.sur_name) as cust_full_name, `LD`.`email`, `C`.`alternate_email`, `C`.`gender`, `LD`.`mobile`, `C`.`alternate_mobile`, `LD`.`obligations`, `LD`.`promocode`, `LD`.`purpose`, `LD`.`user_type`, `LD`.`pancard`, `LD`.`loan_amount`, `LD`.`tenure`, `LD`.`cibil`, `CE`.`income_type`, `CE`.`salary_mode`, `CE`.`monthly_income`, `LD`.`source`, `LD`.`utm_source`, DATE_FORMAT(C.dob, "%d-%m-%Y") AS dob, `LD`.`state_id`, `LD`.`city_id`, `LD`.`lead_branch_id`, `ST`.`m_state_name`, `CT`.`m_city_name`, `CT`.`m_city_trial_sourcing`, `LD`.`pincode`, `LD`.`status`, `LD`.`stage`, `LD`.`lead_status_id`, `LD`.`schedule_time`, `LD`.`created_on`, `LD`.`coordinates`, `LD`.`ip`, `LD`.`imei_no`, `LD`.`term_and_condition`, `LD`.`application_status`, `LD`.`lead_fi_residence_status_id`, `LD`.`lead_fi_office_status_id`, `LD`.`scheduled_date`, `CAM`.`loan_recommended` as `sanctionedAmount`, `LD`.`lead_credit_assign_user_id`, `LD`.`lead_screener_assign_user_id`, `LD`.`lead_disbursal_assign_user_id`, `user_screener`.`name` as `screenedBy`, DATE_FORMAT(LD.lead_screener_assign_datetime, "%d-%m-%Y %H:%i:%s") as screenedOn, `user_sanction`.`name` as `sanctionAssignTo`, DATE_FORMAT(LD.lead_credit_approve_datetime, "%d-%m-%Y %H:%i:%s") as sanctionedOn, `L`.`loan_status_id`, `L`.`loan_recovery_status_id`, DATE_FORMAT(LD.lead_disbursal_approve_datetime, "%d-%m-%Y %H:%i:%s"), `L`.`loan_disbursement_trans_status_id`, `C`.`customer_religion_id`, `religion`.`religion_name`, `C`.`father_name`, `branch`.`m_branch_name`, `C`.`aadhar_no`, `C`.`customer_digital_ekyc_flag`, `C`.`customer_marital_status_id`, `C`.`customer_spouse_name`, `C`.`customer_spouse_occupation_id`, `C`.`customer_qualification_id`, `C`.`customer_docs_available`, `CAM`.`repayment_date`, `CAM`.`cam_sanction_letter_esgin_on`, `MRT`.`m_marital_status_name` as `marital_status`, `MOC`.`m_occupation_name` as `occupation`, `MQ`.`m_qualification_name` as `qualification`
FROM `leads` `LD`
LEFT JOIN `lead_customer` `C` ON `C`.`customer_lead_id` = `LD`.`lead_id`
LEFT JOIN `customer_employment` `CE` ON `CE`.`lead_id` = `LD`.`lead_id` AND `CE`.`emp_active`=1 AND `CE`.`emp_deleted`=0
LEFT JOIN `credit_analysis_memo` `CAM` ON `CAM`.`lead_id` = `LD`.`lead_id` AND `CAM`.`cam_active`=1 AND `CAM`.`cam_deleted`=0
LEFT JOIN `loan` `L` ON `L`.`lead_id` = `LD`.`lead_id` AND `L`.`loan_active`=1 AND `L`.`loan_deleted`=0
LEFT JOIN `master_state` `ST` ON `ST`.`m_state_id` = `LD`.`state_id`
LEFT JOIN `master_city` `CT` ON `CT`.`m_city_id` = `LD`.`city_id`
LEFT JOIN `master_religion` `religion` ON `religion`.`religion_id` = `C`.`customer_religion_id`
LEFT JOIN `master_qualification` `MQ` ON `MQ`.`m_qualification_id` = `C`.`customer_qualification_id`
LEFT JOIN `master_occupation` `MOC` ON `MOC`.`m_occupation_id` = `C`.`customer_spouse_occupation_id`
LEFT JOIN `master_marital_status` `MRT` ON `MRT`.`m_marital_status_id` = `C`.`customer_marital_status_id`
LEFT JOIN `master_branch` `branch` ON `branch`.`m_branch_id` = `LD`.`lead_branch_id`
LEFT JOIN `master_data_source` `DS` ON `DS`.`data_source_id` = `LD`.`lead_data_source_id`
LEFT JOIN `users` `user_screener` ON `user_screener`.`user_id` = `LD`.`lead_screener_assign_user_id`
LEFT JOIN `users` `user_sanction` ON `user_sanction`.`user_id` = `LD`.`lead_credit_assign_user_id`
WHERE `LD`.`stage` = 'S2'
AND `LD`.`lead_active` = 1
AND `LD`.`lead_deleted` = 0
ORDER BY `LD`.`lead_screener_assign_datetime` DESC
 LIMIT 20
ERROR - 2024-01-15 12:52:26 --> Severity: error --> Exception: Call to a member function result() on bool /home/devmunitechuat/public_html/application/views/Tasks/GetLeadTaskList.php 332
