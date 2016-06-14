<?php

namespace AdminAuth\Controller;

use Base\AdminBaseController;

use AdminAuth\Model\AdminPermission;
use AdminAuth\Form\AdminPermissionForm;


class AdminPermissionController extends AdminBaseController{

    public function indexAction() {
        $this->table_service ='ServiceAuthPermission';
        $variables = array();
        $variables['display_fields'] = array('id','name','status');
        return parent::renderList(50, $variables);
    }
    
    
    public function viewAction() {
        $this->model = new AdminPermission();
        $this->table_service = 'ServiceAuthPermission';
        
        return parent::renderView(new AdminPermissionForm());
    }
    
    
    public function updateAction() {
        $this->model = new AdminPermission();
        $this->table_service = 'ServiceAuthPermission';
        return parent::renderAdd(new AdminPermissionForm());
    }
    
    
    public function deleteAction() {
        return $this->redirect()->toRoute('admin');
    }
}