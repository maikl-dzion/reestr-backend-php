<?php

class DirObjects extends BaseController {

    public $scheme     = 'dir';
    public $tableName  = 'dir_tree';
    public $propsTable = 'dir_props';

    use ObjectsTreeTrait;
    
}