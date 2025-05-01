<?php

defined('BASEPATH') or exit('No direct script access allowed');

class AAController extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Leadmod', 'Leads');
		$this->load->model('Task_Model', 'Tasks');

		date_default_timezone_set('Asia/Kolkata');
		$timestamp = date("Y-m-d H:i:s");

		$login = new IsLogin();
		$login->index();
	}

	public function getAAconsentAllLog($leadId) {
		$lead_id = intval($this->encrypt->decode($leadId));
		$methodId = $this->input->get('methodId') ? intval($this->input->get('methodId')) : null;

		$return_data = $this->Tasks->getAAconsentAllLog($lead_id, $methodId, ['aa_id,aa_method_id,aa_request_datetime,aa_sessionId,aa_api_status_id,aa_status_message,aa_consentHandleId,aa_consentId']);

		if (empty($return_data[0])) {
			$data = array('status' => false, 'message' => 'Consent request not found.');
			echo json_encode($data);
			die;
		} else {
			$data = array('status' => true, 'message' => 'Consent request found.', 'data' => $return_data);
			echo json_encode($data);
			die;
		}
	}

	public function consentRequest($leadId) {
		$lead_id = intval($this->encrypt->decode($leadId));

		$return_data = $this->db->select('mobile,first_name,lead_id,email,status,stage,lead_status_id')->where('lead_id', $lead_id)->from('leads')->get()->row();
		if (!isset($return_data) && empty($return_data)) {
			$data = array('status' => false, 'message' => 'Lead detail is not found.');
			echo json_encode($data);
			die;
		} else {
			$user_id = !empty($_SESSION['isUserSession']['user_id']) ? $_SESSION['isUserSession']['user_id'] : "0";
			$enc_lead_id = $this->encrypt->encode($lead_id);
			$this->load->helper('aa_api_curl');
			$mobile = $return_data->mobile;
			$email = $return_data->email;
			$name = $return_data->first_name;

			$aa_request_datetime = date("Y-m-d H:i:s");
			$requestArray = array(
				"mobileNumber" => $mobile,
				"consentDescription" => "CONSENT FOR BANK STATEMENT",
				"consentArtifactName" => "BANK_STATEMENT_PERIODIC",
				"redirectUrl" => "https://paisaonsalary.in/account-consent-thank-you?refstr=" . $enc_lead_id
			);
			$json_request = json_encode($requestArray);
			$endUrl = 'accountAggregator/consent-request-plus';
			$response = sendCurl_request($json_request, $endUrl);
			/* $response = '{"result":{"encryptedRequest":"ohA0BMmD-fLAvdUkCZZgXAOp3rZpRm1D2HYbv9bBQFTCNvW2yIl-rFajq1rrZxydRxxN1iTLrg5W2Ttr57EsOnZKLWS8fYl-kBa_EQheont9B4YgUdgiiDmjQEEb0bGgGqJGgS-vu59xBvzMgclp8zXc2--PYKMhmVk6fgrlmy6ccz5cBRR3MNrAPw_tPRl0A_awjv9PCZH34fbKSd7nULG_WqcOUPAzmhWF_oCsx1Y=","requestDate":"190820241908190","encryptedFiuId":"V1BFeFlRQVVD","consentHandle":"71822cb1-27ba-49fb-b4c5-431d78fd522b","url":"https://reactjssdk.finvu.in/?ecreq=ohA0BMmD-fLAvdUkCZZgXAOp3rZpRm1D2HYbv9bBQFTCNvW2yIl-rFajq1rrZxydRxxN1iTLrg5W2Ttr57EsOnZKLWS8fYl-kBa_EQheont9B4YgUdgiiDmjQEEb0bGgGqJGgS-vu59xBvzMgclp8zXc2--PYKMhmVk6fgrlmy6ccz5cBRR3MNrAPw_tPRl0A_awjv9PCZH34fbKSd7nULG_WqcOUPAzmhWF_oCsx1Y=&reqdate=190820241908190&fi=V1BFeFlRQVVD"}}'; */
			$resArr = json_decode($response, true);
			$apiStatus = isset($resArr['result']['url']) ? 1 : 2;
			$consentHandle = isset($resArr['result']['consentHandle']) ? $resArr['result']['consentHandle'] : '';
			$apiAAlogs = [
				'aa_lead_id' => $lead_id,
				'aa_request' => $json_request,
				'aa_response' => $response,
				'aa_method_id' => 1,
				'aa_api_status_id' => $apiStatus,
				'aa_consentHandleId' => $consentHandle,
				'aa_request_datetime' => $aa_request_datetime,
				'aa_response_datetime' => date("Y-m-d H:i:s")
			];
			$aa_log_id = $this->Tasks->insert($apiAAlogs, "api_account_aggregator_logs");

			if (isset($resArr['result']) && !empty($resArr['result']) && isset($resArr['result']['url'])) {
				$url = $resArr['result']['url'];

				require_once(COMPONENT_PATH . 'CommonComponent.php');
				$CommonComponent = new CommonComponent();
				$res = $CommonComponent->call_url_shortener_api($url, $lead_id);
				$account_aggregator_register_url = $res['short_url'];
				//$account_aggregator_register_url = 'https://tinyurl.com/yu99pv5n';
				$mailResposne = $this->sendConsentRequest_url($name, $account_aggregator_register_url, $email, $mobile);

				if ($mailResposne['status']) {
					$lead_followup = [
						'lead_id' => $lead_id,
						'user_id' => $user_id,
						'status' => $return_data->status,
						'stage' => $return_data->stage,
						'lead_followup_status_id' => $return_data->lead_status_id,
						'remarks' => $mailResposne['message'],
						'created_on' => date("Y-m-d H:i:s")
					];
					$this->Tasks->insert($lead_followup, "lead_followup");
				}
				$resData['status'] = true;
				$resData['message'] = 'Account Aggregator request sent successfully';
				$resData['consentHandleId'] = $consentHandle;
				echo json_encode($resData);
				die;
			} else {
				$resData['status'] = false;
				$resData['message'] = 'Error: Please try again after sometime.';
				$resData['consentHandleId'] = $consentHandle;
				echo json_encode($resData);
				die;
			}
		}
	}

	public function consentRequestStatus($leadId, $internalCall = false) {
		$lead_id = intval($this->encrypt->decode($leadId));

		$return_data = $this->Tasks->getAAconsentLog($lead_id, 1, ['a.mobile', 'b.aa_consentHandleId']);
		if (empty($return_data)) {
			$data = array('status' => false, 'message' => 'Consent request not found.');
			echo json_encode($data);
			die;
		}
		$this->load->helper('aa_api_curl');
		$mobile = $return_data->mobile;
		$consentHandleId = $return_data->aa_consentHandleId;

		$aa_request_datetime = date("Y-m-d H:i:s");
		$requestArray = array(
			"mobileNumber" => $mobile,
			"consentHandleId" => $consentHandleId
		);
		$json_request = json_encode($requestArray);
		$endUrl = 'accountAggregator/consent-status';
		$response = sendCurl_request($json_request, $endUrl);
		//$response = '{"result":{"consentStatus":"ACCEPTED","consentId":"8a3e9070-7000-46c4-a858-a92468e1e00f"}}';

		$resArr = json_decode($response, true);
		$apiStatus = isset($resArr['result']) ? 1 : 2;
		$consentId = isset($resArr['result']['consentId']) ? $resArr['result']['consentId'] : null;
		if (isset($consentId) && !empty($consentId)) {
			$apiAAlogs = [
				'aa_lead_id' => $lead_id,
				'aa_request' => $json_request,
				'aa_response' => $response,
				'aa_method_id' => 2,
				'aa_api_status_id' => $apiStatus,
				'aa_consentHandleId' => $consentHandleId,
				'aa_consentId' => $consentId,
				'aa_status_message' => 'ACCEPTED',
				'aa_request_datetime' => $aa_request_datetime,
				'aa_response_datetime' => date("Y-m-d H:i:s")
			];
			$aa_log_id = $this->Tasks->insert($apiAAlogs, "api_account_aggregator_logs");

			$this->db->where('aa_consentHandleId', $consentHandleId)->where('aa_lead_id', $lead_id)->where('aa_method_id', 1)->update('api_account_aggregator_logs', ['aa_status_message' => 'ACCEPTED']);

			$resData['status'] = true;
			$resData['message'] = 'Request accepted.';
			$resData['data']['consentHandleId'] = $consentHandleId;
			$resData['data']['consentId'] = $consentId;
			//echo json_encode($resData); die;
		} else {
			$apiAAlogs = [
				'aa_lead_id' => $lead_id,
				'aa_request' => $json_request,
				'aa_response' => addslashes($response),
				'aa_method_id' => 2,
				'aa_api_status_id' => $apiStatus,
				'aa_consentHandleId' => $consentHandleId,
				'aa_consentId' => $consentId,
				'aa_status_message' => 'REJECTED',
				'aa_request_datetime' => $aa_request_datetime,
				'aa_response_datetime' => date("Y-m-d H:i:s")
			];
			$aa_log_id = $this->Tasks->insert($apiAAlogs, "api_account_aggregator_logs");
			$resData['status'] = false;
			$resData['message'] = 'Request not accepted.';
			$resData['data']['consentHandleId'] = $consentHandleId;
			//echo json_encode($resData); die;
		}
		if ($internalCall) {
			return $resData;
		} else {
			echo json_encode($resData);
			die;
		}
	}

	public function fiRequest($leadId) {
		$lead_id = intval($this->encrypt->decode($leadId));

		$dateFrom = $this->input->get('dateFrom');
		$dateTo = $this->input->get('dateTo');
		if (empty($dateFrom) || empty($dateTo)) {
			$data = array('status' => false, 'message' => 'dateTimeRange is not allowed to be empty.');
			echo json_encode($data);
			die;
		}
		$dateFrom = date("Y-m-d", strtotime($dateFrom));
		$dateTo = date("Y-m-d", strtotime($dateTo));

		$return_data = $this->Tasks->getAAconsentLog($lead_id, 2, ['a.mobile', 'b.aa_consentHandleId', 'b.aa_consentId']);

		if (empty($return_data)) {
			$data = array('status' => false, 'message' => 'Consent request not found.');
			echo json_encode($data);
			die;
		}
		if (empty($return_data->aa_consentId)) {
			$consentStatus = $this->consentRequestStatus($leadId, true);
			if (isset($consentStatus['status']) && $consentStatus['status'] == true && !empty($consentStatus['consentId'])) {
				$consentId = $consentStatus['consentId'];
			} else {
				$data = array('status' => false, 'message' => 'Consent request not accepted form customer side.');
				echo json_encode($data);
				die;
			}
		} else {
			$consentId = $return_data->aa_consentId;
		}
		$this->load->helper('aa_api_curl');
		$mobile = $return_data->mobile;
		$consentHandleId = $return_data->aa_consentHandleId;
		$dateTimeRF = new DateTime($dateFrom . ' 00:00:59');
		$dateTimeRangeFrom	= $dateTimeRF->format('Y-m-d\TH:i:s.vO');
		$dateTimeTF = new DateTime($dateTo . ' 23:59:59');
		$dateTimeRangeTo	= $dateTimeTF->format('Y-m-d\TH:i:s.vO');
		$aa_request_datetime = date("Y-m-d H:i:s");

		$requestArray = array(
			"customerId" => $mobile,
			"consentHandleId" => $consentHandleId,
			"consentId" => $consentId,
			"dateTimeRangeFrom" => $dateTimeRangeFrom,
			"dateTimeRangeTo" => $dateTimeRangeTo
		);
		$json_request = json_encode($requestArray);
		$endUrl = 'accountAggregator/FI-request';
		$response = sendCurl_request($json_request, $endUrl);
		//$response = '{"result": {"ver": "2.0.0","timestamp": "2024-08-20T17:15:33.334+00:00","txnid": "10d7f210-3717-4d64-938e-6d7e3d076215","consentId": "8a3e9070-7000-46c4-a858-a92468e1e00f","sessionId": "7e11e2ad-af5a-4968-a52f-7dd843385b58","consentHandleId": null} }';

		$resArr = json_decode($response, true);
		$apiStatus = isset($resArr['result']) ? 1 : 2;
		$sessionId = isset($resArr['result']['sessionId']) ? $resArr['result']['sessionId'] : null;
		if (isset($sessionId) && !empty($sessionId)) {
			$apiAAlogs = [
				'aa_lead_id' => $lead_id,
				'aa_request' => $json_request,
				'aa_response' => $response,
				'aa_method_id' => 3,
				'aa_api_status_id' => $apiStatus,
				'aa_consentHandleId' => $consentHandleId,
				'aa_consentId' => $consentId,
				'aa_sessionId' => $sessionId,
				'aa_request_datetime' => $aa_request_datetime,
				'aa_response_datetime' => date("Y-m-d H:i:s")
			];
			$aa_log_id = $this->Tasks->insert($apiAAlogs, "api_account_aggregator_logs");
			$resData['status'] = true;
			$resData['message'] = 'Request accepted.';
			echo json_encode($resData);
			die;
		} else {

			$apiAAlogs = [
				'aa_lead_id' => $lead_id,
				'aa_request' => $json_request,
				'aa_response' => $response,
				'aa_method_id' => 3,
				'aa_api_status_id' => $apiStatus,
				'aa_consentHandleId' => $consentHandleId,
				'aa_consentId' => $consentId,
				'aa_sessionId' => $sessionId,
				'aa_request_datetime' => $aa_request_datetime,
				'aa_response_datetime' => date("Y-m-d H:i:s")
			];
			$aa_log_id = $this->Tasks->insert($apiAAlogs, "api_account_aggregator_logs");

			$resData['status'] = false;
			$resData['message'] = ($resArr['error']['message']) ? $resArr['error']['message'] : 'Request not accepted.';
			$resData['resArr'] = $resArr;
			echo json_encode($resData);
			die;
		}
	}

	public function fiRequestStatus($leadId) {
		$lead_id = intval($this->encrypt->decode($leadId));

		$return_data = $this->Tasks->getAAconsentLog($lead_id, 3, ['a.mobile', 'b.aa_consentHandleId', 'b.aa_consentId', 'b.aa_sessionId']);

		if (empty($return_data)) {
			$data = array('status' => false, 'message' => 'Consent request not found.');
			echo json_encode($data);
			die;
		}

		$this->load->helper('aa_api_curl');
		$mobile = $return_data->mobile;
		$consentHandleId = $return_data->aa_consentHandleId;
		$consentId = $return_data->aa_consentId;
		$sessionId = $return_data->aa_sessionId;
		$aa_request_datetime = date("Y-m-d H:i:s");
		$requestArray = array(
			"customerId" => $mobile,
			"consentHandleId" => $consentHandleId,
			"consentId" => $consentId,
			"sessionId" => $sessionId
		);
		$json_request = json_encode($requestArray);
		$endUrl = 'accountAggregator/FI-request-status';
		$response = sendCurl_request($json_request, $endUrl);
		//$response = '{"result":{"fiRequestStatus":"READY"}}';

		$resArr = json_decode($response, true);
		$apiStatus = isset($resArr['result']) ? 1 : 2;
		$fiRequestStatus = isset($resArr['result']['fiRequestStatus']) ? $resArr['result']['fiRequestStatus'] : null;
		if (isset($fiRequestStatus) && $fiRequestStatus == "READY") {
			$apiAAlogs = [
				'aa_lead_id' => $lead_id,
				'aa_request' => $json_request,
				'aa_response' => $response,
				'aa_method_id' => 4,
				'aa_api_status_id' => $apiStatus,
				'aa_consentHandleId' => $consentHandleId,
				'aa_consentId' => $consentId,
				'aa_sessionId' => $sessionId,
				'aa_status_message' => $fiRequestStatus,
				'aa_request_datetime' => $aa_request_datetime,
				'aa_response_datetime' => date("Y-m-d H:i:s")
			];
			$aa_log_id = $this->Tasks->insert($apiAAlogs, "api_account_aggregator_logs");
			$resData['status'] = true;
			$resData['message'] = 'Financial Information is Ready.';
			echo json_encode($resData);
			die;
		} else {
			$resData['status'] = false;
			$resData['message'] = ($resArr['error']['message']) ? $resArr['error']['message'] : 'Not Ready.';
			$resData['resArr'] = $resArr;
			echo json_encode($resData);
			die;
		}
	}

	public function fiFetchData($leadId) {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');
		$lead_id = intval($this->encrypt->decode($leadId));

		$returnData = $this->Tasks->getAAconsentLog($lead_id, 5, ['b.*']);
		if (!empty($returnData)) {
			$aa_response = stripslashes($returnData->aa_response);
			$aa_response = str_replace('\\', " - ", $aa_response);
			$reportData = json_decode($aa_response, true);
			$resData['status'] = true;
			$resData['message'] = 'Financial Information is Ready.';
			$resData['data'] = $reportData;
			echo $this->createBankStatement_from_fiData($reportData['result']['body']);
			die;
			//echo json_encode($resData); die;

			//$res = json_decode($nobel_response_data_json,true);
			//print_r($nobel_response_data_json); die;
		}

		$return_data = $this->Tasks->getAAconsentLog($lead_id, 3, ['a.mobile', 'b.aa_consentHandleId', 'b.aa_consentId', 'b.aa_sessionId']);

		if (empty($return_data)) {
			$data = array('status' => false, 'message' => 'Consent request not found.');
			echo json_encode($data);
			die;
		}
		$this->load->helper('aa_api_curl');
		$mobile = $return_data->mobile;
		$consentHandleId = $return_data->aa_consentHandleId;
		$consentId = $return_data->aa_consentId;
		$sessionId = $return_data->aa_sessionId;
		$aa_request_datetime = date("Y-m-d H:i:s");
		$requestArray = array(
			"outputFormat" => "json",
			"consentHandleId" => $consentHandleId,
			"sessionId" => $sessionId
		);
		$json_request = json_encode($requestArray);
		$endUrl = 'accountAggregator/FI-fetch-data';
		$response = sendCurl_request($json_request, $endUrl);

		$response = stripslashes($response);
		$response = str_replace('\\', " - ", $response);
		$resArr = json_decode($response, true);
		$apiStatus = isset($resArr['result']) ? 1 : 2;
		if (isset($resArr['result']['body']) && !empty($resArr['result']['body'])) {
			$apiAAlogs = [
				'aa_lead_id' => $lead_id,
				'aa_request' => $json_request,
				'aa_response' => addslashes($response),
				'aa_method_id' => 5,
				'aa_api_status_id' => $apiStatus,
				'aa_consentHandleId' => $consentHandleId,
				'aa_consentId' => $consentId,
				'aa_sessionId' => $sessionId,
				'aa_status_message' => 'Report Ready',
				'aa_request_datetime' => $aa_request_datetime,
				'aa_response_datetime' => date("Y-m-d H:i:s")
			];
			$aa_log_id = $this->Tasks->insert($apiAAlogs, "api_account_aggregator_logs");
			$resData['status'] = true;
			$resData['message'] = 'Financial Information is Ready.';
			$resData['data'] = $response;

			echo $this->createBankStatement_from_fiData($resArr['result']['body']);
			die;
			//echo json_encode($resData); die;
		} else {
			echo ($resArr['error']['message']) ? $resArr['error']['message'] : 'Not Ready.';
			die;
			//$resData['status'] = false;
			//$resData['message'] = ($resArr['error']['message']) ? $resArr['error']['message'] : 'Not Ready.';
			//$resData['resArr'] = $resArr;
			//echo json_encode($resData); die;
		}
	}

	public function analyticsReport($leadId) {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');
		$lead_id = intval($this->encrypt->decode($leadId));

		$returnData = $this->Tasks->getAAconsentLog($lead_id, 6, ['b.*']);

		if (!empty($returnData)) {
			$reportData = json_decode($returnData->aa_response, true);
			$resData['status'] = true;
			$resData['message'] = 'Bank Analytics Report is Ready.';
			//$resData['data'] = $reportData['result']['data'];
			$resData['data'] = array("pdf" => $reportData['result']['pdf'], "excel" => $reportData['result']['excel']);
			echo json_encode($resData);
			die;
			//print_r($returnData); die;
		}

		$return_data = $this->Tasks->getAAconsentLog($lead_id, 5, ['a.mobile', 'b.aa_consentHandleId', 'b.aa_consentId', 'b.aa_sessionId', 'b.aa_response']);

		if (empty($return_data)) {
			$data = array('status' => false, 'message' => 'Consent request not found..');
			echo json_encode($data);
			die;
		}
		$reportData =  stripslashes($return_data->aa_response);;
		$reportData = json_decode($reportData, true);
		$linkRefNo = ($reportData['result']['body'][0]['fiObjects'][0]['linkedAccRef']) ? $reportData['result']['body'][0]['fiObjects'][0]['linkedAccRef'] : '';

		$this->load->helper('aa_api_curl');
		$mobile = $return_data->mobile;
		$consentHandleId = $return_data->aa_consentHandleId;
		$consentId = $return_data->aa_consentId;
		$sessionId = $return_data->aa_sessionId;
		$aa_request_datetime = date("Y-m-d H:i:s");
		$requestArray = array(
			"consentHandleId" => $consentHandleId,
			"sessionId" => $sessionId,
			"linkRefNo" => $linkRefNo,
			"pdf" => true,
			"excel" => true,
		);
		$json_request = json_encode($requestArray);
		$endUrl = 'accountAggregator/analytics-report';
		$response = sendCurl_request($json_request, $endUrl);
		//$response = '{"result":{"fiRequestStatus":"READY"}}';

		$resArr = json_decode($response, true);
		$apiStatus = isset($resArr['result']) ? 1 : 2;
		if (isset($resArr['result']['data']) && !empty($resArr['result']['data'])) {
			$apiAAlogs = [
				'aa_lead_id' => $lead_id,
				'aa_request' => $json_request,
				'aa_response' => $response,
				'aa_method_id' => 6,
				'aa_api_status_id' => $apiStatus,
				'aa_consentHandleId' => $consentHandleId,
				'aa_consentId' => $consentId,
				'aa_sessionId' => $sessionId,
				'aa_status_message' => 'Analytics Report Ready',
				'aa_request_datetime' => $aa_request_datetime,
				'aa_response_datetime' => date("Y-m-d H:i:s")
			];
			$aa_log_id = $this->Tasks->insert($apiAAlogs, "api_account_aggregator_logs");
			$resData['status'] = true;
			$resData['message'] = 'Analytics Report is Ready.';
			$resData['data'] = array("pdf" => $resArr['result']['pdf'], "excel" => $resArr['result']['excel']);
			echo json_encode($resData);
			die;
		} else {
			$apiAAlogs = [
				'aa_lead_id' => $lead_id,
				'aa_request' => $json_request,
				'aa_response' => $response,
				'aa_method_id' => 6,
				'aa_api_status_id' => $apiStatus,
				'aa_consentHandleId' => $consentHandleId,
				'aa_consentId' => $consentId,
				'aa_sessionId' => $sessionId,
				'aa_status_message' => 'Analytics Report Ready',
				'aa_request_datetime' => $aa_request_datetime,
				'aa_response_datetime' => date("Y-m-d H:i:s")
			];
			$aa_log_id = $this->Tasks->insert($apiAAlogs, "api_account_aggregator_logs");
			$resData['status'] = false;
			$resData['message'] = ($resArr['error']['message']) ? $resArr['error']['message'] : 'Not Ready.';
			$resData['resArr'] = $resArr;
			echo json_encode($resData);
			die;
		}
	}

	private function sendConsentRequest_url($customer_name, $account_aggregator_register_url, $email, $mobile) {
		$to = $email;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta http-equiv = "Content-Type" content = "text/html; charset=utf-8" />
					<title>Account Aggregator</title>
				</head>
				<body style="padding: 0; margin: 0;">
					<table width="600" align="center" cellspacing="0" cellpadding="0" style="border: 1px solid #bebaba; background: url(images/bg_image.jpg);background-size: cover;">
						<tbody>
							<tr>
								<td align="center">
									<table style="text-align: center;"  bgcolor="" cellspacing="0"  style="border: 1px solid #bebaba;" border="0" width="600" cellpadding="0">
										<tbody>
											<tr>
												<td style="line-height: 0; padding-top: 0;">
													<a href="#/" target="_blank">
														<img src="' . WEBSITE_URL . 'public/images/banner_account_aggregator.jpg" alt="" width="600">
													</a>
												</td>
											</tr>
										</tbody>
									</table>
									<table style="text-align: center;"  bgcolor="" cellspacing="0"  style="background: url(images/bg_image.jpg);" border="0" width="600" cellpadding="0">
										<tbody>
											<tr>
												<td colspan="3" style="border-radius: 11px; color: #000; font-size: 13px;line-height: 18px; text-align: left;padding: 26px;">

													<span width="300" cellpadding="0">
														Dear ' . ucwords($customer_name) . ',
														<br/><br/>
														We thank you for showing interest in PaisaOnSalary Instant personal loan.
														<br/><br/>Your application process is pending a crucial step, which involves obtaining your consent to access your salary bank account for retrieving the most recent bank statement.
														<br />
														In order to process your loan application further, please give your consent on our Account Aggregator portal to share your bank statement securely.
														<br /><br/>
														To facilitate the continued processing of your loan application, we kindly request your consent to securely share your bank statement through our Account Aggregator portal.
													</span>
												</td>
											</tr>
											<tr>
												<td colspan="3" style="border-radius: 11px; color: #000; font-size: 13px;line-height: 18px; text-align: center;padding: 10px;">
													<span width="300" cellpadding="0"><a href="' . $account_aggregator_register_url . '" style="border-radius: 20px;background-color: #df2b4d;border: none;color: #fff;font-size: 13px;font-weight: 600;padding: 5px 19px;margin: 2%;letter-spacing: 1px;text-decoration:none">Fetch Salary Account Bank Statement</a></span>
													<br />
													<br />
													<span width="300" cellpadding="0">If you are not able to click on the above button, then please copy and paste this URL ' . $account_aggregator_register_url . ' in the browser to proceed.</span>
												</td>
											</tr>
										</tbody>
									</table>
									<tr>
										<td colspan="3" style="border-radius: 11px; color: #000; font-size: 11px;
											line-height: 35px; text-align: center;">
											<b style="background-color: #000062;padding: 10px 10px 7px 10px;font-weight: 100;border-radius: 20px;">
												<a style="font-size: 11px;color: #fff;font-weight: 100;text-decoration: none;letter-spacing: 1px;font-family:Times New Roman;">  <img alt="Mobile: " src="' . PHONE_ICON . '"> ' . REGISTED_MOBILE . '</a> &nbsp;
												<a style="font-size: 11px;color: #fff;font-weight: 100;text-decoration: none;letter-spacing: 1px;font-family:Times New Roman">  <img alt="Webiste: " src="' . WEB_ICON . '"> ' . WEBSITE_URL . ' </a> &nbsp;
												<a style="font-size: 11px;color: #fff;font-weight: 100;text-decoration: none;letter-spacing: 1px;font-family:Times New Roman">  <img alt="Email: " src="' . EMAIL_ICON . '"> ' . INFO_EMAIL . '</a>
											</b><br/>
										</td>
									</tr>

								</td>
							</tr>
						</tbody>
					</table>
				</body>
			</html>';

		require_once(COMPONENT_PATH . 'includes/functions.inc.php');

		$return_array = common_send_email($to, BRAND_NAME . '  | CONSENT FOR BANK STATEMENT : ' . $customer_name, $message);

		if ($return_array['status'] == 1) {
			$lead_remark = "Account Aggregator email sent successfully.";
			$status = "true";
		} else {
			$lead_remark = "Account Aggregator email sending failed.";
			$status = "false";
		}

		return array('status' => $status, 'message' => $lead_remark);
	}

	private function createBankStatement_from_fiData($data) {

		$profile = $data[0]['fiObjects'][0]['Profile']['Holders']['Holder'];
		$summary = $data[0]['fiObjects'][0]['Summary'];
		$transactions = $data[0]['fiObjects'][0]['Transactions']['Transaction'];
		$table = "";

		$table .= "<div style='max-height: 500px;overflow-y: scroll;'><h2>Profile</h2>";
		$table .=  "<table class='table table-bordered'>
		<tr>
			<td><b>Name</b></td>
			<td><b>Date of Birth</b></td>
			<td><b>Mobile</b></td>
			<td><b>Nominee</b></td>
			<td><b>Landline</b></td>
			<td><b>Address</b></td>
			<td><b>Email</b></td>
			<td><b>PAN</b></td>
			<td><b>CKYC Compliance</b></td>
		</tr>
		<tr>
			<td>{$profile['name']}</td>
			<td>{$profile['dob']}</td>
			<td>{$profile['mobile']}</td>
			<td>{$profile['nominee']}</td>
			<td>{$profile['landline']}</td>
			<td>{$profile['address']}</td>
			<td>{$profile['email']}</td>
			<td>{$profile['pan']}</td>
			<td>" . ($profile['ckycCompliance'] ? 'Yes' : 'No') . "</td>
		</tr>
		</table>";

		$table .=  "<h2>Summary</h2>";
		$table .=  "<table class='table table-bordered'>
		<tr>
			<td><b>Current Balance</b></td>
			<td><b>Currency</b></td>
			<td><b>Balance Date Time</b></td>
			<td><b>Account Type</b></td>
			<td><b>Branch</b></td>
			<td><b>Facility</b></td>
			<td><b>IFSC Code</b></td>
			<td><b>Opening Date</b></td>
			<td><b>OD Limit</b></td>
			<td><b>Status</b></td>
		</tr>
		<tr>
			<td>{$summary['currentBalance']}</td>
			<td>{$summary['currency']}</td>
			<td>{$summary['balanceDateTime']}</td>
			<td>{$summary['type']}</td>
			<td>{$summary['branch']}</td>
			<td>{$summary['facility']}</td>
			<td>{$summary['ifscCode']}</td>
			<td>{$summary['openingDate']}</td>
			<td>{$summary['currentODLimit']}</td>
			<td>{$summary['status']}</td>
		</tr>
		</table>";

		$table .=  "<h2>Transactions</h2>";
		$table .=  "<table class='table table-bordered'>
		<tr><th>Type</th><th>Mode</th><th>Amount</th><th>Current Balance</th><th>Timestamp</th><th>Narration</th></tr>";

		foreach ($transactions as $transaction) {
			$table .=  "<tr>
				<td>{$transaction['type']}</td>
				<td>{$transaction['mode']}</td>
				<td>{$transaction['amount']}</td>
				<td>{$transaction['currentBalance']}</td>
				<td>{$transaction['transactionTimestamp']}</td>
				<td>{$transaction['narration']}</td>
			</tr>";
		}

		$table .=  "</table></div>";
		return $table;
	}
	public function __destruct() {
		$this->db->close();
	}
}
