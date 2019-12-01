<?php

define('IDGIUD', 'id_guid');
define('PGIUD', 'id_parent_guid');
define('OWNER_ID', 'id_owner_guid');
define('SUB', 'children');
// define('SUB'   , 'sub_items');
define('DEL', 'delete');

class GroupObjectTypes extends BaseController {

    //------ ПУБЛИЧНЫЕ МЕТОДЫ
    public function getGrpObjects() {
        
        $sql = "SELECT * FROM dir.ot_grp ORDER BY id, npp";
        $result = $this -> getItems($sql);
        $result = $this -> itemsFormatted($result);

        /*****************
         $sql = "SELECT * FROM dir.ot_grp as grp
         LEFT OUTER JOIN dir.props_grp as props
         ON grp.id_guid = props.id_parent_guid";
         //LEFT OUTER JOIN  dir.ot_grp.
         //WHERE id_guid = 'A79BF49094324FA9BBFE0C3E8D455BEA'";

         $sql = "SELECT * FROM dir.props_grp";
         $result = $this->getItems($sql);
         $result = $this->itemsFormatted($result);
         lg($result);
         ***************/

        return $result;
    }

    public function getListObjects() {
        $parentId = $this -> args[0];
        $sql = "SELECT * FROM dir.ot_list WHERE id_owner_guid = '$parentId' ORDER BY npp";
        $result = $this -> getItems($sql);
        // $result = $this->itemsFormatted($result);
        return $result;
    }

    public function addGrpObjects() {

        /******************
        $post = getPostData();
        $data = (array)$post['data'];
        $parentId = $data[PGIUD];
        $nameMax = $data['name_max'];
        if (!$nameMax)
            lg('Не заполнено обязательное поле: Полное имя');

        $query = "SELECT * FROM ot_grp_add('$parentId',  '$nameMax');";

        $res = $this -> saveItem($query);
        $result['res'] = true;
        return $result;
        ****************/
        
        $pgFuncName = 'ot_grp_add';
        
        $data = $this -> getPostData();

        $parentId = $this->isField($data, PGIUD);
        $nameMax  = $this->isField($data , 'name_max');
        if (!$nameMax)  lg('Не заполнено обязательное поле: Полное имя');
        if (!$parentId) lg('Не заполнено обязательное поле: Id родителя');
        
        
        $nameMin  = $this->isField($data , 'name_min');
        $note  = $this->isField($data    , 'note');
        $code  = $this->isField($data    , 'code');
        $mnemo  = $this->isField($data   , 'mnemocode');

        $query = "SELECT * FROM " .$pgFuncName. "('$parentId',  '$nameMax', '$nameMin', '$note', '$code', '$mnemo');";
        
        // lg($query);
        
        $res = $this->saveItem($query);
        $result['res'] = $res;
        return $result;
        
    }

    public function addListObjects() {

        /*******
        $post = getPostData();
        $data = (array)$post['data'];
        $parentId = $data[PGIUD];
        $nameMax = $data['name_max'];
        if (!$nameMax)
            lg('Не заполнено обязательное поле: Полное имя');

        $query = "SELECT * FROM ot_list_add('$parentId',  '$nameMax');";

        $res = $this -> saveItem($query);
        $result['res'] = $res;
        return $result;
        ********/
        
        $err = array();
        
        $pgFuncName = 'ot_list_add';
        
        $data = $this -> getPostData();
        // $parentId = $data[PGIUD];

        // --- обязательные параметры
        $propType = $this->isField($data , 'prop_type');
        $parentId = $this->isField($data , OWNER_ID);
        $nameMax  = $this->isField($data , 'name_max');

        if (!$parentId) $err[] = 'Не заполнено обязательное поле: Id владельца';
        if (!$nameMax)  $err[] = 'Не заполнено обязательное поле: Полное наименование';
        
        if(!empty($err)) lg($err);
        
        if (!$propType) $propType = "'pt_is_str()'";
        
        $idLinkGuid = $this->isField($data , 'id_link_guid');
        $nameMin = $this->isField($data , 'name_min');
        $note    = $this->isField($data , 'note');
        $code    = $this->isField($data , 'code');
        $mnemo   = $this->isField($data , 'mnemocode');

        /******
        par_idguid - id_guid группы типов объектов (обязательный параметр)
        new_namemax - полное наименование создаваемого типа  (обязательный параметр)
        new_namemin - сокращенное наименование создаваемого типа
        new_note - примечание к создаваемому типу  
        new_code - пользовательский код создаваемого типа
        new_mnemo - мнемокод создаваемого типа
        *******/

        $query = "SELECT * FROM " .$pgFuncName. "('$parentId', '$nameMax', '$nameMin', '$note', '$code', '$mnemo');";

        $res = $this -> saveItem($query);
        
        // lg($res);
       
        $result['res'] = true;
        
    }

