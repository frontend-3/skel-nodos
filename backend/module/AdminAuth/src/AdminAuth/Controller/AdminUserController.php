<?php

namespace AdminAuth\Controller;

use Zend\Session\Container;
use Zend\Console\Prompt\Line;
use Zend\Crypt\Password\Bcrypt;

use Base\AdminBaseController;

use AdminAuth\Model\AdminUser;
use AdminAuth\Form\AdminUserForm;
use AdminAuth\InputFilter\AdminUserInputFilter;


class AdminUserController extends AdminBaseController{

    public function indexAction()
    {
        $this->table_service ='ServiceAuthUser';
        $variables = array();
        $variables['display_fields'] = array('id','username','status');
        return parent::renderList(50, $variables);
    }

    public function viewAction()
    {
        $this->model = new AdminUser();
        $this->table_service = 'ServiceAuthUser';
        $table_service =$this->SL()->get('ServiceAuthRole');
        $roles = $table_service->all();
        $roles_array = array();
        foreach($roles as $r){
            $roles_array[$r->id] = $r->name;
        }
        return parent::renderView(new AdminUserForm($roles_array));
    }

    public function updateAction()
    {
        $this->model = new AdminUser();
        $this->table_service = 'ServiceAuthUser';
        $table_service =$this->SL()->get('ServiceAuthRole');
        $roles = $table_service->all();
        $roles_array = array();
        foreach($roles as $r){
            $roles_array[$r->id] = $r->name;
        }
        return parent::renderAdd(
            new AdminUserForm($roles_array),
            new AdminUserInputFilter($this->getDBAdapter()));
    }

    public function deleteAction(){
        return $this->redirect()->toRoute('admin');
    }
}