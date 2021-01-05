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
        $location = $request->location_id;
        $address = $request->address;
        $user = new User();
        echo json_encode($user->createUser($name, $lastname,$email,$phone, $password,$location,$address), JSON_UNESCAPED_UNICODE);
    }
    else{
        echo '{"sonuc" : "hatalı"}';
    }
   
}

?>