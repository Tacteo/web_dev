<?php
   session_start();
   unset($_SESSION['logged_in_user']);
   header('location: login.php');
   exit();
?>