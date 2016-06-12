<?php

namespace AdminAuth\Model;

use Zend\Db\Adapter\Adapter;

use Base\BaseTable;
use AdminAuth\Model\AdminPermission;


class AdminPermissionTable extends BaseTable{

    public function __construct(Adapter $dbAdapter){
        parent::__construct($dbAdapter,new AdminPermission());
    }
}