<?php

namespace User\Controller;

use User\Form\WebUserResetForm;
use User\Form\WebUserForgotForm;
use User\Form\WebUserForm;
use User\Form\WebUserProfileForm;
use User\Form\WebUserLoginForm;
use User\InputFilter\WebUserForgotInputFilter;
use User\InputFilter\WebUserLoginInputFilter;
use User\InputFilter\WebUserRegisterInputFilter;
use User\InputFilter\WebUserResetInputFilter;
use User\Model\WebUser;
use Zend\Session\Container;
use Zend\Crypt\Password\Bcrypt;
use Base\BaseController;

function make_date($y, $m, $d) {
    return date('Y-m-d', strtotime(sprintf('%s/%s/%s', $y, $m, $d)));
}

function make_date_from_string($date){
    $date = strtotime(str_replace("/","-",$date));
    return date('Y-m-d', $date);
}

function get_age_from_datetime($date){
    $from = new \DateTime($date);
    $to = new \DateTime('today');
    return $from->diff($to)->y;
}

function get_age($y, $m, $d) {
    $from = new \DateTime($y . '-' . $m . '-' . $d);
    $to = new \DateTime('today');
    return $from->diff($to)->y;
}

define('TRY_ATTEMPS', 3);

class UserController extends BaseController {

    public function loginAction(){
        $container = new Container('web_site');
        if($container->offsetGet('user_id')){
            return $this->redirect()->toRoute('profile');
        }
        $path = sprintf('%s/public/%s/', ROOT_PATH, 'captcha');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $renderer = $this->SL()->get(
            'Zend\View\Renderer\RendererInterface');
        $url = $renderer->basePath('captcha');

        $request = $this->getRequest();
        $show_captcha = $this->manage_ses_show_captcha();
        $form = new WebUserLoginForm($show_captcha,$url);
        if($request->isPost()){
            $data = $request->getPost();
            $form->setData($data);
            $inputFilter = new WebUserLoginInputFilter($this->getDBAdapter());
            $form->setInputFilter($inputFilter->getInputFilter());
            if($form->isValid()){
                $data_form = $form->getData();
                $table_user = $this->SL()->get('ServiceUserTable');
                $result = $table_user->first(array('email'=>$data_form['login_email']),array('password','id'));
                $bcrypt = new Bcrypt();
                if($bcrypt->verify($data_form['login_password'],$result['password'])){
                    $this->set_value_session('user_id',$result['id']);
                    return $this->redirect()->toRoute('profile');
                }else{
                    $form->get('login_password')->setMessages(array('Usuario y/o password incorrectos'));
                    $this->SL()->get('Zend\Log')->err($form->getMessages());
                }
            }else{
                $form->get('login_password')->setMessages(array('Usuario y/o password incorrectos'));
                $this->SL()->get('Zend\Log')->err($form->getMessages());
            }
        }
        $show_captcha = $this->manage_ses_tries_captcha();
        if($show_captcha){
            $form->addCaptcha($url);
        }
        $variables = array('form'=>$form,"show_captcha"=>$show_captcha);
        return $this->render('site/login',$variables);
    }

    public function logoutAction() {
        $this->destroy();
        return $this->redirect()->toRoute('home');
    }

    private function manage_ses_show_captcha() {
        $key = 'login_attempts';
        $session = new Container('webuser_session');
        $show = false;
        if ($session->offsetExists($key)) {
            if ((int) $session->offsetGet($key) > TRY_ATTEMPS) {
                $show = true;
            }
        }
        return $show;
    }

    private function manage_ses_tries_captcha() {
        $key = 'login_attempts';
        $session = new Container('webuser_session');
        if ($session->offsetExists($key)) {
            $attempts = (int) $session->offsetGet($key) + 1;
        } else {
            $attempts = 1;
        }
        $session->offsetSet($key, $attempts);
        return $attempts > TRY_ATTEMPS ? true : false;
    }

