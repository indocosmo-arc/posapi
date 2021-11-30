<?php
//ini_set("display_errors", 1);

//require 'vendor/autoload.php';
//use \Firebase\JWT\JWT;

//include headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-type: application/json; charset=utf-8");

// including files
include_once("db_connect.php");
if($_SERVER['REQUEST_METHOD'] === "GET"){

   // body
   $data = json_decode(file_get_contents("php://input"));
	//$headers = getallheaders();
 
		if(!empty($data->shop_code) &&  $data->status>=0 ){
			
		try{
			/* $jwt = $headers["Authorization"];
			if (preg_match('/Bearer\s(\S+)/', $headers["Authorization"], $matches)) {
				$jwt = $matches[1];
			}
			$secret_key = "owt125";

			$decoded_data = JWT::decode($jwt, $secret_key, array('HS512')); 
			 */
			
			$shop_code =$data->shop_code;
			$status =$data->status;
			
			$sql_check="select id from shop where code = '$shop_code'";
			$result = $conn->query($sql_check);

			if($result->num_rows< 1) {
				http_response_code(200);
				echo json_encode(array(
                "status" => 200,
                "message" =>'No Shops Found'
              ));
           
			} else {
					
					
					$sql3="UPDATE shop SET `online_order_status`= '".$status."' WHERE code='".$shop_code."' ";

					if ($conn->query($sql3) === TRUE) {
							
						
						http_response_code(201); //server error
						 echo json_encode(array(

						   "status" => 201,
						   "message" => "Status Updated  Successfully"
						 ));
						//echo json_encode($response);
						   
					} else{

						 http_response_code(500); //server error
						 echo json_encode(array(

						   "status" => 500,
						   "message" => "Invalid credentials"
						 ));
						}
					}
					/****************************************************/
					
				 //}		 
       
       
}catch(Exception $ex){

       http_response_code(401); //server error
       echo json_encode(array(
         "status" => 401,
         "message" => " Invalid Token"
       ));
     }
	}	 else{

     http_response_code(404); // not found
     echo json_encode(array(
       "status" => 0,
       "message" => 'All Data Needed'
     ));
   }
}

?>