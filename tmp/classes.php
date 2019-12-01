<?php

define('IDGIUD', 'id_guid');
define('PGIUD', 'id_parent_guid');
define('OWNER_ID', 'id_owner_guid');
define('SUB', 'children');
// define('SUB'   , 'sub_items');
define('DEL', 'delete');
define('ROOT_DIR'   , __DIR__);
define('CLASSES_DIR', ROOT_DIR . '/classes');

require CLASSES_DIR . '/metaData.class.php';
require CLASSES_DIR . '/dirObjects.class.php';
require CLASSES_DIR . '/wsObjects.class.php';

class BaseController {

    protected $db;
    protected $args;
    protected $errMessage;
    protected $post = array();
    protected $data = array();

    public $scheme = '';
    public $tableNameGrp = '';
    public $tableNameList = '';

    public function __construct($db, $args = array()) {
        $this -> db = $db;
        $this -> args = $args;
    }

    public function error($params) {
        throw new BaseException($params);
    }

    public function getTableFields($tableName = '') {
        if (!$tableName)
            $tableName = $this -> args[0];
        $result = $this -> db -> getTableFields($tableName);
        return $result;
    }

    public function getPropsTypes() {
        $sql = "SELECT * FROM public.props_types";
        $result = $this -> getItems($sql);
        return $result;
    }

    public function getListItem($id = '') {
        if ($id)
            $idGuid = $id;
        elseif (isset($this -> args[0]))
            $idGuid = $this -> args[0];
        else
            lg('Empty Id?');

        $sql = "SELECT * FROM " . $this -> scheme . "." . $this -> tableNameList . " WHERE id_guid = '$idGuid'";
        $result = $this -> getItem($sql);
        return $result;
    }

    public function getGrpItem($id = '') {

        if ($id)
            $idGuid = $id;
        elseif (isset($this -> args[0]))
            $idGuid = $this -> args[0];
        else
            lg('Empty Id?');

        $sql = "SELECT * FROM " . $this -> scheme . "." . $this -> tableNameGrp . " WHERE id_guid = '$idGuid'";
        $result = $this -> getItem($sql);
        return $result;
    }

    public function isField($arr, $fieldName) {
        $result = '';
        if (isset($arr[$fieldName]))
            $result = $arr[$fieldName];
        return $result;
    }

    public function identifyValueType($type, $fieldName, $value) {
        $result = $fieldName . "=";
        switch ($type) {
            case 'int' :
                $result = $result . $value;
                break;

            case 'checkbox' :
                $bool = 'FALSE';
                if ($value)
                    $bool = 'TRUE';
                $result = $result . $bool;
                break;

            case 'text' :
            case 'textarea' :
                $result = $result . "'" . $value . "'";
                break;

            default :
                $result = $result . "'" . $value . "'";
                break;
        }

        return $result;
    }

    //------ ПРИВАТНЫЕ МЕТОДЫ
    protected function getItems($sql) {
        $result = $this -> db -> select($sql);
        return $result;
    }

    /******
     protected function prepareResponse($res, $pgFuncName = false, $id = '') {

     $idName   = 'id';
     $itemName = 'item';
     $resName  = 'res';
     $error    = 'err';

     $errorMessage = $this->errMessage;

     $result  = array(
     $idName   => $id,
     $resName  => $id,
     $itemName => '',
     $error    => ''
     );

     // ------------ новая запись
     if(isset($res[$pgFuncName])) {
     $id = $res[$pgFuncName];
     if($id) {
     $result[$idName]  = $id;
     $result[$resName] = $id;
     }
     else
     $result[$error] = $errorMessage;
     }
     else {  // --- редактирование записи
     if(!$res) $result[$error] = $errorMessage;
     }

     return $result;
     }
     *******/

    protected function getPrepareResponse($res, $itemType, $pgFuncName = false, $id = '') {

        $idName = 'id';
        $itemName = 'item';
        $resName = 'res';
        $error = 'err';

        $errorMessage = $this -> errMessage;

        $item = array();

        $result = array($idName => $id, $resName => $id, $itemName => '', $error => '');

        // ------------ новая запись
        if (isset($res[$pgFuncName])) {
            $id = $res[$pgFuncName];
            if ($id) {
                $result[$idName] = $id;
                $result[$resName] = $id;
                switch ($itemType) {
                    case 'grp' :
                        $item = $this -> getGrpItem($id);
                        break;
                    case 'list' :
                        $item = $this -> getListItem($id);
                        break;
                }
                $result[$itemName] = $item;
            } else
                $result[$error] = $errorMessage;
        } else {// --- редактирование записи
            if (!$res)
                $result[$error] = $errorMessage;
            else {
                switch ($itemType) {
                    case 'grp' :
                        $item = $this -> getGrpItem($id);
                        break;
                    case 'list' :
                        $item = $this -> getListItem($id);
                        break;
                }
                $result[$itemName] = $item;
            }
        }

        return $result;
    }

