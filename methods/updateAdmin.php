<?php
include "../database.php";
include "../class.php";

if(isset($_GET["key"])){
    $postdata = file_get_contents("php://input");
    if(isset($postdata)){
        $request = json_decode($postdata);
        $admin_id = $request->admin_id;
        $upd_admin_id = $request->upd_admin_id;
        $admin_token = $request->admin_token;
        $name = $request->firstname;
        $lastname = $request->lastname;
        $email = $request->email;
        $phone = $request->phone;
        $password =$request->password;


        $admin = new Admin();
        echo json_encode($admin->updateAdmin($admin_id,$upd_admin_id,$admin_token,$name, $lastname,$email,$phone, $password), JSON_UNESCAPED_UNICODE);
    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }

     
}

?>