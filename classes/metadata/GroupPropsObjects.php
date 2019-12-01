<?php

//---- ГРУППА СВОЙСТВ ОБЪЕКТОВ

class GroupPropsObjects extends BaseController {

    public $scheme = 'dir';
    public $tableName = '';
    public $tableNameGrp  = 'props_grp';
    public $tableNameList = 'props_list';
    
    public $pgFuncName     = 'prop_grp_add';
    public $pgFuncNameList = 'prop_list_add';

    use MetaDataTrait;

}


