<?php
require_once '../../../config/app.php';

if(isset($_SESSION['authUser'])) :
    header('Location: '.base_url);
    exit;
endif;
