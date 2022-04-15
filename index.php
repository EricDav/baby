<?php
    header('Access-Control-Allow-Origin: *'); 
    $url = explode('?', $_SERVER['REQUEST_URI'])[0];

    if ($url === '/fixtures') {
        include 'fixtures.php'; exit;
    } else if ($url === '/results') {
        include 'results.php'; exit;
    }

?>