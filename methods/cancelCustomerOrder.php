<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);
        $order_id = $request->id;
        $user_id = $request->user_id;
        $user_token = $request->user_token;
        $order_id = $request->order_id;

        $order = new Order();
        echo json_encode($order->cancelCustomerOrder($user_id,$user_token,$order_id), JSON_UNESCAPED_UNICODE);
    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
}

?>