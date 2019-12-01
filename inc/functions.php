<?php


function getResponse($result) {
    $result = json_encode($result);
    die($result);
}


function lg() {

    $out = '';
    $get = false;
    $style = 'margin:10px; padding:10px; border:3px red solid;';
    $args = func_get_args();
    foreach ($args as $key => $value) {
        $itemArr = array();
        $itemStr = '';
        is_array($value) ? $itemArr = $value : $itemStr = $value;
        if ($itemStr == 'get')
            $get = true;

        $line = print_r($value, true);
        $out .= '<div style="' . $style . '" ><pre>' . $line . '</pre></div>';
    }

    if ($get)
        return $out;
    print $out;
    exit ;

}

function router() {

    $result = $pathInfo = array();
    $result['method'] = $_SERVER['REQUEST_METHOD'];
    $result['controller'] = 'GroupObjectTypes';
    $result['action'] = 'getGrpObjects';
    $result['params'] = array();

    if (isset($_SERVER['PATH_INFO']))
        $pathInfo = explode('/', $_SERVER['PATH_INFO']);

    // lg($_SERVER['REQUEST_METHOD']);

    foreach ($pathInfo as $key => $value) {
        switch ($key) {
            case 0 :
                break;
            case 1 :
                $result['controller'] = $value;
                break;
            case 2 :
                $result['action'] = $value;
                break;
            default :
                $result['params'][] = $value;
                break;
        }
    }

    return $result;

}

function getArgs() {

    $result = array();
    $result['method'] = $_SERVER['REQUEST_METHOD'];
    $result['controller'] = 'GroupObjectTypes';
    $result['action'] = 'getGrpObjects';
    $result['params'] = array();

    if (!empty($_GET['controller'])) {
        $result['controller'] = $_GET['controller'];
    }

    if (!empty($_GET['action'])) {
        $result['action'] = $_GET['action'];
    }

    if (!empty($_GET['params'])) {
        $result['params'] = $_GET['params'];
    }

    return $result;

}

function getPostData($f1 = '', $f2 = '') {

    $post = array();
    $post = (array)json_decode(file_get_contents('php://input'));

    if (empty($post))
        lg('Ошибка нет post-данных -> (api.php)');

    if ($f1) {
        if (isset($post[$f1]))
            $post = (array)$post[$f1];
    }

    if ($f2) {
        if (isset($post[$f2]))
            $post = (array)$post[$f2];
    }

    return $post;
}

function isEmpty($arr, $fieldName) {
    $result = '';
    if (!empty($arr[$fieldName])) {
        $result = $arr[$fieldName];
    }
    return $result;
}


?>