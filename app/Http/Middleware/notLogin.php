<?php
if(!isset($_SESSION['authUser'])) :
    header('Location:resources/views/auth/login.php');
    exit;
endif;
