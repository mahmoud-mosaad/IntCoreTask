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
        <title>Login</title>
    </head>
    <body>
        <?php 
        
            session_start();
        
            require_once 'User.php';
            
            $user = new User();
            
            $user->checkSession();
            
            $user->checkCookies();
            
            if (!empty($_POST)) {
                
                $login = $user->check_login($_POST['email'], $_POST['passwordL']);
                
                if ($login == 0){
                    if (isset($_POST['rememberme'])){
                        $user->setCookies();
                    }
                    header("Location: profile.php"); 
                    exit();
                }elseif ($login == 2){
                    echo "<script type='text/javascript'>alert('Your Email or password is wrong');</script>";
                }elseif ($login == 1){
                    echo "<script type='text/javascript'>alert('Your Email is not registered');</script>";
                }
                                
            }
        
        
        ?>
<div class="container">
    <div class="row justify-content-center">
      <div class="col-6">

          
          <br/><br/><br/><br/><br/><br/>
          
        <form action="" method="post" id="loginForm">
            
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" placeholder="Enter your email" class="form-control" value="<?php echo (isset($_POST['email']) ? $_POST['email'] : ''); ?>" required>    <br/>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group" id="show_hide_password">
                    <input name="passwordL" id="passwordL" type="password" placeholder="Enter your password" class="form-control" data-toggle="password" required>   <br/>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <a href=""><i class="fa fa-eye-slash"></i></a>
                        </span>
                    </div>
                </div>
            </div>
            
           
            <div class="form-group form-check">
                <input name="rememberme" type="checkbox" class="form-check-input" id="exampleCheck1" value="1">
              <label class="form-check-label" for="exampleCheck1">Remember Me</label>
            </div>

            <button id="submitLogin" name="login" type="submit" class="btn btn-primary">Login</button>
            
        </form>
        <br/>
        Don't have account? <a href="index.php">Register</a>

      </div>
    </div>
</div>
        
        <script type="text/javascript" src="assets/jquery-3.4.1.min.js"></script>
        <script type="text/javascript" src="assets/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/script.js"></script>
    </body>
</html>
