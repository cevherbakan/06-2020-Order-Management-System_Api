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


        if(!empty($request->user_id))
        {
            $user_id = $request->user_id;

            $user = new User();
            if(!empty($request->user_token)){
                $user_token = $request->user_token;
                echo json_encode($user->updateUser($user_id,null,$user_token,null,$name, $lastname,$email,$phone, $password,$location,$address), JSON_UNESCAPED_UNICODE);
            }
            else if(!empty($request->admin_token) && !empty($admin_id = $request->admin_id)){
                $admin_token = $request->admin_token;
                $admin_id = $request->admin_id;
                echo json_encode($user->updateUser($user_id,$admin_id,null,$admin_token,$name, $lastname,$email,$phone, $password,$location,$address), JSON_UNESCAPED_UNICODE);
            }
        }


    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
   
}

?>