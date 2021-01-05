<?php
include "../database.php";
include "../class.php";


if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);
        $name = $request->unit_name;
        $admin_id = $request->admin_id;
        $admin_token = $request->admin_token;

        $unit = new Unit();
        echo json_encode($unit->createUnit($admin_id,$admin_token,$name), JSON_UNESCAPED_UNICODE);
    }
    else{
        $sonuc['sonuc']='başarısız';
        echo json_encode($sonuc, JSON_UNESCAPED_UNICODE);
    }
   
}

?>