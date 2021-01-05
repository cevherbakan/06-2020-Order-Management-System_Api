<?php

class Config
{
    private $databaseHost = 'localhost';
    private $databaseName = 'esnaf';
    private $databaseUsername = 'root';
    private $databasePassword = "";

    function db()
    {
        try {
            $mysql_connection = "mysql:host=$this->databaseHost;dbname=$this->databaseName";
            $dbconnection = new PDO($mysql_connection, $this->databaseUsername, $this->databasePassword);
            $dbconnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dbconnection;
        } catch (PDOException $e) {
            //echo "Bağlantı Hatası: " . $e->getMessage() . "<br/>";
        }
    }
}



class Crud extends Config
{
    function checkAccessForUser($user_id,$admin_id,$user_token,$admin_token){
        $result=false;


            if($admin_id != null && $admin_token != null){
                $result =$this->checkAdminToken($admin_id,$admin_token);

            }
            else if($user_id != null && $user_token != null){
                $result =$this->checkUserToken($user_id,$user_token);

            }

        return $result;
    }

    function checkAccessForSeller($seller_id,$admin_id,$seller_token,$admin_token){
        $result=false;


            if($admin_id != null && $admin_token != null){
                $result =$this->checkAdminToken($admin_id,$admin_token);

            }
            else if($seller_id != null && $seller_token != null){
                $result =$this->checkSellerToken($seller_id,$seller_token);

            }

        return $result;
    }

    function checkAccessForAdmin($admin_id,$admin_token){
        $result=false;


            if($admin_id != null && $admin_token != null){
                $result =$this->checkAdminToken($admin_id,$admin_token);

            }

        return $result;
    }

    function checkAccessForEveryone($user_id,$seller_id,$admin_id,$user_token,$seller_token,$admin_token){
        $result=false;


            if($admin_id != null && $admin_token != null){
                $result =$this->checkAdminToken($admin_id,$admin_token);

            }
            else if($seller_id != null && $seller_token != null){
                $result =$this->checkSellerToken($seller_id,$seller_token);

            }
            else if($user_id != null && $user_token != null){
                $result =$this->checkUserToken($user_id,$user_token);

            }

        return $result;
    }

    function checkAdminToken($id,$token){
        $sql = "Select id from admins WHERE id='$id' AND token = '$token' ";
        $result=$this->get($sql);
        $return=false;
        if($result){
            $return=true;
        }

        return $return;
    }
    function checkUserToken($id,$token){
        $sql = "Select id from users WHERE id='$id' AND token = '$token' ";
        $result=$this->get($sql);
        $return=false;
        if($result){
            $return=true;
        }

        return $return;
    }
    function checkSellerToken($id,$token){
        $sql = "Select id from sellers WHERE id='$id' AND token = '$token' ";
        $result=$this->get($sql);
        $return=false;
        if($result){
            $return=true;
        }

        return $return;
    }

    function insertAndUpdate($sql,$data){
        $db = $this->db();
        $sql=$db->prepare($sql);
        $sql->execute($data);
        $return = true;
        return $return;
    }

    function get($sql){
        try {
            $db = $this->db();
            $query = $db->query($sql);
            $data = $query->fetch(PDO::FETCH_OBJ); #PDO::FETCH_ASSOC
            $db=null;
            $return = false;
            
            if(!empty($data)){

                $return = $data;
            }

            return $return;

        } catch (PDOException $ex) {
            return $ex;
        }
    }

    function getAll($sql){
        try {
            $db = $this->db();
            $query = $db->query($sql);
            $data = $query->fetchAll(PDO::FETCH_OBJ); #PDO::FETCH_ASSOC
            $db=null;
            $return = false;
            
            if(!empty($data)){

                $return = $data;
            }

            return $return;

        } catch (PDOException $ex) {
            return $ex;
        }

    } 
    
}


class User extends Crud
{
    public function createUser($name,$surname,$email,$phone,$password,$location_id,$address){

        $token=md5(uniqid(rand(), true));
        $sql="INSERT INTO users (firstname,lastname,token,email,phone,password,location_id,address) VALUES (?,?,?,?,?,?,?,?)";
        $data=[$name,$surname,$token,$email,$phone,$password,$location_id,$address];
        $query["result"] = $this->insertAndUpdate($sql,$data);

        return $query;
    }

