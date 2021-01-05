<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);
        $email = $request->email;
        $password = $request->password;

        $seller = new Seller();
        echo json_encode($seller->login($email,$password), JSON_UNESCAPED_UNICODE);
    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
}

?>