    protected function getItem($sql) {
        $result = $this -> db -> fetch($sql);
        return $result;
    }

    protected function saveItem($query, $action = 'add') {
        switch ($action) {
            case 'add' :
                $result = $this -> db -> fetch($query);
                break;
            case 'edit' :
                $result = $this -> db -> _exec($query);
                break;
        }
        return $result;
    }

    protected function updateItem($query) {
        // $result = $this -> db -> _exec($query);
        $result = $this -> db -> _exec($query);
        // lg($result);
        // $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    protected function getPostData($dataName = 'data') {

        $this -> post = getPostData();
        $this -> data = (array)$this -> post[$dataName];
        return $this -> data;

    }

    protected function itemsFormatted($items) {

        $result = $parent = $subDelete = array();

        foreach ($items as $key => $values) {
            $guid = $values[IDGIUD];
            $parentGuid = $values[PGIUD];

            $sub = $this -> renderSubItems($items, $guid);
            //--- ПОЛУЧАЕМ ДОЧЕРНИЕ ОБЪЕКТЫ (РЕКУРСИВНО)

            if (!empty($sub[DEL])) {
                foreach ($sub[DEL] as $delKey => $delVal) {
                    $subDelete[$delKey] = $delVal;
                }
            }

            if (isset($subDelete[$guid]))
                continue;

            $item = array();
            $item = $values;

            $item['text'] = $values['name_max'];

            if ($parentGuid) {
                if (!empty($sub[SUB])) {
                    $item[$guid][SUB] = $sub[SUB];
                }

                if (isset($parent[$parentGuid])) {
                    $parent[$parentGuid][SUB] = $item;
                } else {
                    $parent[$parentGuid][SUB] = $item;
                }

            } else {

                if (!empty($sub[SUB])) {
                    $item[SUB] = $sub[SUB];
                }

                if (isset($parent[$guid])) {
                    $parent[$guid] = $item;
                } else {
                    $parent[$guid] = $item;
                }
            }

        }

        $result = $parent;

        return $result;

    }

    protected function renderSubItems($items, $parentId) {

        $result = $subDelete = array();

        $funcName = __FUNCTION__;

        foreach ($items as $key => $values) {
            $guid = $values[IDGIUD];
            $pGuid = $values[PGIUD];
            if ($parentId == $pGuid) {

                $sub = $this -> $funcName($items, $guid);

                if (!empty($sub[SUB])) {
                    $values[SUB] = $sub[SUB];
                }

                if (!empty($sub[DEL])) {
                    foreach ($sub[DEL] as $subKey => $subVal) {
                        if (!isset($subDelete[$subKey])) {
                            $subDelete[$subKey] = $subVal;
                        }
                    }
                }

                $subDelete[$guid] = $guid;
                $result[$guid] = $values;
            }
        }

        return array(SUB => $result, DEL => $subDelete);
    }

    //------ Для тестирования и отладки
    public function getTestTmp() {

    }

}

class DB {

    public $pdo;

    public function __construct(array $dbConf) {

        $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);

        $dsn = $dbConf['driver'] . ':dbname=' . $dbConf['dbname'] . ';host=' . $dbConf['host'] . ';port=' . $dbConf['port'];
        $this -> pdo = new PDO($dsn, $dbConf['user'], $dbConf['passwd'], $options);

    }

    public function select($query) {

        $stmt = $this -> pdo -> query($query);
        $results = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    public function fetchAll($query) {

        $stmt = $this -> pdo -> query($query);
        $results = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    public function fetch($query) {
        $stmt = $this -> pdo -> query($query);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function _exec($query) {
        $result = $this -> pdo -> exec($query);
        return $result;
    }

    public function getPdoLink() {
        return $this -> pdo;
    }

    public function getTableFields($tableName) {
        $query = "
               SELECT column_name, column_default, data_type 
               FROM INFORMATION_SCHEMA.COLUMNS 
               WHERE table_name = '" . $tableName . "'";

        $query = "SELECT column_name FROM information_schema.columns WHERE table_name =  '$tableName' ";
        $result = $this -> pdo -> exec($query);
        return $result;
    }

}

class BaseException extends Exception {
}