    public function getUser($user_id,$admin_id,$user_token,$admin_token){

        $result["result"]= $this->checkAccessForUser($user_id,$admin_id,$user_token,$admin_token);
       
            if($result["result"] == true){
                $sql = "Select * from users WHERE id='$user_id' ";
                $result["data"]=$this->get($sql);
            }
            return $result;
        }

    public function updateUser($user_id,$admin_id,$user_token,$admin_token,$name,$surname,$email,$phone,$password,$location_id,$address){

        $result["result"]= $this->checkAccessForUser($user_id,$admin_id,$user_token,$admin_token);
       
        if($result["result"] == true){
        $sql = "UPDATE users SET firstname = ?, lastname = ?, email = ?, phone = ?, password = ?, location_id = ?, address = ? WHERE id = '$user_id'";
        $data = [$name,$surname,$email,$phone,$password,$location_id,$address];

        $result["data"] = $this->insertAndUpdate($sql,$data);
        }

        return $result;
    }

    public function deleteUser($user_id,$admin_id,$user_token,$admin_token){
        $result["result"]= $this->checkAccessForUser($user_id,$admin_id,$user_token,$admin_token);
       
        if($result["result"] == true){
        $sql = "UPDATE users SET remove = ? WHERE id = '$user_id'";
        $data = [1];

        $result["data"] = $this->insertAndUpdate($sql,$data);
        }

        return $result;
    }

    public function getAllUser($admin_id,$admin_token){
        $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
       
        if($result["result"] == true){
        $sql = "Select * from users";
        $result["data"]=$this->getAll($sql);
        }

        return $result;
    }

    public function login($email,$password){
        $sql = "Select id from users WHERE email='$email' AND password='$password' ";
        $result["data"]=$this->get($sql);


        if($result["data"]){
            $array_result = json_decode(json_encode($result), true);
            $id= $array_result["data"]["id"];

            $token=md5(uniqid(rand(), true));
            $result["token"]= $token;
            $sql = "UPDATE users SET token = ? WHERE id = '$id' ";
            
            $data = [$token];
            $this->insertAndUpdate($sql,$data);
            
        }

        return $result;
    }
}


class Seller extends Crud
{
    public function createSeller($name,$surname,$email,$phone,$password,$location_id,$address){

        $token=md5(uniqid(rand(), true));
        $sql="INSERT INTO sellers (firstname,lastname,token,email,phone,password,location_id,address) VALUES (?,?,?,?,?,?,?,?)";
        $data=[$name,$surname,$token,$email,$phone,$password,$location_id,$address];
        $query["result"] = $this->insertAndUpdate($sql,$data);

        return $query;
    }

    public function getSeller($seller_id,$admin_id,$seller_token,$admin_token){

        $result["result"]= $this->checkAccessForUser($seller_id,$admin_id,$seller_token,$admin_token);
       
            if($result["result"] == true){
                $sql = "Select * from sellers WHERE id='$seller_id' ";
                $result["data"]=$this->get($sql);
            }
            return $result;
        }

        public function updateSeller($seller_id,$admin_id,$seller_token,$admin_token,$name,$surname,$email,$phone,$password,$location_id,$address){

            $result["result"]= $this->checkAccessForUser($seller_id,$admin_id,$seller_token,$admin_token);
           
            if($result["result"] == true){
            $sql = "UPDATE sellers SET firstname = ?, lastname = ?, email = ?, phone = ?, password = ?, location_id = ?, address = ? WHERE id = '$seller_id'";
            $data = [$name,$surname,$email,$phone,$password,$location_id,$address];
    
            $result["data"] = $this->insertAndUpdate($sql,$data);
            }
    
            return $result;
        }


        public function deleteSeller($seller_id,$admin_id,$seller_token,$admin_token){
            $result["result"]= $this->checkAccessForSeller($seller_id,$admin_id,$seller_token,$admin_token);
           
            if($result["result"] == true){
            $sql = "UPDATE sellers SET remove = ? WHERE id = '$seller_id'";
            $data = [1];
    
            $result["data"] = $this->insertAndUpdate($sql,$data);
            }
    
            return $result;
        }


        public function getAllSeller($admin_id,$admin_token){
            $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
           
            if($result["result"] == true){
            $sql = "Select * from sellers";
            $result["data"]=$this->getAll($sql);
            }
    
            return $result;
        }


        public function login($email,$password){
            $sql = "Select id from sellers WHERE email='$email' AND password='$password' ";
            $result["data"]=$this->get($sql);
    
    
            if($result["data"]){
                $array_result = json_decode(json_encode($result), true);
                $id= $array_result["data"]["id"];
    
                $token=md5(uniqid(rand(), true));
                $result["token"]= $token;
                $sql = "UPDATE sellers SET token = ? WHERE id = '$id' ";
                
                $data = [$token];
                $this->insertAndUpdate($sql,$data);
                
            }
    
            return $result;
        }

}


