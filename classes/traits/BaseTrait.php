<?php

trait BaseTrait {
    
    public function getObjectsTree() {

        $where = '';
        $limit = $this->getArg(0);
        if($limit) $limit = ' LIMIT ' . $limit;     
        $tableName = $this->getTableName($this->tableName);
        $sql = "SELECT * FROM ".$tableName." " . $where . " ORDER BY id " . $limit;
        $result = $this -> getItems($sql);
        $result = $this -> itemsFormatted($result);
        return $result;
        
    }
    
    public function getObjectProps() {

        $parentId  = $this->getArg(0);
        $tableName = $this->getTableName($this->propsTable);
        // $sql = "SELECT * FROM " .$tableName. " WHERE id_owner_guid = '$parentId' ORDER BY npp";
        
        $sql = "
           SELECT

                  npp,
                
                  prop_name_get(wop.id_prop_guid) prop_name,
                
                  dirobj_prop_val_vr_get(wop.id_guid, true) prop_val,
                
                  dirobj_type_name_get(true, wop.id_owner_guid) obj_type_name,
                
                  dirobj_code_get(true, wop.id_owner_guid) obj_code,
                
                  dirobj_name_cr_get(true, wop.id_owner_guid) obj_name,
                
                  id_guid
            
            FROM
            
                 dir.dir_props wop
            
            WHERE
            
                (id_owner_guid = '$parentId')
            
            OR
            
              (id_owner_guid in (select id_guid from dir.dir_tree where (parents_id like '%[' || dirobj_id_get(true, '$parentId') || ']%')))
            
            ORDER BY id_owner_guid, npp ";
              
        $result = $this -> getItems($sql);
        
        return $result;
        
    }
    
    public function getTableName($tableName = '') {
        if(!$tableName) $tableName = $this->table;
        return $this->scheme .'.'. $tableName;
    }
   
}