    public function editGrpObjects() {

        $result = array();
        $data = $this -> getPostData();

        $idGuid = $data[IDGIUD];
        $nameMax = $data['name_max'];
        $data['yes_force_update'] = 'TRUE';

        if (!$idGuid) lg('Не id объекта');

        $queryModel = array();
        
        $itemModel = array(
        
            'code' => 'text', 
            'name_max' => 'text', 
            'name_min' => 'text', 
            'mnemocode' => 'text', 
            'note' => 'textarea', 
            'npp' => 'int', 
            'yes_edit' => 'checkbox', 
            'yes_del' => 'checkbox', 
            'yes_move' => 'checkbox', 
            'markfordel' => 'checkbox',
            'info' => 'text', 
            'yes_force_update' => 'checkbox'
            
        );
        
        foreach ($itemModel as $fieldName => $fieldType) {
            if (!isset($data[$fieldName])) continue;
            $value = $data[$fieldName];
            $queryRow = $this->identifyValueType($fieldType, $fieldName, $value);
            if ($queryRow)
                $queryModel[$fieldName] = $queryRow;
        }

        $sqlString = implode(',', $queryModel);

        $query = "UPDATE dir.ot_grp SET  " . $sqlString . "
                  WHERE id_guid = '" . $idGuid . "' ";
       
        $res = $this -> saveItem($query);
        $result['res'] = $res;
        return $result;
        
    }

    public function editListObject() {
       
        $result = array();
        $data = $this -> getPostData();

        $idGuid = $data[IDGIUD];
        $nameMax = $data['name_max'];
        $data['yes_force_update'] = 'TRUE';

        if (!$idGuid) lg('Не id объекта');

        $queryModel = array();
        
        $itemModel = array(
        
            'code' => 'text', 
            'name_max' => 'text', 
            'name_min' => 'text', 
            'mnemocode' => 'text', 
            'note' => 'textarea', 
            'npp' => 'int', 
            'yes_edit' => 'checkbox', 
            'yes_del' => 'checkbox', 
            'yes_move' => 'checkbox', 
            'markfordel' => 'checkbox',
            'info' => 'text', 
            'yes_force_update' => 'checkbox'
            
        );
        
        
        /*******
          its_abstract = НовоеЗначение,
          its_dir = НовоеЗначение,
          its_link_only = НовоеЗначение,
          id_link_guid = ‘НовоеЗначение’,
          id_pict = НовоеЗначение,
          model_info = ‘НовоеЗначение’,
          model_info_l = ‘НовоеЗначение’,
          model2_info = ‘НовоеЗначение’,
          model2_info_l = ‘НовоеЗначение’,
          script_name_calc = ‘НовоеЗначение’, 
          script_name_calc_l = ‘НовоеЗначение’,
          yes_script_sel = НовоеЗначение,
          script_sel = ‘НовоеЗначение’,
          yes_force_update = TRUE
        *********/
        
        foreach ($itemModel as $fieldName => $fieldType) {
            if (!isset($data[$fieldName])) continue;
            $value = $data[$fieldName];
            $queryRow = $this->identifyValueType($fieldType, $fieldName, $value);
            if ($queryRow)
                $queryModel[$fieldName] = $queryRow;
        }

        $sqlString = implode(',', $queryModel);

        $query = "UPDATE dir.ot_list SET  " . $sqlString . "
                  WHERE id_guid = '" . $idGuid . "' ";
       
        $res = $this -> saveItem($query);
        $result['res'] = $res;
        
        return $result;
        
    }

