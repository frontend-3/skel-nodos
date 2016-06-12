<?php

namespace Base;

use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Crypt\Password\Bcrypt;
use Zend\Mvc\Controller\AbstractActionController;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;

use Base\BaseController;

class AdminBaseController extends BaseController {
    
    protected $perm = '';
    protected $model = null;
    protected $inlines = null;
    protected $sizes = array();

    protected $add_template = 'admin/base/templates/add';
    protected $list_template = 'admin/base/templates/list';
    protected $table_service ='';

    public function is_allow($perm) {
        $user = $this->getLoggedUser();
        $hasperm = false;

        if(!is_object($user)) {
            $role_id = $user['role_id'];
            if($perm != '') {
                $table_role_permissions = $this->SL()->get('ServiceAuthRole');
                //$hasperm = $table_role_permissions->getPermByRole($role_id, $perm);
            }
        }
        if(!$hasperm){
            //return $this->redirect()->toRoute('admin');
        }
    }
    
    
    public function getPerms() {
        $user = $this->getLoggedUser();
        $perm_keys = array();
        if(!is_object($user)) {
            $role_id = $user['role_id'];
            $table_role_permissions = $this->SL()->get('ServiceAuthRolePermission');
            $perms = $table_role_permissions->getPerms($role_id);
            $perm_keys = array();
            foreach($perms as $p) {
                $perm_keys[] = array_keys(array_flip($p))[0];
            }
        }
        return $perm_keys;
    }
    
    
    public function getLoggedUser() {
        $user_session = new Container('admin');
        if($user_session->offsetExists('user_info')) {
            $obj = $user_session->offsetGet('user_info');
        }
        else {
            $obj = null;
        }

        if(is_null($obj)) {
            return false;
        }
        else {
            return $obj;
        }
    }
    
    
    public function renderDelete($redirect_route, $table_service) {
        $id = $this->params()->fromRoute('id');
        $table = $this->SL()->get($table_service);
        $table->delete($id);
        
        if($redirect_route == NULL) {
            $route = explode("/", $this->get_routed_match());
            $redirect_route = $route[0] . '/' . $route[1];
        }
        
        return $this->redirect()->toRoute($redirect_route);
    }
    
    
    public function get_routed_match() {
        $routeMatch = $this->SL()->get('Application')->getMvcEvent()->getRouteMatch();
        return $routeMatch->getMatchedRouteName();
    }
    
    
    public function renderList($per_page, $variables = array(), $options = array(), $condition = array()) {
        $user = $this->getLoggedUser();
        if(!$user){
            return $this->redirect()->toRoute('admin');
        }

        $table = $this->SL()->get($this->table_service);
        $paginator = $table->fetchAll(true, $condition);
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        $paginator->setItemCountPerPage($per_page);
        $variables['paginator'] = $paginator;
        $container = new Container('msg');
        $route = $this->get_routed_match();
        $title = explode("/",$route);
        $uri = $this->getRequest()->getUri()->getPath();
        $this->perm = sprintf("%s-view",str_replace('_','-',$title[1]));
        $msg = '';
        if($container->offsetExists('content')) {
            $msg = $container->offsetGet('content');
            $container->offsetUnset('content');
        }
        $variables['msg']   = $msg;
        $variables['route'] = $route;
        return $this->render($this->list_template,$variables,$options);
    }
    
    
    public function renderAdd($form, $inputFilter = null, $variables = array(), $options = array()) {
        $user = $this->getLoggedUser();
        if(!$user) {
            return $this->redirect()->toRoute('admin');
        }
        $user_id = $user['id'];
        $request = $this->getRequest();
        $id = intval($this->params("id"));
        $table = $this->SL()->get($this->table_service);        

        $route = explode("/", $this->get_routed_match());
        
        $title = explode("/", $route[1]);
        $this->perm = sprintf("%s-add", str_replace('_', '-', $title[0]));
        $route = $route[0] . '/' . $route[1];
        
        $field = null;
        $service = null;
        $foreign_key = null;
        $return_keys = null;
        $errors = array();
        
        if(!is_null($this->inlines)) {
            $field = array_keys($this->inlines)[0];
            $array = $this->inlines[$field];
            $service = $this->SL()->get($array['service']);
            $foreign_key = $array['foreign_key'];
            $return_keys = $array['return_keys'];
        }

        if($request->isPost()) {
            $form->setData($request->getPost());
            if($inputFilter!=null){
                if(property_exists($inputFilter,'exclude_id')) {
                    if($id>0) {
                        $inputFilter->exlude_id = $id;
                    }
                }
                $form->setInputFilter($inputFilter->getInputFilter());
            }
            if($form->isValid()) {
                $data_form = $form->getData();
                if(array_key_exists('password',$data_form)) {
                    if($data_form['password']!='') {
                        $bcrypt = new Bcrypt();
                        $data_form['password'] = $bcrypt->create($data_form['password']);
                    }
                    else {
                        unset($data_form['password']);
                    }
                }
                if($id == 0) {
                    // For generating usernames in module Auth
                    if(property_exists($this->model,'username')) {
                        $data_form['username'] = $table->generateUsername($data_form['first_name'],$data_form['last_name']);
                    }
                    if(property_exists($this->model,'slug_field')) {
                        if($this->model->slug_field!='') {
                            $data_form['slug'] = $table->get_slug($data_form[$this->model->slug_field]);
                        }
                    }
                    if($this->model->add_created) {
                        $data_form['created_by'] = $user_id;
                        $data_form['updated_by'] = $user_id;
                    }
                }
                else {
                    if($this->model->add_created) {
                        $data_form['updated_by'] = $user_id;
                    }
                }
                $data_form['id'] = $id;
                $this->model->exchangeArray($data_form);
                $id = $table->save($this->model);
                if(!is_null($service)) {
                    $service->delete(array($foreign_key => $id));
                    foreach($data_form[$field] as $d) {
                        $data = array();
                        $data[$return_keys] = $d;
                        $data[$foreign_key] = $id;
                        $service->save_array($data);
                    }
                }
                return $this->redirect()->toRoute($route);
            }
            else {
                $errors = $form->getMessages();
            }
        }
        else {
            if($id > 0) {
                $data = $table->first(array('id'=>$id));
                
                if(!$data) {
                    die('404');
                }
                
                if(!is_null($service)) {
                    $result = $service->all(array($return_keys), array($foreign_key => $id));
                    $data = array();
                    
                    foreach($result as $r) {
                        $keys = array_keys($r);
                        foreach($keys as $key) {
                            $data[] = $r[$key];
                        }
                    }
                    
                    $form_data[$field] = $data;
                    $form->setData($form_data);
                }
            }
        }
        
        $variables = array(
            'form'   => $form,
            'errors' => $errors,
            'route'  => $route,
        );
        return $this->render($this->add_template, $variables, $options);
    }
    
    
    public function renderView($form, $variables = array(), $options = array()) {
        $id = intval($this->params("id"));
        $user = $this->getLoggedUser();
        $user_id = null;
        if(!is_object($user)) {
            $user_id = $user['id'];
        }
        $route = explode("/", $this->get_routed_match());
        $route = $route[0] . '/' . $route[1];
        $table = $this->SL()->get($this->table_service);
        $files_table = $this->SL()->get('ServiceFileTable');
        $field = null;
        $service = null;
        $foreign_key = null;
        $return_keys = null;

        if(!is_null($this->inlines)) {
            $field = array_keys($this->inlines)[0];
            $array = $this->inlines[$field];
            $service = $this->SL()->get($array['service']);
            $foreign_key = $array['foreign_key'];
            $return_keys = $array['return_keys'];
        }
        if($id > 0) {
            $object = $table->first(array('id'=>$id));
            $object->formatFieldDates();
            $form->bind($object);
            if(array_key_exists('form_data', $variables)) {
                $form->setData($variables['form_data']);
            }
            if(!is_null($service)) {
                $result = $service->all(array($return_keys), array($foreign_key => $id));
                $data = array();
                foreach($result as $r) {
                    $keys = array_keys($r);
                    foreach($keys as $key) {
                        $data[] = $r[$key];
                    }
                }
                $form_data[$field] = $data;
                $form->setData($form_data);
            }
        }
        else {
            $object = null;
        }
        
        $variables = array(
            'object' => $object,
            'form'   => $form,
            'route'  => $route,
        );
        
        return $this->render($this->add_template, $variables, $options);
    }
    
    
    public function render($tpl_name = '', $variables = array(), $options = array()){
        $result = new ViewModel();
        if(array_key_exists('no_layout', $variables)) {
            $result->setTerminal(true);
        }
        else {
            if(!array_key_exists('is-login', $variables)) {
                $this->is_allow($this->perm);
                $variables['perms'] = $this->getPerms();
            }
        }
        
        if(!array_key_exists('route', $variables)) {
            $variables['route'] = $this->get_routed_match();
        }
        
        if(!is_null($variables)) {
            $variables['partials'] = $this->getPartials();
            $result->setVariables($variables);
        }
        
        if(!is_null($options)) {
            $result->setOptions($options);
        }
        
        $result->setTemplate($tpl_name);
        
        return $result;
    }
    
    
    public function getPartials() {
        $manager         = $this->SL()->get('ModuleManager');
        $modules         = $manager->getLoadedModules();
        $loadedModules   = array_keys($modules);
        $skipActionsList = array('notFoundAction', 'getMethodFromAction');
        $partials        = array();
        
        foreach($loadedModules as $loadedModule) {
            $folder = sprintf("%s/module/%s/view/admin/%s", ROOT_PATH, $loadedModule, strtolower($loadedModule));
            if(file_exists($folder)) {
                $route = sprintf("admin/%s/menu.phtml", strtolower($loadedModule));
                $partials[] = $route;
            }
        }
        return $partials;
    }


    protected function send_mail($email, $full_name, $subject, $body, $sender = 'no-reply') {
        $config = $this->SL()->get('Config');
        $config_email = $config['email'];
        $username = $config_email['username'];
        $password = $config_email['password'];
        $message = new Message();
        $message->addFrom($config_email['from']['address'], $sender)
                ->addTo($email, $full_name);
        $html_part = new MimePart($body);
        $html_part->type = 'text/html';
        $html_part->charset = 'utf-8';
        $body_part = new MimeMessage();
        $body_part->addPart($html_part);
        $message->setEncoding("UTF-8");
        $message->setSubject($subject);
        $message->setBody($body_part);

        if($config_email['transport'] == 'sendmail') {
            $transport = new SendmailTransport();
        }
        else {
            $transport = new SmtpTransport();
            $options   = new SmtpOptions(array(
                'host' => $config_email['host'],
                'connection_class'  => 'login',
                'connection_config' => array(
                    'ssl'      => $config_email['encryption'],
                    'username' => $username,
                    'password' => $password
                ),
                'port' => 587,
            ));
            $transport->setOptions($options);
        }
        $transport->send($message);
    }
}