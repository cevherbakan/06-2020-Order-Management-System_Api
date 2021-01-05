<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);

        $district_id = $request->district_id;

            $location = new Location();


            echo json_encode($location->getOneDistrict($district_id), JSON_UNESCAPED_UNICODE);

    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
}

?>