    public function getItemId($tableName, $uid, $fieldName = 'id_guid') {

        $sql = 'SELECT * FROM ' . $tableName . ' WHERE ' . $fieldName . ' = "' . $uid . '"';
        $result = $this -> getItem($sql);
        return $result;
    }

    public function getChildrens($tableName, $uid = 0, $fieldName = 'id_parent_guid') {

        $sql = "SELECT * FROM $tableName WHERE $fieldName = '$uid' ORDER BY npp";
        $result = $this -> getItems($sql);
        return $result;

    }

    //------ Приватные методы

}

//---- ГРУППА СВОЙСТВ ОБЪЕКТОВ

class GroupPropsObjects extends BaseController {

    //------ ПУБЛИЧНЫЕ МЕТОДЫ

    public function getGrpObjects() {
        $sql = "SELECT * FROM dir.props_grp ORDER BY id";
        $result = $this -> getItems($sql);
        $result = $this -> itemsFormatted($result);
        return $result;
    }

    public function getListObjects() {
        
        $parentId = $this -> args[0];
        
        // LEFT JOIN public.props_types AS props ON props.id_guid = list.prop_type
        $sql = "SELECT *,
                (select * from proptype_name_get(dpl99.prop_type)) pt_name
                FROM dir.props_list dpl99
                WHERE id_owner_guid = '$parentId' ORDER BY npp";
        $result = $this -> getItems($sql);
        // lg($sql);
        
        // $result = $this->itemsFormatted($result);
        return $result;
    }

    public function addGrpObjects() {

        $data = $this -> getPostData();

        $parentId = $this->isField($data, PGIUD);
        $nameMax  = $this->isField($data , 'name_max');
        if (!$nameMax)  lg('Не заполнено обязательное поле: Полное имя');
        if (!$parentId) lg('Не заполнено обязательное поле: Id родителя');
        
        
        $nameMin  = $this->isField($data , 'name_min');
        $note  = $this->isField($data    , 'note');
        $code  = $this->isField($data    , 'code');
        $mnemo  = $this->isField($data   , 'mnemocode');

        $query = "SELECT * FROM prop_grp_add('$parentId',  '$nameMax', '$nameMin', '$note', '$code', '$mnemo');";
        
        $res = $this->saveItem($query);
        $result['res'] = $res;
        return $result;
    }

    public function addListObjects() {

        $err = array();
        
        $data = $this -> getPostData();
        // $parentId = $data[PGIUD];

        // --- обязательные параметры
        $propType = $this->isField($data , 'prop_type');
        $parentId = $this->isField($data , OWNER_ID);
        $nameMax  = $this->isField($data , 'name_max');

        if (!$parentId) $err[] = 'Не заполнено обязательное поле: Id владельца';
        if (!$nameMax)  $err[] = 'Не заполнено обязательное поле: Полное наименование';
        
        if(!empty($err)) lg($err);
        
        if (!$propType) $propType = "'pt_is_str()'";
        
        $idLinkGuid = $this->isField($data , 'id_link_guid');
        $nameMin = $this->isField($data , 'name_min');
        $note    = $this->isField($data , 'note');
        $code    = $this->isField($data , 'code');
        $mnemo   = $this->isField($data , 'mnemocode');

        // lg($data);

        $query = "SELECT * FROM prop_list_add('$parentId',  $propType, '$idLinkGuid', '$nameMax', '$nameMin', '$note', '$code', '$mnemo');";

        $res = $this -> saveItem($query);
       
        $result['res'] = $res;
        
        return $result;
    }

