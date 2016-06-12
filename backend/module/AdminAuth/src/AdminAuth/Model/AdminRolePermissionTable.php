<?php

namespace AdminAuth\Model;

use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;

use Base\BaseTable;
use AdminAuth\Model\AdminRolePermission;


class AdminRolePermissionTable extends BaseTable{

    public function __construct(Adapter $dbAdapter){
        parent::__construct($dbAdapter,new AdminRolePermission());
    }

    public function getPerms($role_id)
    {
        $select = new Select();
        $select->from(array("role_perms"=>$this->tableName));
        $select->columns(array());
        $select->join(
            array('perm' => 'web_system_auth_permissions'), 'role_perms.permission_id=perm.id', array('name')
        );
        $select->where(array('role_perms.role_id' => $role_id));
        $statement = $this->sql->prepareStatementForSqlObject($select);
        return $statement->execute();
    }
}