<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="assets/bootstrap.min.css">
        <link rel="stylesheet" href="assets/font-awesome.min.css">
        <title>Update Profile</title>
    </head>
    <body>
        
        <?php
        
        session_start();
        
        require_once 'User.php';

        $profile = new User();
        
        if(!isset($_SESSION['userId'])){
            // not logged in
            header('Location: login.php');
            exit();
        }
        
        $profile->setEmail($_SESSION['userId']);
        $row = $profile->getData();
        
        
        if (!empty($_POST)) {

            
            $successpassword = $profile->updateData($_SESSION['userId'],
                                                    ($_FILES['photo']['name'] != ""),  // photo input
                                                    'photo',                   // photo source input var name 
                                                    ($_POST['name'] != ''),
                                                    $_POST['name'],
                                                    ($_POST['email'] != ''),
                                                    $_POST['email'],
                                                    (($_POST['password-old'] != '')||($_POST['password-new']!= '')||($_POST['password-new-confirmed']!= '')),
                                                    $_POST['password-old'],             
                                                    $_POST['password-new'], 
                                                    $_POST['password-new-confirmed']
                                                );
                        
            if ($successpassword){
                $profile->session_logout();
            }
            
        }
        
        if (isset($_GET['logout'])){
            $profile->session_logout();
        }
        
        
        ?>
       

        
<div class="container">
    
    
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand"><?php echo $row['name']. "       (". $row['email'] . ")     " . '<h3 style="color:red">After you save changes the account will logout</h3>'; ?></a>
        <form method="get" action="" class="form-inline">
          <button name="logout" class="btn btn-outline-success my-2 my-sm-0" type="submit">Logout</button>
        </form>
    </nav>
    
    <div class="row justify-content-center">

        <div class="col-6">

        
        <form action="" method="post" id="updateForm" enctype="multipart/form-data">
            
            
            <div class="form-group">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" placeholder="Enter your name" class="form-control"  >
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" placeholder="Enter your email" class="form-control"  >
            </div>
            <div class="form-group">
                <label for="password-old">Old Password</label>
                <div class="input-group" id="show_hide_password">
                    <input name="password-old" id="password-old" type="password" placeholder="Enter your password" class="form-control" data-toggle="password" >
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <a href=""><i class="fa fa-eye-slash"></i></a>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password-new">New Password</label>
                <div class="input-group" id="show_hide_password">
                    <input id="password-new" name="password-new" type="password" placeholder="Enter new password" class="form-control" data-toggle="password" >
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <a href=""><i class="fa fa-eye-slash"></i></a>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password-new-confirmed">Confirm New Password</label>
                <div class="input-group" id="show_hide_password">
                    <input id="password-new-confirmed" name="password-new-confirmed" type="password" placeholder="Confirm new password" class="form-control" data-toggle="password" >
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <a href=""><i class="fa fa-eye-slash"></i></a>
                        </span>
                    </div>
                </div>
            </div>
            
            
            
            <div class="form-group">
                <label for="photo">Change your Photo</label>
                <input id="photo" name="photo" type="file" class="form-control-file">
            </div>
            
            <input id="hidden2" name="hidden2" type="hidden" value="false">
            <button id="submitUpdate "name="update" type="submit" class="btn btn-primary">Save Changes</button>

            
        </form>
        
      </div>
        <div class="col-3">
            
            <br/><br/><br/><br/>
            <img width="140px" height="140px" class="rounded-circle float-right"
                    <?php 
                        $location=$row['id']; 
                        if(!file_exists("photos/".$location)){$location="0";} 
                        echo 'src="photos/'.$location.'"'; 
                     ?> >
            
        </div>
    </div>
</div>
        
        <script type="text/javascript" src="assets/jquery-3.4.1.min.js"></script>
        <script type="text/javascript" src="assets/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/script.js"></script>
    </body>
</html>
