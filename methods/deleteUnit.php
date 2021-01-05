<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);
        $unit_id = $request->unit_id;
        $admin_id = $request->admin_id;
        $admin_token = $request->admin_token;

        $unit = new Unit();
        echo json_encode($unit->deleteUnit($admin_id,$admin_token,$unit_id), JSON_UNESCAPED_UNICODE);
    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
}

?>