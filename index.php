<?php

/////////////////////
/////////////////////

//--- заголовки для принятия запросов с удаленных Url
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$result = $params = $router = array();

require_once 'bootstrap.php';

try {

    $db     = new DB($Config['db']);
    
    // $tab = $db->getTableFields('props_list');
    // var_export($tab); die();
    
    $route  = new Router($Routes, $db);
    $result = $route->run(); 

}
catch(Exception $e) { 
    lg($e, 'Exception');
}


getResponse($result);

/////////////////////
/////////////////////


 
?>