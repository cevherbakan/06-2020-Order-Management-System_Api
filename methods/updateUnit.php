<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);
        $id = $request->unit_id;
        $name = $request->unit_name;
        $admin_id = $request->admin_id;
        $admin_token = $request->admin_token;

        $unit = new Unit();
        echo json_encode($unit->updateUnit($admin_id,$admin_token,$id,$name), JSON_UNESCAPED_UNICODE);
    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
   
}

?>