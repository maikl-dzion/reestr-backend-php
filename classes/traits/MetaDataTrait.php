<?php


trait MetaDataTrait {
    
    //###############################
    // -----   Выборка из базы        ----
    public function getGrpObjects() {

        $where = '';
        $parentId = $this ->getParam(0);
        if($parentId) 
           $where = " WHERE id_parent_guid = '$parentId' ";
   
        $tableName = $this->scheme . '.' . $this->tableNameGrp;
        $sql = "SELECT * FROM " .$tableName. " " . $where . " ORDER BY npp, id";
        $result = $this -> getItems($sql);
        $result = $this -> itemsFormatted($result);
        return $result;
    }
    
    
    public function getListObjects() {
        $parentId = $this -> getParam(0);
        if(!$parentId) $this->error('NOT Parent_Id (' .$parentId.')');
        $tableName = $this->scheme . '.' . $this->tableNameList;
        $sql   = "SELECT * FROM " .$tableName;
        $where = " WHERE id_owner_guid = '$parentId' ORDER BY npp";
        switch ($this->tableNameList) {
            case 'props_list':
                $sql = "SELECT *, (SELECT * FROM proptype_name_get(dpl99.prop_type)) pt_name
                        FROM " .$tableName. " dpl99 ";
                break;
        }
      
        $result = $this -> getItems($sql . $where);
        return $result;
    }

    public function getItemId($tableName, $uid, $fieldName = 'id_guid') {
        
        $sql = "SELECT * FROM " . $tableName . " ";
        $where = " WHERE " . $fieldName . " = '" . $uid . "'";
        switch ($tableName) {
            case 'dir.props_list':
                $sql = "SELECT *, (SELECT * FROM proptype_name_get(dpl99.prop_type)) pt_name
                        FROM " .$tableName. " dpl99 ";
                break;
        }

        $result = $this -> getItem($sql . $where);
        return $result;
    }

    public function itemModelFormatted($tableName, $idGuid) {
        $result = array();
        $fieldName = 'id_guid';
        $tableScheme = $this->scheme . '.' . $tableName;
        $item  = $this->getItemId($tableScheme, $idGuid, $fieldName);
        $model = $this->loadModel($tableName, META_MODEL_FILE_NAME);

        foreach ($model as $field => $values) {
            (!$values['label']) ? $values['label'] = $field : $f = 0 ;
            $values['value'] = '';
            if(isset($item[$field])) {
                $values['value'] = $item[$field]; 
            }
            $result[$field]  = $values;
        }
        
        // lg($item);
        
        return $result;
    }
    
    public function getGrpItem($id = '') {
        $idGuid = $this->getParam(0);
        if($id) $idGuid = $id;
        return $this->itemModelFormatted($this->tableNameGrp, $idGuid);
    }
    
    public function getListItem($id = '') {
        $idGuid = $this->getParam(0);
        if($id) $idGuid = $id;
        return $this->itemModelFormatted($this->tableNameList, $idGuid);
    }
    
    public function getChildrens($tableName, $uid = 0, $fieldName = 'id_parent_guid') {

        $sql = "SELECT * FROM $tableName WHERE $fieldName = '$uid' ORDER BY npp";
        $result = $this -> getItems($sql);
        return $result;

    }

    
    //###############################
    // ---- Сохранение  в базу ---------
    
    public function addGrpObjects() {

        $errMessage = array();
        $actionType = 'add';
        $objType    = 'grp';
        $this -> errMessage = 'Не удалось сохранить данные в базе';
        
        $pgFuncName = $this->pgFuncName;
        $data = $this -> getPostData();
        
        // --- обязательные параметры
        $parentId = $this -> isField($data, PGIUD);
        $nameMax  = $this -> isField($data, 'name_max');
        
        if (!$nameMax)  $errMessage[] = 'Не заполнено обязательное поле: Полное имя';
        if (!$parentId) $errMessage[]= 'Не заполнено обязательное поле: Id родителя';
        if(!empty($errMessage)) $this->error($errMessage);
        
        $nameMin = $this -> isField($data, 'name_min');
        $note    = $this -> isField($data, 'note');
        $code    = $this -> isField($data, 'code');
        $mnemo   = $this -> isField($data, 'mnemocode');

        $query = "SELECT * FROM " . $pgFuncName . "('$parentId',  '$nameMax', '$nameMin', '$note', '$code', '$mnemo');";
        $result = $this -> saveItem($query, $actionType);
        return $this -> getPrepareResponse($result, $objType, $pgFuncName);
        
    }


