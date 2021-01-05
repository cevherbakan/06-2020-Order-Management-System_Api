

<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);
        $admin_id = $request->admin_id;
        $admin_token = $request->admin_token;

        $admin = new Admin();
        echo json_encode($admin->getLastSeller($admin_id,$admin_token), JSON_UNESCAPED_UNICODE);
    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
}

?>