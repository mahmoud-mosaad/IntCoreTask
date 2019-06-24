<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

try{
    
    $db = new PDO('mysql:host=localhost;dbname=IntCoreTaskDB;charset=utf8','root','');
    
} catch (Exception $ex) {
    echo $ex->getMessage();
}


