<?php
require 'core/load.php';
$config = include('core/config.php');
if (!isset($config['dbservername'])) {
    throw new \Exception('Servername is not defined in config.php');
}
if (!isset($config['dbusername'])) {
    throw new \Exception('DB Username is not defined in config.php');
}
if (!isset($config['dbpassword'])) {
    throw new \Exception('DB Password is not defined in config.php');
}
if (!isset($config['dbname'])) {
    throw new \Exception('DB Name is not defined in config.php');
}
$queryString = explode("?",$_SERVER['REQUEST_URI'])[0];
$slinkStr = @explode('/', trim($queryString, '/'));
if ($slinkStr[0] == '') {
    include 'view/home.php';
    return;
} else if ($slinkStr[0] == 'api') {
    if ($slinkStr[1] == 'get_short_link') {
        $link = $_POST['link'];
        if (!empty($link)) {
            echo LinkManager\getShortLink($link);
        } else {
            http_response_code(400);
            header('Content-type: application/json');
            echo json_encode([
                'status' => 400,
                'message' => 'Link is required'
            ]);
        }
    } else {
        http_response_code(404);
        header('Content-type: application/json');
        echo json_encode([
            'status' => 404,
            'message' => 'Route Not Found'
        ]);
    }
} else {
    $target = LinkManager\getTargetLink($slinkStr[0]);
    if (is_null($target)) {
        http_response_code(404);
        include 'view/404.php';
    }else{
        header("Location: $target", true, 302);
    }
}