    public function editGrpObjects() {

        $result = array();
        $data = $this -> getPostData();

        $idGuid = $data[IDGIUD];
        $nameMax = $data['name_max'];
        $data['yes_force_update'] = 'TRUE';

        if (!$idGuid)
            lg('Не id объекта');

        $queryModel = array();
        
        $itemModel = array(
        
            'code' => 'text', 
            'name_max' => 'text', 
            'name_min' => 'text', 
            'mnemocode' => 'text', 
            'note' => 'textarea', 
            'npp' => 'int', 
            'yes_edit' => 'checkbox', 
            'yes_del' => 'checkbox', 
            'yes_move' => 'checkbox', 
            'markfordel' => 'checkbox',
            'info' => 'text', 
            'yes_force_update' => 'checkbox'
            
        );
        
        foreach ($itemModel as $fieldName => $fieldType) {
            if (!isset($data[$fieldName])) continue;
            $value = $data[$fieldName];
            $queryRow = $this->identifyValueType($fieldType, $fieldName, $value);
            if ($queryRow)
                $queryModel[$fieldName] = $queryRow;
        }

        $sqlString = implode(',', $queryModel);

        $query = "UPDATE dir.props_grp SET  " . $sqlString . "
                  WHERE id_guid = '" . $idGuid . "' ";
        // lg($query);

        $res = $this -> saveItem($query);
        $result['res'] = $res;
        return $result;
    }

    public function editListObject() {

        $result = array();
        $post = getPostData();
        $data = (array)$post['data'];

        $idGuid = $data[IDGIUD];
        $nameMax = $data['name_max'];
        $data['yes_force_update'] = 'TRUE';
        
        if (!$idGuid)
            lg('Нет id объекта');

        $queryModel = array();

        $itemModel = array(
        
            'code' => 'text', 
            'name_max' => 'text', 
            'name_min' => 'text', 
            'mnemocode' => 'text', 
            'note' => 'textarea', 
            'npp' => 'int', 
            'yes_edit' => 'checkbox', 
            'yes_mnemo_edit' => 'checkbox', 
            'yes_del' => 'checkbox', 
            'yes_move' => 'checkbox', 
            'markfordel' => 'checkbox', 
            'info' => 'text', 
            'id_link_guid' => 'text', 
            'yes_script_sel' => 'checkbox', 
            'script_sel' => 'text', 
            'yes_force_update' => 'checkbox'
            
        );


        foreach ($itemModel as $fieldName => $fieldType) {
            if (!isset($data[$fieldName])) continue;
            $value = $data[$fieldName];
            $queryRow = $this->identifyValueType($fieldType, $fieldName, $value);
            if ($queryRow)
                $queryModel[$fieldName] = $queryRow;
        }

        
        $sqlString = implode(',', $queryModel);

        $query = "UPDATE dir.props_list SET " . $sqlString . "
                  WHERE id_guid = '" . $idGuid . "' ";
        // lg($queryModel);

        $res = $this -> saveItem($query);
        $result['res'] = $res;

        return $result;
    }

    public function getItemId($tableName, $uid, $fieldName = 'id_guid') {

        $sql = 'SELECT * FROM ' . $tableName . ' WHERE ' . $fieldName . ' = "' . $uid . '"';
        $result = $this -> getItem($sql);
        return $result;
    }

    public function getChildrens($tableName, $uid = 0, $fieldName = 'id_parent_guid') {

        $sql = "SELECT * FROM $tableName WHERE $fieldName = '$uid' ORDER BY npp";
        $result = $this -> getItems($sql);
        return $result;

    }

    //------ ПРИВАТНЫЕ МЕТОДЫ

}

class BaseController {

    protected $db;
    protected $args;
    protected $post = array();
    protected $data = array();

