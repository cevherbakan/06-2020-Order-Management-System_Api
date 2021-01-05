<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);

        $name = $request->firstname;
        $lastname = $request->lastname;
        $email = $request->email;
        $phone = $request->phone;
        $password =$request->password;
        $location =$request->location_id;
        $address = $request->address;


        if(!empty($request->seller_id))
        {
            $seller_id = $request->seller_id;

            $seller = new Seller();
            if(!empty($request->seller_token)){
                $seller_token = $request->seller_token;
                echo json_encode($seller->updateSeller($seller_id,null,$seller_token,null,$name, $lastname,$email,$phone, $password,$location,$address), JSON_UNESCAPED_UNICODE);
            }
            else if(!empty($request->admin_token) && !empty($admin_id = $request->admin_id)){
                $admin_token = $request->admin_token;
                $admin_id = $request->admin_id;
                echo json_encode($seller->updateSeller($seller_id,$admin_id,null,$admin_token,$name, $lastname,$email,$phone, $password,$location,$address), JSON_UNESCAPED_UNICODE);
            }
        }


    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
   
}

?>