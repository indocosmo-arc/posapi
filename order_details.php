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

  
		if(!empty($data->shop_id)){

     try{
			$created_on  =$data->created_on;
			$created_at  =date("Y-m-d H:i:s", strtotime($data->created_at));
     	  $shop_id  =$data->shop_id;
	  

$sql_check="SELECT *from shop_db_settings where shop_id='".$shop_id."' ";
		
		$result =  $conn->query($sql_check);

if($result->num_rows< 1) {
				http_response_code(404);
              echo json_encode(array(
                "status" => 404,
                "message" =>'No Shops Found'
              ));
           
			} else {
				$shops=array();
				 while($row =  $result->fetch_assoc() ) {
					//$servername = $row['db_server'];
					$username = $row['db_user'];
					$password = $row['db_password'];
					$database=$row['db_database'];
					
					// Create connection
					$conn = new mysqli($servername, $username, $password,$database);
					// Check connection
					if ($conn->connect_error) {
					  //die("Connection failed: " . $conn->connect_error);
					  http_response_code(500); //server error
					   echo json_encode(array(
						 "status" => 0,
						 "message" => 'Connection failed'
					   ));
					}
					//echo "Connected successfully";
					/***********************************************/
					
		  $sql_check="SELECT * FROM order_dtls WHERE order_date>='".$created_on."' and order_time>='".$created_at."' ";
	  		//print_r($sql_check);exit();
			$result =  $conn->query($sql_check);

if($result->num_rows< 1) {
				http_response_code(404);
              echo json_encode(array(
                "status" => 404,
                "message" => "No orders found"
              ));
           
			} else {
				$items=array();
				 while($row =mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					 $ss=array();
					 $ss['id']=$row['id'];
					 $ss['order_id']=$row['order_id'];
					 $ss['sale_item_id']=$row['sale_item_id'];					 
					 $ss['sale_item_code']=$row['sale_item_code'];					 
					 $ss['name']=$row['name'];
					 $ss['sub_class_name']=$row['sub_class_name'];
					 $ss['qty']=$row['qty'];
					 $ss['item_total']=$row['item_total'];
					 $ss['tax']['tax1_name']=$row['tax1_name'];
					 $ss['tax']['tax1_pc']=$row['tax1_pc'];
					 $ss['tax']['tax1_amount']=$row['tax1_amount'];
					 $ss['tax']['is_tax1_applied']=$row['is_tax1_applied'];
					  $ss['tax']['tax2_name']=$row['tax2_name'];
					 $ss['tax']['tax2_pc']=$row['tax2_pc'];
					 $ss['tax']['tax2_amount']=$row['tax2_amount'];
					 $ss['tax']['is_tax2_applied']=$row['is_tax2_applied'];
					 $ss['tax']['tax3_name']=$row['tax3_name'];
					 $ss['tax']['tax3_pc']=$row['tax3_pc'];
					 $ss['tax']['tax3_amount']=$row['tax3_amount'];
					 $ss['tax']['is_tax3_applied']=$row['is_tax3_applied'];
					 $ss['GST']['gst_name']=$row['gst_name'];
					 $ss['GST']['gst_pc']=$row['gst_pc'];
					 
					 $ss['discount']['discount_name']=$row['discount_name'];
					 $ss['discount']['discount_code']=$row['discount_code'];
					 $ss['discount']['discount_type']=$row['discount_type'];
					 $ss['discount']['discount_price']=$row['discount_price'];

					$ss['status']=$row['status'];
					$ss['order_date']=$row['order_date'];
					$ss['order_time']=$row['order_time'];
					
					 $items[]=$ss;
					 
				 }
        // http_response_code(500); //server error
         echo json_encode(array(

           "status" => 200,
           "items" => $items
         ));
       }
					/****************************************************/
					
				 }		 
       
       }
}catch(Exception $ex){

       http_response_code(500); //server error
       echo json_encode(array(
         "status" => 0,
         "message" => $ex->getMessage()
       ));
     }
	}	 else{

     http_response_code(404); // not found
     echo json_encode(array(
       "status" => 0,
       "message" => 'Invalid Shop'
     ));
   }
}

?>
