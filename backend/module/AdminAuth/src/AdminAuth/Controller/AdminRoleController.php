<?php

namespace AdminAuth\Controller;

use Base\AdminBaseController;

use AdminAuth\Model\AdminRole;
use AdminAuth\Form\AdminRoleForm;


class AdminRoleController extends AdminBaseController{

    public function indexAction()
    {
        $this->table_service ='ServiceAuthRole';
        $variables = array();
        $variables['display_fields'] = array('id','name','status');
        return parent::renderList(50,$variables);
    }

    public function viewAction(){
        $table_perms = $this->SL()->get('ServiceAuthPermission');
        $perms = $table_perms->all(array('id','name'));
        $perms_array = array();
        foreach($perms as $perm){
            $perms_array[$perm['id']] = $perm['name'];
        }
        $this->table_service ='ServiceAuthRole';
        $this->model = new AdminRole();
        $this->inlines['permissions'] = array(
            'service' => 'ServiceAuthRolePermission',
            'foreign_key' => 'role_id',
            'return_keys'=>'permission_id');
        return parent::renderView(new AdminRoleForm($perms_array));
    }

    public function updateAction()
    {
        $table_perms = $this->SL()->get('ServiceAuthPermission');
        $perms = $table_perms->all(array('id','name'));
        $perms_array = array();
        foreach($perms as $perm){
            $perms_array[$perm['id']] = $perm['name'];
        }
        $this->table_service ='ServiceAuthRole';
        $this->model = new AdminRole();
        $this->inlines['permissions'] = array(
            'service' => 'ServiceAuthRolePermission',
            'foreign_key' => 'role_id',
            'return_keys'=>'permission_id');
        return parent::renderAdd(new AdminRoleForm($perms_array));
    }
    
    public function deleteAction()
    {
        return $this->redirect()->toRoute('admin');
    }
}