    public function addListObjects() {

        $errMessage = array();
        $actionType = 'add';
        $objType    = 'list';
        $this -> errMessage = 'Не удалось сохранить данные в базе';

        $pgFuncName = $this->pgFuncNameList;
        $data       = $this -> getPostData();

        // --- обязательные параметры
        $parentId = $this -> isField($data, OWNER_ID);
        $propType = $this -> isField($data, 'prop_type');
        $nameMax  = $this -> isField($data, 'name_max');
        if(!$propType) $propType = "'pt_is_str()'";

        if (!$parentId) $errMessage[] = 'Не заполнено обязательное поле (' .OWNER_ID. '): Id владельца';
        if (!$nameMax)  $errMessage[] = 'Не заполнено обязательное поле: Полное наименование';
        if(!empty($errMessage)) $this->error($errMessage);  

 
        $idLinkGuid = $this -> isField($data, 'id_link_guid');
        $nameMin    = $this -> isField($data, 'name_min');
        $note       = $this -> isField($data, 'note');
        $code       = $this -> isField($data, 'code');
        $mnemo      = $this -> isField($data, 'mnemocode');

        /******
         par_idguid - id_guid группы типов объектов (обязательный параметр)
         new_namemax - полное наименование создаваемого типа  (обязательный параметр)
         new_namemin - сокращенное наименование создаваемого типа
         new_note - примечание к создаваемому типу
         new_code - пользовательский код создаваемого типа
         new_mnemo - мнемокод создаваемого типа
         *******/
         
         $query = "SELECT * FROM " . $pgFuncName . "('$parentId', '$nameMax', '$nameMin', '$note', '$code', '$mnemo');";
         
         switch ($pgFuncName) {
             case 'prop_list_add':
                   $query = "SELECT * FROM " . $pgFuncName . "('$parentId',  $propType, '$idLinkGuid', '$nameMax', '$nameMin', '$note', '$code', '$mnemo');";
                   break;
         }

         $result = $this -> saveItem($query, $actionType);
        
         return $this -> getPrepareResponse($result, $objType, $pgFuncName);
    }

    public function editGrpObjects() {

        //________________
        $objType    = 'grp';
        $tableName = $this->tableNameGrp; 
        //_________________ 

        $result = $data = $queryModel = array();
        $this -> errMessage = 'Не удалось сохранить данные в базе';
        $idGuid  = $id = $queryString = '';
        $actionType = 'edit';

        $data = $this -> getPostData();

        if(!empty($data[IDGIUD])) {
            $idGuid  = $data[IDGIUD];
            unset($data[IDGIUD]);
            if(isset($data['id'])) {
                $id = $data['id'];
                unset($data['id']);
            }
        }
        else {
            $this->error('Not id_guid');
        }

        $tableFields = $this->loadModel($tableName, META_MODEL_FILE_NAME);   
        foreach ($tableFields as $fieldName => $info) {
            if(isset($data[$fieldName])) {   
               $value = $data[$fieldName];
               $this->checkRequiredField($info, $value, $fieldName);

               $fieldType = $info['type']; 
               $queryLine = $this -> identifyValueType($fieldType, $fieldName, $value);     
               if($queryLine) $queryModel[] = $queryLine; 
               
            }
        }   
        
        $queryString = implode(',', $queryModel);

        $tableSchemeName = $this->scheme . '.' . $tableName; 
        $query = "UPDATE " .$tableSchemeName. " SET  " . $queryString . "
                  WHERE id_guid = '" . $idGuid . "' ";

        $result = $this -> saveItem($query, $actionType);
        return $this -> getPrepareResponse($result, $objType, '', $idGuid);
    }

    public function editListObject() {
        
        //___________________
        $objType    = 'list';
        $tableName = $this->tableNameList;
        //___________________

        $result = $data = $queryModel = array();
        $this -> errMessage = 'Не удалось сохранить данные в базе';
        $idGuid  = $id = $queryString = '';
        $actionType = 'edit';

        $data = $this -> getPostData();

        if(!empty($data[IDGIUD])) {
            $idGuid  = $data[IDGIUD];
            unset($data[IDGIUD]);
            if(isset($data['id'])) {
                $id = $data['id'];
                unset($data['id']);
            }
        }
        else {
            $this->error('Not id_guid');
        } 

        $tableFields = $this->loadModel($tableName, META_MODEL_FILE_NAME);   
        foreach ($tableFields as $fieldName => $info) {
            if(isset($data[$fieldName])) {    
               $value = $data[$fieldName];
               $this->checkRequiredField($info, $value, $fieldName);
                
               $fieldType = $info['type']; 
               $queryLine = $this -> identifyValueType($fieldType, $fieldName, $value);     
               if($queryLine) $queryModel[] = $queryLine; 
               
            }
        }   
        
        $queryString = implode(',', $queryModel);

        $tableSchemeName = $this->scheme . '.' . $tableName; 
        $query = "UPDATE " .$tableSchemeName. " SET  " . $queryString . "
                  WHERE id_guid = '" . $idGuid . "' ";

        $result = $this -> saveItem($query, $actionType);
        return $this -> getPrepareResponse($result, $objType, '', $idGuid);
        
    }

    protected function checkRequiredField($info, $value, $fieldName) {
        if(!isset($info['required'])) return true;
        if($info['required'] && !$value) {
           $label = $info['label'];
           $this->error('Не запонено обязательное поле  "' .$label .' ('. $fieldName. ')" ');
        }    
    }
 
}
