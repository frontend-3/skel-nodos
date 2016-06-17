<?php

namespace AdminAuth\Model;

use Zend\Db\Adapter\Adapter;

use Base\BaseTable;
use AdminAuth\Model\AdminUser;


class AdminUserTable extends BaseTable
{
    public function __construct(Adapter $dbAdapter)
    {
        parent::__construct($dbAdapter, new AdminUser());
    }

    public function generateUsername($firstname, $lastname) 
    {
        $name = strtolower($firstname[0]) . strtolower($lastname);
        $query = "select * from " . $this->tableName . " where username REGEXP '^{$name}([0-9]*)?$'";
        $slugCount = $this->tableGateway->getAdapter()->query($query, array())->count();
        return ($slugCount > 0) ? "{$name}{$slugCount}" : $name;
    }
}