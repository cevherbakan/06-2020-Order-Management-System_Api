<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);
        
        
        if(!empty($request->seller_id))
        {
            $order_id = $request->id;
            $seller_id = $request->seller_id;

            $order = new Order();
            if(!empty($request->user_token)){
                $seller_token = $request->seller_token;
                echo json_encode($order->updateUser($seller_id,$seller_token,$order_id), JSON_UNESCAPED_UNICODE);
            }

        }


    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
}

?>