<?php

// namespace Reestr;

class BaseController {

    protected $db;
    protected $args;
    protected $errMessage;
    public    $errMessageArr = array();
    protected $post = array();
    protected $data = array();

    public $scheme        = '';
    public $table         = '';
    public $tableName     = '';
    public $tableNameGrp  = '';
    public $tableNameList = '';
    
    use BaseTrait;
    // use BaseObjectsHelper;
    
    public function __construct($db, $params = array()) {
        $this ->db   = $db;
        $this ->args = $params;
    }
    
    public function getArg($index = 0) {
        $result = false;
        if(!empty($this->args[$index])) 
            $result = $this->args[$index];
        return $result;
    }
    
    public function getParam($index = 0) {
        return $this->getArg($index);
    }

    public function error($params = '') {
       
        $debugTrace = '';
        if(DEBUG_TRACE) {
            $debugTrace = debug_backtrace();
            if(DEBUG_TRACE_TOOGLE) {
                $debugTrace = $this->debugTraceToogle($debugTrace);
                $debugTrace = array_reverse($debugTrace);
            }    
        }  
          
        if(!empty($this->errMessageArr))
           lg($params, $this->errMessageArr, $debugTrace);
        
        lg($params, $debugTrace);
        // throw new BaseException($params);
    }
    
    public function debugTraceToogle($arr = array()) {
        $result = array();
        foreach ($arr as $key => $value) {
           $item = array();
           foreach ($value as $vKey => $val) { 
               switch ($vKey) {
                   case 'file':
                   case 'function':    
                   case 'line':    
                   case 'class':
                       $item[$vKey] = $val;           
                       break;
               }
           }
           $result[$key] = $item;
        }    
       return $result;    
    }
    
    
    public function getTableFields($tableName = '') {
        if (!$tableName) $tableName = $this ->getParam(0);
        $result = $this ->db >getTableFields($tableName);
        return $result;
    }

    public function getPropsTypes() {
        $sql = "SELECT * FROM public.props_types";
        $result = $this -> getItems($sql);
        return $result;
    }

    public function _getListItem($id = '') {
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

    public function _getGrpItem($id = '') {

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
            case 'hidden'   : $result = '';  break;
            case 'int'      : $result = $result . $value; break;
            
            case 'text'     :
            case 'textarea' : $result = $result . "'" . $value . "'"; break;

            case 'checkbox' :
                $bool = 'FALSE';
                if ($value) $bool = 'TRUE';
                $result = $result . $bool;
                break;

            default : $result = $result . "'" . $value . "'";  break;
        }

        return $result;
    }

    //------ ПРИВАТНЫЕ МЕТОДЫ
    protected function getItems($sql) {
        $result = $this -> db -> select($sql);
        return $result;
    }

    public function loadModel($tableName, $fileName) {
        $modelFileName = MODELS_DIR . '/' .$fileName;
        if(!file_exists($modelFileName)) 
            $this->error('Not file module -' . $modelFileName);
        
        $models = require $modelFileName;
        $model  = $models[$tableName];
        // lg($model);
        return $model;
    }

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
        } 
        else {  // --- редактирование записи
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
    public function getTestTmp() {}

}
