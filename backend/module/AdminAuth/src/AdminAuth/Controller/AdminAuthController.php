<?php

namespace AdminAuth\Controller;

use Zend\Session\Container;
use Zend\Crypt\Password\Bcrypt;

use Base\AdminBaseController;

use AdminAuth\Form\AdminLoginForm;
use AdminAuth\InputFilter\AdminLoginInputFilter;

class AdminAuthController extends AdminBaseController {
    public function welcomeAction() {
        $user = $this->getLoggedUser();
        $vars=array();

        if(!is_object($user)) {
            $vars['user']=$user;
        }

        return $this->render('admin/auth/welcome',$vars);
    }
    
    
    public function loginAction() {
        $admin_session = new Container('admin');
        if($admin_session->offsetExists('user_info')) {
            return $this->redirect()->toRoute('admin/system_users');
        }

        $vars = array();
        $form = new AdminLoginForm();
        $request = $this->getRequest();

        if($request->isPost()) {
            $form->setData($request->getPost());
            $inputFilter = new AdminLoginInputFilter();
            $form->setInputFilter($inputFilter->getInputFilter());
            
            if($form->isValid()) {
                $form_data = $form->getData();

                $table = $this->SL()->get('ServiceAuthUser');
                $user = $table->first(array(
                    'username' => $form_data['username']), array('id', 'password', 'is_superuser', 'role_id','first_name','last_name'));
                if($user) {
                    $bcrypt = new Bcrypt();
                    if($bcrypt->verify($form_data['password'], $user['password'])) {
                        $data = array(
                            'id' => $user['id'],
                            'role_id' => $user['role_id'],
                            'super_user' => $user['is_superuser'],
                            'first_name' => $user['first_name'],
                            'last_name' => $user['last_name'],
                        );
                        $admin_session->offsetSet('user_info', $data);
                        return $this->redirect()->toRoute('admin/welcome');
                    }
                }
            }
        }
        
        $vars = array(
            'form'     => $form,
            'loginMsg' => '',
            'is-login' => true,
        );
        
        return $this->render('admin/auth/login', $vars);
    }
    
    
    public function logOutAction() {
        $admin_session = new Container('admin');
        $admin_session->offsetUnset('user_info');
        return $this->redirect()->toRoute('admin');
    }
    
}