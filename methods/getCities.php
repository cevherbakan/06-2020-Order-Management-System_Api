<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);

        $country_id = $request->country_id;

            $location = new Location();


            echo json_encode($location->getCities($country_id), JSON_UNESCAPED_UNICODE);

    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
}

?>