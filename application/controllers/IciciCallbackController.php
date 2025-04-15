<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IciciCallbackController extends CI_Controller {
    public function deposit_callback(){
        // Log the request method
        $request_method = $_SERVER['REQUEST_METHOD'];
        file_put_contents('logs/icici/deposit_'.date('Y-m-d').'.txt', "\n\n\n Time: ".date("F j, Y, g:i a"). " \n Request Method: " . $request_method , FILE_APPEND);

        // Log request headers
        $headers = getallheaders();
        file_put_contents('logs/icici/deposit_'.date('Y-m-d').'.txt', "\n Headers: " . json_encode($headers), FILE_APPEND);

        // Get the raw POST data
        $data = file_get_contents('php://input');

        // Check if data is empty
        if(empty($data)){
            file_put_contents('logs/icici/deposit_'.date('Y-m-d').'.txt', "\n No data received.", FILE_APPEND);
        } else {
            // Log the received data
            file_put_contents('logs/icici/deposit_'.date('Y-m-d').'.txt', "\n Data: " . $data , FILE_APPEND);
        }

        // Optionally respond to the request
        //echo json_encode(["status"=>true,"message"=>"Request accepted."]);
    }
}
