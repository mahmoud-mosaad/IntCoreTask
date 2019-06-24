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
        <title>Registration</title>
    </head>
    <body>
        
    <?php
    
        session_start();
    
        require_once 'User.php';

        $user = new User();
        
        $user->checkSession();

        $user->checkCookies();
        
        if (!empty($_POST)) {
                
            $msg = $user->check_register(isset($_POST['photo']), "photo", $_POST['name'], $_POST['email'], $_POST['password']);

            if (!$msg){
                echo "<script type='text/javascript'>alert('Your Email is repeated or invalid');</script>";

            }

        }
    ?>
<div class="container">
    <div class="row justify-content-center">
      <div class="col-6">

        
        <form action="" method="post" id="registerForm">
            
            <div class="form-group">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" placeholder="Enter your name" class="form-control" value="<?php echo (isset($_POST['name']) ? $_POST['name'] : ''); ?>" required>   <br/>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" placeholder="Enter your email" class="form-control"  value="<?php echo (isset($_POST['email']) ? $_POST['email'] : ''); ?>" required>    <br/>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group" id="show_hide_password">
                    <input name="password" id="password" type="password" placeholder="Enter your password" class="form-control" data-toggle="password" aria-describedby="passwordHelp" required>   <br/>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <a href=""><i class="fa fa-eye-slash"></i></a>
                        </span>
                    </div>
                </div>
                <small id="passwordHelp" class="form-text text-muted">
                    Your password must be 8-20 characters long, contain letters, underscore and numbers, and must not contain spaces, special characters, or emoji, And at least one capital character and number.
                </small>
            </div>
            
            <div class="form-group">
                <label for="password-confirmed">Confirm Password</label>
                <div class="input-group" id="show_hide_password">
                    <input id="password-confirmed" name="password-confirmed" type="password" placeholder="Confirm your password" class="form-control" data-toggle="password" required>   <br/>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <a href=""><i class="fa fa-eye-slash"></i></a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="photo">Your Picture</label>
                <input id="photo" name="photo" type="file" class="form-control-file">  <br/>
            </div>

            <button id="submitRegister" name="register" type="submit" class="btn btn-primary">Register</button>
        </form>
        <br/>
        Already registered? <a href="login.php">Login</a>

        
      </div>
    </div>
</div>
        
        <script type="text/javascript" src="assets/jquery-3.4.1.min.js"></script>
        <script type="text/javascript" src="assets/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/script.js"></script>
    </body>
</html>
