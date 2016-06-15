<?php

namespace AdminAuth\Model;

use Zend\Db\Adapter\Adapter;

use Base\BaseTable;
use AdminAuth\Model\AdminRole;


class AdminRoleTable extends BaseTable{

    public function __construct(Adapter $dbAdapter){
        parent::__construct($dbAdapter, new AdminRole());
    }
    
    
    public function getPermByRole($rid, $perm) {
        $query = "
          SELECT *
            FROM web_system_auth_role_persmissions rp,
                 web_system_auth_permissions p
           WHERE p.id=rp.permission_id
             AND role_id='" . $rid . "'
             AND p.label='" . $perm . "'
        ";
            
        $r = $this->tableGateway->getAdapter()->query($query, array())->count();
        return ($r > 0);
    }
}