class Order extends Crud     
{
    public function createOrder($user_id,$admin_id,$user_token,$admin_token,$product_id, $seller_id,$quantity){
        

        $result["result"]= $this->checkAccessForUser($user_id,$admin_id,$user_token,$admin_token);
        
        if($result["result"] == true)
        {
            
        $sql="INSERT INTO orders (product_id, seller_id, user_id, quantity) VALUES (?,?,?,?)";
        $data=[$product_id, $seller_id, $user_id, $quantity];
        $result["result"] = $this->insertAndUpdate($sql,$data);
        }

        return $result;
    }


    public function getForUserOrder($user_id,$user_token,$order_id){
        $result["result"]=false;

        $result["result"]= $this->checkAccessForUser($user_id,null,$user_token,null);
       
        if($result["result"] == true){
        $sql = "Select * from orders WHERE id='$order_id' ";
        $result["data"]=$this->get($sql);

        }
        return $result;

    }
    public function getForSellerOrder($seller_id,$seller_token,$order_id){
        $result["result"]=false;

        $result["result"]= $this->checkAccessForSeller($seller_id,null,$seller_token,null);
       
        if($result["result"] == true){

        $sql = "Select * from orders WHERE id='$order_id' ";
        $result["data"]=$this->get($sql);

        }
        return $result;

    }
    
    
    public function getOrderOne($admin_id,$admin_token,$order_id){
        $result["result"]=false;

        $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
       
        if($result["result"] == true){

        $sql = "Select * from orders WHERE id='$order_id' ";
        $result["data"]=$this->get($sql);

        }
        return $result;

    }


    public function cancelCustomerOrder($user_id,$user_token,$order_id)
    {
        $result["result"]=false;

        $result["result"]= $this->checkAccessForUser($user_id,null,$user_token,null);
       
        if($result["result"] == true){
        $sql = "UPDATE orders SET cancel = ? WHERE id = '$order_id' ";
        $data = [1];

        $result["result"] = $this->insertAndUpdate($sql,$data);
        }

        return $result;
    }  

    public function cancelSellerOrder($seller_id,$seller_token,$order_id)
    {
        $result["result"]=false;

        $result["result"]= $this->checkAccessForUser($seller_id,null,$seller_token,null);
       
        if($result["result"] == true){
        $sql = "UPDATE orders SET cancel = ? WHERE id = '$order_id' ";
        $data = [2];

        $result["result"] = $this->insertAndUpdate($sql,$data);
        }

        return $result;
    }  

    public function deleteOrder($admin_id,$admin_token,$order_id)
    {
        $result["result"]=false;

        $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
       
        if($result["result"] == true){
        $sql = "UPDATE orders SET remove = ? WHERE id = '$order_id' ";
        $data = [1];

        $result["result"] = $this->insertAndUpdate($sql,$data);
        }

        return $result;
    }  

    public function getUserAllOrder($user_id,$admin_id,$user_token,$admin_token)
    {
        $result["result"]=false;

        $result["result"]= $this->checkAccessForUser($user_id,$admin_id,$user_token,$admin_token);

        if($result["result"] == true){
        $sql = "Select * from orders WHERE user_id = '$user_id' ";
        $result["data"]=$this->getAll($sql);
        }

        return $result;
    }

    public function getSellerAllOrder($seller_id,$admin_id,$seller_token,$admin_token)
    {
        $result["result"]=false;

        $result["result"]= $this->checkAccessForUser($seller_id,$admin_id,$seller_token,$admin_token);

        if($result["result"] == true){
        $sql = "Select * from orders WHERE seller_id = '$seller_id' ";
        $result["data"]=$this->getAll($sql);
        }

        return $result;
    }

    public function getAllOrder($admin_id,$admin_token)
    {
        $result["result"]=false;

        $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);

        if($result["result"] == true){
        $sql = "Select * from orders ";
        $result["data"]=$this->getAll($sql);
        }

        return $result;
    }
    public function orderConfirmation($seller_id,$seller_token){
        $result["result"]=false;

        $result["result"]= $this->checkAccessForSeller($seller_id,null,$seller_token,null);

        if($result["result"] == true){
        $sql = "UPDATE orders SET confirmation = ? WHERE id = '$order_id' ";
        $data = [1];

        $result["result"] = $this->insertAndUpdate($sql,$data);

        }
        return $result;
    }

}


