<?php

define('DEBUG_TRACE', true);  //---запуск функции  debug_backtrace()
define('DEBUG_TRACE_TOOGLE', true);  //--- свернуть результаты  debug_backtrace()

define('IDGIUD'   , 'id_guid');
define('PGIUD'    , 'id_parent_guid');
define('OWNER_ID' , 'id_owner_guid');
define('SUB'      , 'children');
define('DEL'      , 'delete');

define('META_MODEL_FILE_NAME', 'MetaDataModel.php');

define('ROOT_DIR' , __DIR__);
define('INC_DIR'    , ROOT_DIR . '/inc');
define('CONF_DIR'   , ROOT_DIR . '/config');
define('CLASSES_DIR' , ROOT_DIR . '/classes');
define('MODELS_DIR'  , ROOT_DIR . '/models');

require CONF_DIR . '/config.php'; //--получаем конфиги
require CONF_DIR . '/routes.php'; //--получаем роуты

// require INC_DIR . '/functions.php';
/****************
require CLASSES_DIR . '/router.class.php';
require CLASSES_DIR . '/traits.php';
require CLASSES_DIR . '/base.class.php';
require CLASSES_DIR . '/metaData.class.php';
require CLASSES_DIR . '/dirObjects.class.php';
require CLASSES_DIR . '/wsObjects.class.php';

****************/

includedFilesRun(INC_DIR);  //--- подключаем файлы

function includedFilesRun($dirName) { 
     $arr = array();
     $iterator = new DirectoryIterator($dirName);
     foreach ($iterator as $fileinfo) {
        if ($fileinfo->isFile()) {
            $ext      = $fileinfo->getExtension();    
            $fileName = $fileinfo->getFilename();
            $filePath = $fileinfo->getPathname();
            if($ext == 'php') {
                require_once $filePath; 
            }
        }
    } 
}


// lg('test');
 