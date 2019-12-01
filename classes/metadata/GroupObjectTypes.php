<?php

// namespace Reestr;

//---- ГРУППА ТИПОВ ОБЪЕКТОВ

class GroupObjectTypes extends BaseController {

    public $scheme     = 'dir';
    public $tableName  = '';
    public $tableNameGrp   = 'ot_grp';
    public $tableNameList  = 'ot_list';
    
    public $pgFuncName     = 'ot_grp_add';
    public $pgFuncNameList = 'ot_list_add';

    use MetaDataTrait;


}

