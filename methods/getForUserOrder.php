<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);
        
        
        if(!empty($request->user_id))
        {
            $order_id = $request->id;
            $user_id = $request->user_id;

            $order = new Order();
            if(!empty($request->user_token)){
                $user_token = $request->user_token;
                echo json_encode($order->updateUser($user_id,$user_token,$order_id), JSON_UNESCAPED_UNICODE);
            }

        }


    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
}

?>