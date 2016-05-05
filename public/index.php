<?php

namespace application;

require_once '../controller/UserController.php';
require_once '../model/User.php';


$controller = new UserController();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $controller->handleGet();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $controller->handlePost();
} else {
    echo 'unhandled request method';
}

?>