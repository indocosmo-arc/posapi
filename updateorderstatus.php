<?php
//ini_set("display_errors", 1);

require 'vendor/autoload.php';
use \Firebase\JWT\JWT;
//require_once 'jwt_utils.php';
//include headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-type: application/json; charset=utf-8");

// including files
include_once("db_connect.php");
<<<<<<< Updated upstream
if($_SERVER['REQUEST_METHOD'] === "POST"){
		
		$data = json_decode(file_get_contents("php://input"));
		$headers = getallheaders();
		if(!empty($data->order_id) && !empty($data->shop_id) ){
=======
if($_SERVER['REQUEST_METHOD'] === "GET"){

   // body
   $data = json_decode(file_get_contents("php://input"));
	//$headers = getallheaders();
  
		if( !empty($data->shop_code)){
>>>>>>> Stashed changes

		try{

			$jwt = $headers["Authorization"];
			 if (!empty($jwt)) {
				if (preg_match('/Bearer\s(\S+)/', $jwt, $matches)) {  
					$jwt= $matches[1];
				}
        }

			$secret_key = "owt125";

			$decoded_data = JWT::decode($jwt, $secret_key, array('HS512'));
			
			//$decoded_data = JWT::verify($jwt, $secret_key, array('HS512'));
			//$msg, $signature, $key, $alg
			//print_r($headers["Authorization"]);exit();
			$order_id  =$data->order_id;
			$shop_id  =$data->shop_id;
			$status  =$data->status;
			$updated_at  =$data->updated_at;

			$sql_check="SELECT *FROM order_details  WHERE order_id = '".$order_id."' and shop_id='".$shop_id."' ";
		$result =  $conn->query($sql_check);
	
			if($result->num_rows< 1) {
				http_response_code(404);
              echo json_encode(array(
                "status" => 404,
                "message" => "Invalid credentials"
              ));
           
			} else {
				$sql="UPDATE order_details SET order_status = '".$status."' WHERE order_id = '".$order_id."' and shop_id='".$shop_id."' ";

				if ($conn->query($sql) === TRUE) {
					
					$response['order_id'] = $order_id;
					$response['status_code'] = 201;
					if($status=='accepted'){
						$response['message'] = "Order Accept Status Updated  Successfully";
					}elseif($status==3){
						$response['message'] = "KOT Print Status  Updated  Successfully";
					}
					elseif($status==4){
						$response['message'] = "KOT Print Status  Updated  Successfully";
					}
					elseif($status=='delivered'){
						$response['message'] = "Order dispatched Status Updated Successfully";
					}
					elseif($status=='cancelled'){
						$response['message'] = "Order Canceled Successfully";
					}
					
					echo json_encode($response);
				   
				} else{

					 http_response_code(500); //server error
					 echo json_encode(array(

					   "status" => 500,
					   "message" => "Failed to update Status"
					 ));
				}
			}
		}catch(Exception $ex){

			   http_response_code(500); //server error
			   echo json_encode(array(
				 "status" => 401,
				 "message" => 'error'
				 //"message" => $ex->getMessage()
			   ));
		}
	}	 
	else{

		 http_response_code(404); // not found
		 echo json_encode(array(
		   "status" => 404,
		   "message" => "All data needed"
		 ));
	}
}


$conn->close();
?>