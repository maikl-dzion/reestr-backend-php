<?php

class WsObjects extends BaseController {

    public $scheme     = 'ws';
    public $tableName  = 'obj_tree';
    public $propsTable = 'obj_props';
    
    use ObjectsTreeTrait;

}
