<?php
session_start();
unset($_SESSION['authUser']);
header('Location:login.php');
session_destroy();
exit;