    public function addAction() {
        $this->set_value_session('route',$this->get_routed_match());
        $user_table = $this->SL()->get('ServiceUserTable');

        $form = new WebUserForm($this->getDptos());
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $this->getRequest()->getPost();
            if ($data['cod_dpto'] != '') {
                $form->get('cod_prov')->setvalueOptions(
                        $this->getProvinces($data['cod_dpto']));
                if ($data['cod_prov'] != '') {
                    $form->get('cod_dist')->setvalueOptions(
                            $this->getDistricts(
                                    $data['cod_dpto'], $data['cod_prov']));
                }
            }
            $bcrypt = new Bcrypt();
            $dbadapter = $this->getDBAdapter();
            $inputFilter = new WebUserRegisterInputFilter($dbadapter);
            $form->setInputFilter($inputFilter->getInputFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $data_form = $form->getData();
                $data_form['password'] = $bcrypt->create($data_form['password']);
                $data_form['birthday'] = make_date_from_string($data_form['birthday']);
                $data_form['created_at'] = date('Y-m-d H:i:s');
                $data_form['updated_at'] = date('Y-m-d H:i:s');
                $model = new WebUser();
                $model->exchangeArray($data_form);
                try{
                    $user_id = $user_table->save($model);
                }catch (\Exception $e){
                    var_dump($e->getMessage());
                    $this->SL()->get('Zend\Log')->err(
                        array(__FILE__ . ':' . __LINE__,
                            $form->getMessages()));
                }
                $model = new WebUser();
                $model->token_password = md5(date('Y-m-d H:i:s').$user_id);
                $model->token_activate = md5(date('Y-m-d H:i:s').$user_id);
                $model->id = $user_id;
                try{
                    $user_table->save($model);
                }catch (\Exception $e){
                    $this->SL()->get('Zend\Log')->err(
                        array(__FILE__ . ':' . __LINE__,
                            $form->getMessages()));
                }

                $register = new Container('register');
                $register->offsetSet('id', $user_id);
                die('registro exitoso');
                return $this->redirect()->toRoute('register_thanks');
            }else{
                $data['password'] = $data['confirm_password']= '';
                $this->SL()->get('Zend\Log')->err(
                    array(__FILE__ . ':' . __LINE__,
                        $form->getMessages()));
                $form->setData($data);
            }
        }
        $data_view = array();
        $data_view['form'] = $form;
        return $this->render('site/user/register', $data_view, null);
    }

    public function thanksAction() {
        $this->set_value_session('route',$this->get_routed_match());
        return $this->render('site/register/thanks');
    }

    public function forgotPasswordAction() {
        $renderer = $this->SL()->get(
            'Zend\View\Renderer\RendererInterface');
        $url = $renderer->basePath('captcha');
        $form = new WebUserForgotForm(true,$url);
        $request = $this->getRequest();
        if($request->isPost()){
            $inputFilter = new WebUserForgotInputFilter($this->getDBAdapter());
            $form->setInputFilter($inputFilter->getInputFilter());
            $form->setData($request->getPost());
            if($form->isValid()){
                $form_data = $form->getData();
                $email = $form_data['email'];
                $user_table = $this->SL()->get('ServiceUserTable');
                try{
                    $user = $user_table->first(array('email'=>$email),array('token_password','first_name','last_name'));
                }catch (\Exception $e){
                    $this->SL()->get('Zend\Log')->err(
                        array(__FILE__ . ':' . __LINE__,
                            $e->getMessage()));
                }
                if($user){
                    $url_reset = $this->url()->fromRoute(
                        'reset_password_user', array('token' => $user['token_password']), array('force_canonical' => true));
                    echo $url_reset;
                    die();
                }
                return $this->redirect()->toRoute('forgot_password_thanks');
            }else{
                $errors = $form->getMessages();
                if(count($errors)==1){
                    foreach ($errors as $error) {
                        if(array_key_exists('noRecordFound',$error)){
                            return $this->redirect()->toRoute('forgotUserThanks');
                            break;
                        }
                    }
                }
            }
        }
        $variables = array();
        $variables['form'] = $form;
        return $this->render('site/user/forgot',$variables);
    }

    public function forgotThanksAction() {
        return $this->render('site/user/forgot-thanks');
    }

    public function resetPasswordAction() {
        $user_table = $this->SL()->get('ServiceUserTable');
        $token = $this->getEvent()->getRouteMatch()->getParam('token');
        $user = $user_table->first(array('token_password'=>$token),array('email','id'));
        $renderer = $this->SL()->get(
            'Zend\View\Renderer\RendererInterface');
        if($user){
            $url = $renderer->basePath('captcha');
            $form  = new WebUserResetForm($url);
            $variables = array();
            $variables['form'] = $form;
            $request = $this->getRequest();
            if($request->isPost()){
                $inputFilter = new WebUserResetInputFilter();
                $form->setData($request->getPost());
                $form->setInputFilter($inputFilter->getInputFilter());
                if($form->isValid()){
                    $data_form = $form->getData();
                    $model = new WebUser();
                    $bcrypt = new Bcrypt();
                    $model->password = $bcrypt->create($data_form['password']);
                    $model->token_password = md5(date('Y-m-d H:i:s').$user['id']);
                    $model->id = $user['id'];
                    $user_table->save($model);
                    return $this->redirect()->toRoute('home');
                }
            }
            return $this->render('site/user/reset',$variables);
        }
        return $this->redirect()->toRoute('home');
    }

    public function activateAction() {
        $user_table = $this->SL()->get('ServiceUserTable');
        $token = $this->getEvent()->getRouteMatch()->getParam('token');
        $user = $user_table->first(array('token_activate'=>$token),array('email','id'));
        if($user){
            return $this->redirect()->toRoute('home');
        }
    }

    public function getDptos($value='') {
        $table_departments = $this->SL()->get('ServiceDepartmentsTable');
        $departments = $table_departments->all(array('id','name'));
        $departments_array = array(''=>'Seleccione');
        foreach ($departments as $key => $value) {
            $departments_array[$value['id']] = $value['name'];
        }
        return $departments_array;
    }

    public function getProvinces($cod_dpto=null) {
        $table_provinces = $this->SL()->get('ServiceProvincesTable');
        if(!is_null($cod_dpto)){
            $provinces = $table_provinces->all(array('id','name'),array('cod_dpto'=>$cod_dpto));
        }else{
            $provinces = $table_provinces->all(array('id','name'));    
        }
        $provinces_array = array(''=>'Seleccione');
        foreach ($provinces as $key => $value) {
            $provinces_array[$value['id']] = $value['name'];
        }
        return $provinces_array;
    }

    public function getDistricts($cod_dpto=null,$cod_prov = null) {
        $table_districts = $this->SL()->get('ServiceDistrictsTable');
        if(!is_null($cod_dpto) && !is_null($cod_prov)){
            $districts = $table_districts->all(array('id','name'),array('cod_dpto'=>$cod_dpto));
        }else{
            $districts = $table_districts->all(array('id','name'));    
        }
        $districts_array = array(''=>'Seleccione');
        foreach ($districts as $key => $value) {
            $districts_array[$value['id']] = $value['name'];
        }
        return $districts_array;
    }

    public function profileAction() {
        if($this->authenticated==0) {
            return $this->redirect()->toRoute('user_login');
        }
        $table_user = $this->SL()->get('ServiceUserTable');
        $container = new Container('web_site');
        $user = $table_user->first(array('id'=>$container->offsetGet('user_id')));
        $user->birthday = date("d/m/Y",strtotime($user->birthday));
        $form = new WebUserForm();
        $form->get('cod_dpto')->setvalueOptions($this->getDptos());

        $form->bind($user);
        $form->get('cod_prov')->setvalueOptions($this->getProvinces($user->cod_dpto));
        $request = $this->getRequest();
        if($request->isPost()){

        }
        $variables = array();
        $variables['form'] = $form;
        return $this->render('site/user/profile',$variables);
    }

}
