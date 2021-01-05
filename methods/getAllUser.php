<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);

            $user = new User();

            if(!empty($request->admin_token) && !empty($admin_id = $request->admin_id)){
                $admin_token = $request->admin_token;
                $admin_id = $request->admin_id;
                echo json_encode($user->getAllUser($admin_id,$admin_token), JSON_UNESCAPED_UNICODE);
            }
        
    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
}

?>