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
            $admin_id = $request->admin_id;

            $order = new Order();
            if(!empty($request->admin_token)){
                $admin_token = $request->admin_token;
                echo json_encode($order->updateUser($admin_id,$admin_token,$order_id), JSON_UNESCAPED_UNICODE);
            }

        }


    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
}

?>