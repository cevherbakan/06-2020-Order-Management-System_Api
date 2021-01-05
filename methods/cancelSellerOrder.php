<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);
        $order_id = $request->id;
        $seller_id = $request->seller_id;
        $seller_token = $request->seller_token;
        
        $order = new Order();
        echo json_encode($order->cancelSellerOrder($seller_id,$seller_token,$order_id), JSON_UNESCAPED_UNICODE);
    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
}

?>