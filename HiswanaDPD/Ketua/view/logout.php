<?php
 session_start();

 $_SESSION = [];

 session_unset();
 session_destroy();

 unset($_COOKIE['key']);
 setcookie('id_cookie','');


 header("Location: /index");
 exit;

 ?> 