class Product extends Crud
{
    public function createProduct($seller_id,$admin_id,$seller_token,$admin_token,$name,$price,$unit){
        $result["result"]=false;

        $result["result"]= $this->checkAccessForSeller($seller_id,$admin_id,$seller_token,$admin_token);
       
        if($result["result"] == true){

        $sql="INSERT INTO products (name, price, seller_id, unit) VALUES (?,?,?,?)";
        $data=[$name,$price,$seller_id,$unit];

        $result["result"] = $this->insertAndUpdate($sql,$data);

        }

        return $result;
    }

    public function getProduct($product_id){
        $sql = "Select * from products WHERE id='$product_id' ";
        $result=$this->get($sql);

        return $result;

    }
    public function updateProduct($seller_id,$admin_id,$seller_token,$admin_token,$product_id,$name,$price,$unit){

        $result["result"]=false;

        $result["result"]= $this->checkAccessForSeller($seller_id,$admin_id,$seller_token,$admin_token);
       
        if($result["result"] == true){

        $sql = "UPDATE products SET name = ?, price = ?, seller_id = ?, unit = ? WHERE id = '$product_id'";
        $data = [$name,$price,$seller_id,$unit];

        $result["result"] = $this->insertAndUpdate($sql,$data);
        }

        return $result;
    }

    public function deleteProduct($seller_id,$admin_id,$seller_token,$admin_token,$product_id){
        
        $result["result"]=false;

        $result["result"]= $this->checkAccessForSeller($seller_id,$admin_id,$seller_token,$admin_token);
       
        if($result["result"] == true){
        $sql = "UPDATE products SET remove = ? WHERE id = '$product_id'";
        $data = [1];

        $result["result"] = $this->insertAndUpdate($sql,$data);

        }

        return $result;
    }

    public function getAllProduct($admin_id,$admin_token){
        $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
           
        if($result["result"] == true){
        $sql = "Select * from products";
        $result["data"]=$this->getAll($sql);
        }

        return $result;
    }
}


class Unit extends Crud
{
    public function createUnit($admin_id,$admin_token,$name){

        $result["result"]=false;

        $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
       
        if($result["result"] == true){

            $sql="INSERT INTO units (name) VALUES (?)";
            $data=[$name];

        $result["result"] = $this->insertAndUpdate($sql,$data);

        }

        return $result;
    }


    public function getUnit($unit_id){
        $sql = "Select * from units WHERE id='$unit_id' ";
        $result=$this->get($sql);

        return $result;
    }

    public function updateUnit($admin_id,$admin_token,$unit_id,$name){
        $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
           
        if($result["result"] == true){
            $sql = "UPDATE units SET name = ? WHERE id = '$unit_id'";
            $data = [$name];
    
            $result["result"] = $this->insertAndUpdate($sql,$data);
        }

        return $result;

    }

    public function deleteUnit($admin_id,$admin_token,$unit_id){

        $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
           
        if($result["result"] == true){
            $sql = "UPDATE units SET remove = ? WHERE id = '$unit_id'";
            $data = [1];
    
            $result["result"] = $this->insertAndUpdate($sql,$data);
        }
        return $result;


    }

    public function getAllUnit($admin_id,$admin_token){

        $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
           
        if($result["result"] == true){
        $sql = "Select * from units";
        $result["data"]=$this->getAll($sql);
        }

        return $result;

    }
}

class Admin extends Crud{

    public function createAdmin($name,$surname,$email,$phone,$password){

        $token=md5(uniqid(rand(), true));
        $sql="INSERT INTO admins (firstname,lastname,token,email,phone,password) VALUES (?,?,?,?,?,?)";
        $data=[$name,$surname,$token,$email,$phone,$password];

        $result["result"] = $this->insertAndUpdate($sql,$data);
        return $result;

        
    }

    public function getAdmin($admin_id,$admin_token){

        $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
       
            if($result["result"] == true){
                $sql = "Select * from admins WHERE id='$admin_id' ";
                $result["data"]=$this->get($sql);
            }
            return $result;
        }

        public function updateAdmin($admin_id,$upd_admin_id,$admin_token,$name,$surname,$email,$phone,$password){

            $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
           
            if($result["result"] == true){
            $sql = "UPDATE admins SET firstname = ?, lastname = ?, email = ?, phone = ?, password = ? WHERE id = '$upd_admin_id'";
            $data = [$name,$surname,$email,$phone,$password];
    
            $result["data"] = $this->insertAndUpdate($sql,$data);
            }
    
            return $result;
        }

