<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);
        $id=$request->id;


            $product = new Product();
            if(!empty($request->seller_token)&& !empty($request->seller_id)){
                $seller_token = $request->seller_token;
                echo json_encode($product->deleteProduct($seller_id,null,$seller_token,null,$id), JSON_UNESCAPED_UNICODE);
            }
            else if(!empty($request->admin_token) && !empty($admin_id = $request->admin_id)){
                $admin_token = $request->admin_token;
                $admin_id = $request->admin_id;
                echo json_encode($product->deleteProduct(null,$admin_id,null,$admin_token,$id), JSON_UNESCAPED_UNICODE);
            }
        

    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
}
?>