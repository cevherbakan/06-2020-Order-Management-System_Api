<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);
        $unit_id = $request->id;

        $unit = new Unit();
        echo json_encode($unit->getUnit($unit_id), JSON_UNESCAPED_UNICODE);
    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
}

?>