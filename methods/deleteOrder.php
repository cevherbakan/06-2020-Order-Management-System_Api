<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);
        $admin_id = $request->admin_id;
        $admin_token = $request->admin_token;
        $order_id = $request->order_id;

        $order = new Order();
        echo json_encode($order->deleteOrder($admin_id,$admin_token,$order_id), JSON_UNESCAPED_UNICODE);
    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
}

?>