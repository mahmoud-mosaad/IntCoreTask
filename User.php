<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author mahmo
 */
class User {
    
    private $id;
    private $name;
    private $email;
    private $password;

    function setEmail($email){
        $this->email = $email;
    }

    function getEmail(){
        return $this->email;
    }
    
    function getPassword(){
        return $this->password;
    }
    
    
    function getData(){
        
        include 'connectDB.php';

        $query = "select * from users where email = '" . $this->email . "'";

        $stmt = $db->query($query);
        $row = $stmt->fetch();
        
        if ($stmt->rowCount() <= 0 ){
            return false;
        }
        
        return $row;
    }
    
    function updateData($oldemail, $setphoto, $photovarname, $setname, $name, $setemail, $email, $setpassword, $oldpassword, $newpassword, $confirmednewpassword){
        
        $this->setEmail($oldemail);
        $row = $this->getData();
        
        if ($setphoto){
            $this->change_photo($row['id'], $photovarname);
        }
        
        if ($setname || $setemail || $setpassword){
            
            include 'connectDB.php';
            $query = "update users set ";
            $first = true;
            $confirmpassword = false;

            if ($setname){
                $query .= " name = :name ";
                if ($first){
                    $first = false;
                }
            }
            if ($setemail){
                if ($first){
                    $query .= " email = :email ";
                    $first = false;
                }else{
                    $query .= " , email = :email ";
                }
            }
            if ($setpassword){
                if (password_verify($oldpassword, $row['password'])){
                    if ($newpassword === $confirmednewpassword){
                        if ($first){
                            $query .= " password = :password ";
                            $first = false;
                        }else{
                            $query .= ", password = :password ";
                        }
                        $confirmpassword = true;
                    }else{
                        return false;
                    }
                }
            }
            $query .= " where id = :id ";

            $stmt = $db->prepare($query);

            if ($setname){
                $stmt->bindValue(':name', $name);
            }
            if ($setemail){
                $stmt->bindValue(':email', $email);
                
                $to_mail = $oldemail;
                $subject = "Thanks";
                $msg = "Dear " . $name . " ... Your Email removed from Intcore Account after updated";

                $to_mail = $email;
                $subject = "Thanks";
                $msg = "Dear " . $name . " ... Thanks for registration in IntCore";

                
            }
            if ($setpassword && $confirmpassword){
                $stmt->bindValue(':password', password_hash($newpassword, PASSWORD_DEFAULT));
            }
            if ($first == false){
                $stmt->bindValue(':id', $row['id']);
            }
            
            $stmt->execute();
            
            return true;
        }
        

    }
    
    //function for check username and password
    function check_login($username, $password) {
        include 'connectDB.php';

        $query = "select * from users where email = '" . $username . "'";

        $stmt = $db->query($query);
        $row = $stmt->fetch();
        
        if ($stmt->rowCount() <= 0 ){
            return 1;
        }
        
        
        //checked username and password
        if (password_verify($password, $row['password'])){
            //if username and password true, then create session.
            $this->email = $username;
            $_SESSION['userId'] = $this->email;
            $this->password = password_hash($password, PASSWORD_DEFAULT);
            $this->name = $row['name'];
            $this->id = $row['id'];
            return 0;
        }else{
            return 2;
        }
    }
 
    function check_register($setphoto, $photo, $name, $email, $password){
        
        $this->setEmail($email);
        
        $row = $this->getData();
        
        if ($row == false){
            
            $to_mail = $email;
            $subject = "Thanks";
            $msg = "Dear " . $name . " ... Thanks for registration in IntCore";
            
            if (@mail($to_mail, $subject, $msg)){
                include 'connectDB.php';

                $query = "insert into users (name, email, password) values (:name, :email, :password)";

                $stmt = $db->prepare($query);
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));

                if ($stmt->execute()){

                    $id = $this->getData()['id'];
                    if ($setphoto){
                        $this->change_photo($id, $photo);
                    }

                    $_SESSION['userId'] = $email;
                    header("Location: profile.php");
                    exit();
                }
            }else{
                return false;
            }

        }else{
            return false;
        }
        
    }
    
    //function for set cookies 1 hour
    function setCookies() {
        $exp = time() + (60*60*24*30*12);
        setcookie("userId", $this->email, $exp);
        include 'connectDB.php';

        $query = "select * from users where email = '" . $this->email . "'";

        $stmt = $db->query($query);
        $row = $stmt->fetch();
        
        
        setcookie("userP", $row['password'], $exp);
    }
 
    //function for checking cookies
    function checkCookies() {
        if (isset($_COOKIE['userId'])){    
            
            include 'connectDB.php';

            $query = "select * from users where email = '" . $_COOKIE['userId'] . "'";

            $stmt = $db->query($query);
            $row = $stmt->fetch();
            
            if ($_COOKIE['userP'] === $row['password']){
                $_SESSION['userId'] = $_COOKIE['userId'];
                header("Location: profile.php"); 
                exit();
            }
        }
    }
 
    //function for checking session
    function checkSession() {
        //if user has login and session has not been removed
        if(isset($_SESSION['userId']))
        {
            //logged in so redirect
            header('Location: profile.php');
            exit();
        }
    }
 
    //function for delete sessions
    function session_logout() {
        unset($_SESSION['userId']);
        //delete all sessions
        session_destroy();
        //delete cookies
        setcookie("userId", NULL, time() - 600);
        setcookie("userP", NULL, time() - 600);
        //redirect to form login
        header("location:login.php");
        exit();
    }
    
    
    function change_photo($name, $source){
        $target_dir = "photos/";
        $target_file = $target_dir . $name;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES[$source]["tmp_name"]);
        if($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            //echo "File is not an image.";
            $uploadOk = 0;
            return false;
        }
        // Check if file already exists
        /*if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }*/
        // Check file size
        if ($_FILES[$source]["size"] > 500000) {
            //echo "Sorry, your file is too large.";
            $uploadOk = 0;
            return false;
        }
        // Allow certain file formats
        /*if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }*/
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            //echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES[$source]["tmp_name"], $target_file)) {
                //echo "The file ". basename( $_FILES[$source]["name"]). " has been uploaded.";
                return true;
            } else {
                //echo "Sorry, there was an error uploading your file.";
                return false;
            }
        }
    }
        
}
