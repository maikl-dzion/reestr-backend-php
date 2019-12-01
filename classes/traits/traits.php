<?php


trait BaseObjectsHelper {
    
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


trait TestHelper {

    public function getTreeItems() {

        $where = ' WHERE name_max IS NOT NULL ';
        $limit = $this->getArg(0);
        if($limit) $limit = ' LIMIT ' . $limit;     
        $tableName = $this->scheme .'.'. $this->tableName;
        $sql = "SELECT * FROM ".$tableName." " . $where . " ORDER BY npp " . $limit;
        $result = $this -> getItems($sql);
        return $result;
        
    } 
    
    
    public function getTreeItem() {

        $idGuid  = $this->getArg(0);
        $idType  = $this->getArg(1);
        $sqlType = $this->getArg(2);
        $tableName = $this->scheme .'.'. $this->tableName;
         
         
        switch ($sqlType) {
            case 'getTrigger':
                $query = "SELECT
                               dirobj_type_name_get(true, wot.id_guid) obj_type, 
                               dirobj_name_get(true, wot.id_guid) obj_name,
                               id_guid
                          FROM " .$tableName. " wot 
                          WHERE (parents_id like '%[' || dirobj_id_get(true, '" .$idGuid. "') || ']%') ";
                break;
            
            default:
                $select = "(SELECT id FROM " .$tableName. "  WHERE id_guid='" .$idGuid. "' LIMIT 1) ";
                $query = "SELECT * FROM " .$tableName. " WHERE (parents_id like '%[' || " . $select ." || ']%');";
                
                break;
        } 

        $result = $this -> getItems($query);
        return $result;
        
    } 
  
  
    public function getObject() {

        $idGuid = $this->getArg(0);

        $sql = "
            SELECT 
                 obj.name_max     as obj_name
                 ,list.name_max   as list_name
                 ,grp.name_max    as grp_name
                 ,dir.name_max    as dir_name
                 ,parent.name_max as parent_name
                 ,props.*
              
            FROM ws.obj_tree as obj
            
            LEFT JOIN ws.obj_props as props  ON props.id_owner_guid = obj.id_guid
            LEFT JOIN ws.obj_tree  as parent ON parent.id_guid = obj.id_parent_guid
            LEFT JOIN dir.dir_tree as dir    ON dir.id_guid    = obj.id_link_guid
            LEFT JOIN dir.ot_list  as list   ON list.id_guid   = obj.id_type_guid
            LEFT JOIN dir.ot_grp   as grp    ON grp.id_guid    = list.id_owner_guid
            
            WHERE obj.id_guid = '" .$idGuid. "'
                    
        ";
 
        $result = $this -> getItems($sql);
        lg($result);
        return $result;
       
    }

    public function getObjectTest() {

        $idGuid = $this->getArg(0);
        // dirobj_type_name_get(false, wot.id_guid) obj_type, -- ХФ dirobj_type_name_get() возвращает наименование типа объекта
        // dirobj_name_get(false, wot.id_guid) obj_name,  -- ХФ dirobj_name_get() возвращает наименование объекта
        // ХФ ot_idguid_by_mnemo() возвращает id_guid типа объекта по его мнемокоду (или id_guid)
        // ХФ dirobj_id_get() находит и возвращает id объекта по его id_guid
        $sql = " 
            SELECT
            
              dirobj_type_name_get(false, wot.id_guid) obj_type,
              dirobj_name_get(false, wot.id_guid) obj_name,
              id_guid
            
            FROM
                    
              ws.obj_tree wot
            
            WHERE
            
              (id_type_guid = ot_idguid_by_mnemo('Исполнитель_полномочия'))
            
            AND
            
              (parents_id like '%[' || dirobj_id_get(false, '" .$idGuid. "') || ']%');
        
        ";
 
        $result = $this -> getItems($sql);
        lg($result);
        return $result;
       
    }
       
}



?>