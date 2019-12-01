<?php

//namespace Reestr;
//use PDO;

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
        if(!$stmt) $this->error($this ->pdo->errorInfo());
        return $results;
    }

    public function fetchAll($query) {
        $stmt = $this -> pdo -> query($query);
        if(!$stmt) $this->error($this ->pdo->errorInfo());
        $results = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function fetch($query) {
        $stmt = $this -> pdo -> query($query);
        if(!$stmt) $this->error($this ->pdo->errorInfo());
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function _exec($query) {
        
        try {
           $result = $this -> pdo -> exec($query);
        } catch (PDOException $e) {
            if ($e->getCode() == '2A000')
                $this->error($e->getMessage());
        }
        
        return $result;
    }

    public function getPdoLink() {
        return $this -> pdo;
    }

    public function getTableFields($tableName, $formatted = true) {
        $result = array();
        $sql = "SELECT column_name, column_default, data_type 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE table_name = '" .$tableName . "'; ";       
        $result = $this ->select($sql);
        
        if($formatted) {
            $result = $this->tableFieldsFormatted($result);
        }

        return $result;
    }

    public function tableFieldsFormatted($arr = array()) {
        $result = array();
        
        if(!empty($arr)) {
            foreach ($arr as $key => $value) {
                
                $name    = $value['column_name'];
                $type    = $value['data_type'];
                $default = $value['column_default'];
                
                switch ($type) {
                    case 'character varying':
                    case 'integer': $type = 'text';     break;
                    case 'boolean': $type = 'checkbox'; break;  
                    case 'text'   : $type = 'textarea'; break;                   
                }
                
                $item = array(
                   'date_type' => $value['data_type'],
                   'row'       => 1,
                   'type'      => $type,
                   'label'     => '',
                );
                
                $result[$name] = $item;
            }
        }

        return $result;  
    }
    
    public function error($params = '') {
        $debugTrace = '';
        if(DEBUG_TRACE) $debugTrace = debug_backtrace(); 
        lg($params, $debugTrace);
    }

}


