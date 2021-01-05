<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);
        $admin_id = $request->admin_id;
        $admin_token = $request->admin_token;

        $product = new Product();
        echo json_encode($product->getAllProduct($admin_id,$admin_token), JSON_UNESCAPED_UNICODE);
    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
}

?>