    public function __construct($db, $args = array()) {
        $this -> db = $db;
        $this -> args = $args;
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
    
    public function isField($arr, $fieldName) {
        $result = '';
        if(isset($arr[$fieldName]))
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

    protected function getItem($sql) {
        $result = $this -> db -> fetch($sql);
        return $result;
    }

    protected function saveItem($query) {
        $result = $this -> db -> _exec($query);
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
        // $sql = "SELECT * FROM dir.dir_tree LIMIT 4";
        // $sql = "SELECT * FROM ws.obj_tree LIMIT 10";

        /***
         * WHERE list.id_guid = '13540F60A1934885A2F7618F6A83C930'
         */
        $sql = "
                  SELECT 

                     grpar.name_max as grpar_name,
                     grp.name_max as grp_name,
                     list.name_max as list_name,
                     par.name_max as par_name,
                     tree.name_max as tree_name                    
                     
                   FROM dir.dir_tree AS tree
                   
                   LEFT JOIN dir.ot_list AS list 
                   ON tree.id_type_guid = list.id_guid
                   
                   LEFT JOIN dir.dir_tree AS par 
                   ON par.id_guid = tree.id_parent_guid
                   
                   LEFT JOIN dir.ot_grp AS grp
                   ON grp.id_guid = list.id_owner_guid
                   
                   LEFT JOIN dir.ot_grp AS grpar
                   ON grpar.id_guid = grp.id_parent_guid
                   
                   LIMIT 400  
           ";

        $sql = "
                  SELECT 

                     grp_parent.name_max AS grp_parent_name,
                     grp.name_max AS grp_name,
                     list.name_max  AS list_name,
                     obj_parent.name_max AS obj_parent_name,
                     obj.name_max  AS obj_name

                   FROM ws.obj_tree AS obj
                   
                   LEFT JOIN dir.ot_list AS list 
                   ON list.id_guid = obj.id_type_guid 
                   
                   LEFT JOIN ws.obj_tree AS obj_parent 
                   ON obj_parent.id_guid = obj.id_parent_guid
                   
                   LEFT JOIN dir.ot_grp AS grp
                   ON grp.id_guid = list.id_owner_guid
                   
                   LEFT JOIN dir.ot_grp AS grp_parent
                   ON grp_parent.id_guid = grp.id_parent_guid
                   
                   LIMIT 10  
                   
           ";

        $sql = "
                  SELECT 

                     grp_parent.name_max AS grp_p_name,
                     grp.name_max        AS grp_name,
                     list.name_max       AS list_name,
                     obj_parent.name_max AS p_name,
                     obj.name_max        AS name,
                     
                     props_list.name_max AS proplist_name,

                     list.id_guid        AS list_id,
                     obj_parent.id_guid  AS parent_id,
                     obj.id_guid         AS cur_id,
                     
                     obj.*

                   FROM ws.obj_tree AS obj
                   
                   LEFT JOIN ws.obj_tree AS obj_parent 
                   ON obj_parent.id_guid = obj.id_parent_guid
                   
                   LEFT JOIN ws.obj_props AS props 
                   ON props.id_owner_guid = obj.id_guid
                   
                   LEFT JOIN dir.props_list AS props_list 
                   ON props_list.id_guid = props.id_prop_guid
                   
                   LEFT JOIN dir.ot_list AS list 
                   ON list.id_guid = obj.id_type_guid 

                   LEFT JOIN dir.ot_grp AS grp
                   ON grp.id_guid = list.id_owner_guid
                   
                   LEFT JOIN dir.ot_grp AS grp_parent
                   ON grp_parent.id_guid = grp.id_parent_guid
                   
                   LIMIT 10  
                   
           ";

        $result = $this -> getItems($sql);
        // $result = $this->itemsFormatted($result);
        lg($result);
        return $result;
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
        $results = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $results;
    }

    public function _exec($query) {
        $result = $this -> pdo -> exec($query);
        return $result;

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
