<?php

namespace AdminAuth\Controller;

use Zend\Session\Container;
use Zend\Crypt\Password\Bcrypt;

use Base\AdminBaseController;

use AdminAuth\Form\AdminLoginForm;
use AdminAuth\InputFilter\AdminLoginInputFilter;

class AdminAuthController extends AdminBaseController {
    public function welcomeAction() {
        $admin_session = new Container('admin');
        if($admin_session->offsetExists('user_info') == false) {
            return $this->redirect()->toRoute('admin');
        }
        
        return $this->render('admin/auth/welcome');
    }
    
    
    public function loginAction() {
        $admin_session = new Container('admin');
        if($admin_session->offsetExists('user_info')) {
            return $this->redirect()->toRoute('admin/welcome');
        }

        $vars = array();
        $form = new AdminLoginForm();
        $request = $this->getRequest();
        $login_msg = '';

        if($request->isPost()) {
            $form->setData($request->getPost());
            $inputFilter = new AdminLoginInputFilter();
            $form->setInputFilter($inputFilter->getInputFilter());
            
            if($form->isValid()) {
                $form_data = $form->getData();

                $table = $this->SL()->get('ServiceAuthUser');
                $user = $table->first(array(
                    'username' => $form_data['username']), array('id', 'password', 'is_superuser', 'role_id'));
                if($user) {
                    $bcrypt = new Bcrypt();
                    if($bcrypt->verify($form_data['password'], $user['password'])) {
                        $data = array(
                            'id' => $user['id'],
                            'role_id' => $user['role_id'],
                            'super_user' => $user['is_superuser']
                        );
                        $admin_session->offsetSet('user_info', $data);
                        return $this->redirect()->toRoute('admin/welcome');
                    }
                    else {
                        $login_msg = 'Wrong username or password';
                    }
                }
                else {
                    $login_msg = 'Wrong username or password';
                }
            }
        }
        
        $vars = array(
            'form'     => $form,
            'loginMsg' => $login_msg,
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