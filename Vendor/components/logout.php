<?php
    session_start();
    require '../_database/database.php';
    session_destroy();
    header('location:http://localhost/gathbandhanpyaarka/index.php/home?logout=success');
?>