        public function deleteAdmin($admin_del_id,$admin_id,$admin_token){
            $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
           
            if($result["result"] == true){
            $sql = "UPDATE admins SET remove = ? WHERE id = '$admin_del_id'";
            $data = [1];
    
            $result["data"] = $this->insertAndUpdate($sql,$data);
            }
    
            return $result;
        }

        public function getAllAdmin($admin_id,$admin_token){
            $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
           
            if($result["result"] == true){
            $sql = "Select * from admins";
            $result["data"]=$this->getAll($sql);
            }
    
            return $result;
        }

        public function getLastAdmin($admin_id,$admin_token){
            $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
            
           
            if($result["result"] == true){
            $sql = "SELECT * FROM admins ORDER BY id DESC LIMIT 1";
            $result["data"]=$this->getAll($sql);
            }
    
            return $result;
        }

        public function getLastUser($admin_id,$admin_token){
            $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
            
           
            if($result["result"] == true){
            $sql = "SELECT * FROM users ORDER BY id DESC LIMIT 1";
            $result["data"]=$this->getAll($sql);
            }
    
            return $result;
        }

        public function getLastSeller($admin_id,$admin_token){
            $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
            
           
            if($result["result"] == true){
            $sql = "SELECT * FROM sellers ORDER BY id DESC LIMIT 1";
            $result["data"]=$this->getAll($sql);
            }
    
            return $result;
        }
        public function getLastProduct($admin_id,$admin_token){
            $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
            
           
            if($result["result"] == true){
            $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 1";
            $result["data"]=$this->getAll($sql);
            }
    
            return $result;
        }

        public function getLastOrder($admin_id,$admin_token){
            $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
            
           
            if($result["result"] == true){
            $sql = "SELECT * FROM orders ORDER BY id DESC LIMIT 1";
            $result["data"]=$this->getAll($sql);
            }
    
            return $result;
        }

        public function getLastUnit($admin_id,$admin_token){
            $result["result"]= $this->checkAccessForAdmin($admin_id,$admin_token);
            
           
            if($result["result"] == true){
            $sql = "SELECT * FROM units ORDER BY id DESC LIMIT 1";
            $result["data"]=$this->getAll($sql);
            }
    
            return $result;
        }


        public function login($email,$password){
            $sql = "Select id from admins WHERE email='$email' AND password='$password' ";
            $result["data"]=$this->get($sql);
    
    
            if($result["data"]){

                $array_result = json_decode(json_encode($result), true);
                $id= $array_result["data"]["id"];
    
                $token=md5(uniqid(rand(), true));
                $result["token"]= $token;
                $sql = "UPDATE admins SET token = ? WHERE id = '$id' ";
                
                $data = [$token];
                $result["result"]=$this->insertAndUpdate($sql,$data);
                
            }
    
            return $result;
        }

}


class Location extends Crud
{
    public function getAllCountries($user_id,$seller_id,$admin_id,$user_token,$seller_token,$admin_token){
        $result["result"]= $this->checkAccessForEveryone($user_id,$seller_id,$admin_id,$user_token,$seller_token,$admin_token);
           
        if($result["result"] == true){
            $sql = "SELECT * FROM countries";
            $result["data"]=$this->getAll($sql);

        }
        return $result;
    }

    public function getCities($country_id){

        $sql = "SELECT * FROM cities WHERE country_id = '$country_id'";
        $result["data"]=$this->getAll($sql);
        $result["result"]=true;

        return $result;

    }

    public function getDistricts($city_id){

        $sql = "SELECT * FROM districts WHERE city_id = '$city_id'";
        $result["data"]=$this->getAll($sql);
        $result["result"]=true;

        return $result;

    }

    public function getOneCountry($country_id){

        $sql = "SELECT * FROM countries WHERE id = '$country_id'";
        $result["data"]=$this->get($sql);
        $result["result"]=true;

        return $result;
    }

    public function getOneCity($city_id){
        $sql = "SELECT * FROM cities WHERE id = '$city_id'";
        $result["data"]=$this->get($sql);
        $result["result"]=true;

        return $result;

    }

    public function getOneDistrict($district_id){
        $sql = "SELECT * FROM districts WHERE id = '$district_id'";
        $result["data"]=$this->get($sql);
        $result["result"]=true;

        return $result;

    }


}


?>