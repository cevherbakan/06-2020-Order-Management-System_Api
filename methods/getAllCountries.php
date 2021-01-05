<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);

            $location = new Location();

            if(!empty($request->admin_token) && !empty($request->admin_id)){
                $admin_token = $request->admin_token;
                $admin_id = $request->admin_id;
                echo json_encode($location->getAllCountries(null,null,$admin_id,null,null,$admin_token), JSON_UNESCAPED_UNICODE);
            }
            else if(!empty($request->user_token) && !empty($request->user_id)){
                $user_token = $request->user_token;
                $user_id = $request->user_id;
                echo json_encode($location->getAllCountries($user_id,null,null,$user_token,null,null), JSON_UNESCAPED_UNICODE);
            }

            else if(!empty($request->seller_token) && !empty($request->seller_id)){
                $seller_token = $request->seller_token;
                $seller_id = $request->seller_id;
                echo json_encode($location->getAllCountries(null,$seller_id,null,null,$seller_token,null), JSON_UNESCAPED_UNICODE);
            }
        


    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
}

?>