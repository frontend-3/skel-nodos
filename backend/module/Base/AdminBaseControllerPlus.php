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
use Florence\Florence;

class AdminBaseControllerPlus extends BaseController {
    protected $route = NULL;
    protected $perm  = NULL;
    protected $title = NULL;
    public $has_ubigeo = false;
    public $has_created_updated_by = false;
    public $has_file_uploads = false;
    
    public function is_allowed($perm) {
        $user = $this->getLoggedUser();
        $hasperm = false;
        
        if(!is_object($user)) {
            $role_id = $user['role_id'];
            if($perm != '') {
                $table_role_permissions = $this->SL()->get('ServiceAuthRole');
                $hasperm = $table_role_permissions->getPermByRole($role_id, $perm);
            }
        }
        
        if(!$hasperm){
            return false;
        }
        else {
            return true;
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
    
    
    public function noLoggedUser() {
        $user = $this->getLoggedUser();
        return $user === false;
    }
    
    
    public function get_routed_match() {
        $routeMatch = $this->SL()->get('Application')->getMvcEvent()->getRouteMatch();
        return $routeMatch->getMatchedRouteName();
    }
    
    
    public function coreSetup() {
        $route = explode("/", $this->get_routed_match());
        
        if($this->route === NULL) {
            $this->route = $route[0]  . '/' . $route[1];
        }
        
        if($this->route === NULL) {
            $this->route = $route[0]  . '/' . $route[1];
        }
        
        if($this->perm === NULL) {
            $this->perm = $route[1];
        }
        
        if($this->title === NULL) {
            $this->title = ucfirst($route[1]);
        }
        
        if($this->params()->fromQuery('page') !== NULL) {
            $this->page = intval($this->params()->fromQuery('page'));
            
            if($this->page <= 0) {
                $this->page = 1;
            }
        }
        else {
            $this->page = 1;
        }
    }
    
    
    public function listConfig() {
    // Configure and define variables that will be used thru the process
        $defaults = array(
            'template'         => 'admin/base/templates/listplus',
            'perm'             => NULL,
            'view_vars'        => array(),
            'view_options'     => array(),
            'title'            => NULL,
            'condition'        => NULL,
            'order'            => 'id DESC',
            'records_per_page' => 50,
            'display_fields'   => NULL,
        );
        
        foreach($defaults as $k => $v) {
            if(!property_exists($this, $k) || $this->$k === NULL) {
                $this->$k = $v;
            }
        }
    }
    
    
    public function listSetup() {
    // Using the variables and definitions made in Config(), here we
    // autoconfigure everything else we need. Magic happens here.
        $this->coreSetup();
        
        $container = new Container('msg');
        
        if($container->offsetExists('content')) {
            $this->flash = $container->offsetGet('content');
            $container->offsetUnset('content');
        }
        else {
           $this->flash = NULL;
        }
    }
    
    
    public function listGetModel() {
        $table = $this->model($this->model_name);
        
        $records = $table->fetchAll(true, $this->condition, $this->order);
        $records->setCurrentPageNumber($this->page);
        $records->setItemCountPerPage($this->records_per_page);
        
        $this->records = $records;
    }
    
    
    public function listGetForm() {
        $this->flr = new Florence($this, $this->formdef);
        
        foreach($this->flr->elements as $name => $e) {
            if(isset($e['admin']) && isset($e['admin']['ignore'])) {
                unset($this->flr->elements[$name]);
            }
        }
    }
    
    
    public function listSetDisplayFields() {
        if($this->display_fields === NULL) {
            $keys = array_unique(array_keys(get_object_vars($this->records->getCurrentItems()->getArrayObjectPrototype())));
            sort($keys);
            
            $exclude = array(
                'dbAdapter',
                'sql',
                'tableGateway',
                'table_name',
                'add_created',
                'add_updated',
                'created_at',
                'created_by',
                'updated_at',
                'updated_by',
            );
            
            foreach($exclude as $e) {
                if($k = array_search($e, $keys)) {
                    unset($keys[$k]);
                }
            }
            
            $this->display_fields = $k;
        }
    }
    
    
    public function listRender() {
        $vars                   = $this->view_vars;
        $vars['flr']            = $this->flr;
        $vars['records']        = $this->records;
        $vars['route']          = $this->route;
        $vars['title']          = $this->title;
        $vars['display_fields'] = $this->display_fields;
        $vars['flash']          = $this->flash;
        $vars['perm']           = $this->perm;
        $vars['perms']          = $this->getPerms();
        $vars['page']           = $this->page;
        
        return $this->render($this->template, $vars, $this->view_options);
    }
    
    
    public function listAction() {
        $this->listConfig();
        $this->listSetup();
        
        if($this->is_allowed($this->perm . '-list') === false) {
            return $this->redirect_to('admin');
        }
        
        $this->listGetModel();
        $this->listGetForm();
        $this->listSetDisplayFields();
        return $this->listRender();
    }
    
    
    public function viewConfig() {
    // Configure and define variables that will be used thru the process
        $defaults = array(
            'template'         => 'admin/base/templates/view',
            'perm'             => NULL,
            'id'               => intval($this->params()->fromRoute('id')),
            'view_vars'        => array(),
            'view_options'     => array(),
            'title'            => NULL,
            'files'            => array(),
            'has_file_uploads' => false,
            'use_files_module' => false,
        );
        
        foreach($defaults as $k => $v) {
            if(!property_exists($this, $k) || $this->$k === NULL) {
                $this->$k = $v;
            }
        }
    }
    
    
    public function viewSetup() {
    // Using the variables and definitions made in Config(), here we
    // autoconfigure everything else we need. Magic happens here.
        $this->coreSetup();
        $this->perm = $this->perm . '-view';
    }
    
    
    public function viewGetForm() {
        $this->flr  = new Florence($this, $this->formdef);
        
        foreach($this->flr->elements as $name => $e) {
            if(isset($e['admin']) && isset($e['admin']['ignore'])) {
                unset($this->flr->elements[$name]);
            }
        }
        
        $this->form = $this->flr->generate();
    }
    
    
    public function viewGetModel() {
        $this->record = $this->model($this->model_name)->first(array('id' => $this->id));
        $this->form->setData($this->record->getArrayCopy());
        // TODO: Handle 404
    }
    
    
    public function viewGetFiles() {
        foreach($this->flr->elements as $name => $e) {
            if($e['type'] == 'File') {
                $this->files[$name] = $this->model('Files\Model\File')->get_file_for($this->record->getTableName(), $this->id, $name);
            }
        }
    }
    
    
    public function viewRender() {
        $vars           = $this->view_vars;
        $vars['id']     = $this->id;
        $vars['record'] = $this->record;
        $vars['form']   = $this->form;
        $vars['flr']    = $this->flr;
        $vars['route']  = $this->route;
        $vars['title']  = $this->title;
        $vars['files']  = $this->files;
        $vars['page']   = $this->page;
        
        return $this->render($this->template, $vars, $this->view_options);
    }
    
    
    public function viewAction() {
        if($this->noLoggedUser()) {
            return $this->redirect_to('admin');
        }
        
        $this->viewConfig();
        $this->viewSetup();
        
        if($this->is_allowed($this->perm) === false) {
            return $this->redirect_to('admin');
        }
        
        $this->viewGetForm();
        $this->viewGetModel();
        
        if($this->use_files_module) {
            $this->viewGetFiles();
        }
        
        return $this->viewRender();
    }
    
    
    public function addupdateConfig() {
    // Configure and define variables that will be used thru the process
        $defaults = array(
            'template'               => 'admin/base/templates/update',
            'id'                     => intval($this->params()->fromRoute('id')),
            'view_vars'              => array(),
            'view_options'           => array(),
            'errors'                 => NULL,
            'has_created_updated_by' => true,
            // Flag setup automagically when we have a File type element
            'has_file_uploads'       => false,
            // Array for every element that is defined as a file in Formdef
            'files'                  => array(),
            'file_uploads_path'      => 'public/media/images/',
            // Use the Files modules to handle File Uploads.
            'use_files_module'       => true,
            'has_ubigeo'             => false,
            // Callbacks
            'cb_init'                => function() {},
            'cb_is_post'             => function() {},
            'cb_is_valid'            => function() {},
            'cb_after_save'          => function() {},
            'cb_not_valid'           => function() {},
            'cb_is_get'              => function() {},
            'cb_is_get_update'       => function() {},
            'cb_is_get_add'          => function() {},
            'cb_end'                 => function() {},
        );
        
        foreach($defaults as $k => $v) {
            if(!property_exists($this, $k) || $this->$k === NULL) {
                $this->$k = $v;
            }
        }
    }
    
    
    public function addupdateGetForm() {
        $this->flr  = new Florence($this, $this->formdef);
        
        foreach($this->flr->elements as $name => $e) {
            if(isset($e['admin']) && isset($e['admin']['ignore'])) {
                unset($this->flr->elements[$name]);
            }
            else {
                if($e['type'] == 'File') {
                    $this->has_file_uploads = true;
                    $this->files[$name]     = array();
                }
            }
        }
        
        $this->form = $this->flr->generate();
    }
    
    
    public function addupdateGetModel() {
        $this->record = $this->model($this->model_name);
        
        if($this->id > 0) {
            $this->record = $this->record->first(array('id' => $this->id));
            // TODO: Handle 404
            
            if($this->has_file_uploads) {
                $this->getFileUploads();
            }
        }
    }
    
    
    public function addupdateProcessPost() {
        call_user_func($this->cb_init);
        
        $request = $this->getRequest();
        
        if($request->isPost()) {
            $this->post_data = $this->getPostData();
            call_user_func($this->cb_is_post);
            $this->loadPostUbigeo();
            $this->form->setData($this->post_data);
            
            $valid = $this->verifyFileUploads();
            
            if($valid && $this->form->isValid()) {
                $this->form_data       = $this->form->getData();
                $this->form_data['id'] = $this->id;
                
                $this->processCreatedUpdatedBy();
                
                call_user_func($this->cb_is_valid);
                
                $this->prepareFormData();
                if ($this->has_ubigeo) {
                    $this->prepareUbigeoForSave();
                }
                $this->saveRecord();
                $this->processFileUploads();
                
                call_user_func($this->cb_after_save);
                
                return $this->redirect()->toRoute($this->route, array(), array('query' => array('page' => $this->page)));
            }
            else {
                call_user_func($this->cb_not_valid);
                $this->errors = $this->form->getMessages();
            }
        }
        else {
            call_user_func($this->cb_is_get);
            
            if($this->id > 0) {
                $this->record_data = $this->record->getArrayCopy();
                call_user_func($this->cb_is_get_update);
                $this->form->setData($this->record_data);
                $this->loadGetUbigeo();
            }
            else {
                call_user_func($this->cb_is_get_add);
            }
        }
        
        call_user_func($this->cb_end);
        return true;
    }
    
    
    public function addupdateRender() {
        $variables           = $this->view_vars;
        $variables['record'] = $this->record;
        $variables['flr']    = $this->flr;
        $variables['form']   = $this->form;
        $variables['errors'] = $this->errors;
        $variables['route']  = $this->route;
        $variables['title']  = $this->title;
        $variables['has_ubigeo'] = $this->has_ubigeo;
         
        return $this->render($this->template, $variables, $this->view_options);
    }
    
    
    public function addupdateAction() {
        if($this->noLoggedUser()) {
            return $this->redirect_to('admin');
        }
        
        $this->addupdateConfig();
        $this->addupdateSetup();
        
        if($this->is_allowed($this->perm) === false) {
            return $this->redirect_to('admin');
        }
        
        $this->addupdateGetForm();
        $this->addupdateGetModel();
        $t = $this->addupdateProcessPost();
        
        if($t !== true) {
            return $t;
        }
        
        return $this->addupdateRender();
    }
    
    
    public function getPostData() {
        $request = $this->getRequest();
        
        if($this->has_file_uploads) {
            return array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
        }
        else {
            return $request->getPost()->toArray();
        }
    }
    
    
    public function prepareFormData() {
        foreach($this->form_data as $k => $v) {
            if($k !== 'id') {
                if($v == '') {
                    $e = $this->flr->elements[$k];
                    
                    // Check if value should be considered or ignored
                    if(!isset($e['admin']) ||
                       !isset($e['admin']['save_empty']) ||
                       $e['admin']['save_empty'] == false) {
                         unset($this->form_data[$k]);
                    }
                }
            }
        }
    }
    
    
    public function loadPostUbigeo() {
        if($this->has_ubigeo) {
            $dep  = $this->post_data['department'];
            $prov = $this->post_data['province'];
            $dist = $this->post_data['district'];
            
            $this->form->get('province')->setValueOptions($this->model('Ubigeo\Model\WebUbigeoProvinces')->getValueOptions($dep));
            $this->form->get('district')->setValueOptions($this->model('Ubigeo\Model\WebUbigeoDistricts')->getValueOptions($dep, $prov));
        }
    }
    
    
    public function loadGetUbigeo() {
        if($this->has_ubigeo) {
            $dep  = $this->record_data['department_id'];
            $prov = $this->record_data['province_id'];
            $dist = $this->record_data['district_id'];
            
            $this->form->get('department')->setValue($dep);
            $this->form->get('province')->setValue($prov);
            $this->form->get('district')->setValue($dist);
            
            $this->form->get('province')->setValueOptions($this->model('Ubigeo\Model\WebUbigeoProvinces')->getValueOptions($dep));
            $this->form->get('district')->setValueOptions($this->model('Ubigeo\Model\WebUbigeoDistricts')->getValueOptions($dep, $prov));
        }
    }
    
    
    public function prepareUbigeoForSave() {
        $this->form_data['department_id'] = $this->form_data['department'];
        $this->form_data['province_id']   = $this->form_data['province'];
        $this->form_data['district_id']   = $this->form_data['district'];
    }
    
    
    public function processCreatedUpdatedBy() {
        $user = $this->getLoggedUser();
        $user_id = $user['id'];
        
        if($this->id == 0) {
            if($this->has_created_updated_by) {
                $this->form_data['created_by'] = $user_id;
                $this->form_data['updated_by'] = $user_id;
            }
        }
        else {
            if($this->has_created_updated_by) {
                $this->form_data['updated_by'] = $user_id;
            }
        }
    }
    
    
    public function getFileUploads() {
        if($this->use_files_module) {
            foreach($this->files as $name => $f) {
                $this->files[$name] = $this->model('Files\Model\File')->get_file_for($this->record->getTableName(), $this->id, $name);
            }
        }
    }
    
    
    public function verifyFileUploads() {
        // TODO
        // See a clean way to verify the user uploaded a file and also
        // remove its requirement
        return true;
    }
    
    
    public function processFileUploads() {
        $id         = $this->id;
        $params     = $this->form_data;
        $table_name = $this->record->getTableName();
        
        foreach($this->files as $name => $f) {
            $e = $this->flr->elements[$name];
            
            if(isset($params[$name]) && $params[$name]['name'] != '') {
                $filename = $this->getFileUploadName($params[$name]['name']);
                
                // Get file upload path
                if(isset($e['admin']) && isset($e['admin']['upload_path'])) {
                    $path = $e['admin']['upload_path'];
                }
                else {
                    $path = $this->file_uploads_path;
                }
                
                @mkdir($path, 0777, true);
                
                move_uploaded_file($params[$name]['tmp_name'], $path . $filename);
                
                if($this->use_files_module) {
                    $file = $this->model('Files\Model\File');
                    $file->delete(array('table_name' => $table_name, 'table_id' => $id, 'name' => $name));
                    
                    $file->table_id   = $id;
                    $file->table_name = $table_name;
                    $file->name       = $name;
                    $file->filename   = $filename;
                    $file->path       = $path;
                    $file->save();
                }
            }
            else {
                $this->files[$name] = false;
                
                // Handle removing file upload
                if(isset($e['admin']) && isset($e['admin']['accept_remove']) && $e['admin']['accept_remove'] == true) {
                    if(isset($_POST['_remove_' . $name])) {
                        $file = $this->model('Files\Model\File');
                        $file->delete(array('table_name' => $table_name, 'table_id' => $id, 'name' => $name));
                    }
                }
            }
        }
        
        return true;
    }
    
    
    public function getFileUploadName($name) {
        $f = pathinfo($name);
        return slugify(date('YmsHis') . '-' . $f['filename']) . '.' . $f['extension'];
    }
    
    
    public function saveRecord() {
        $this->record->exchangeArray2($this->form_data);
        $this->id = $this->record->save();
    }
    
    
    public function updateConfig() {
        $this->addupdateConfig();
    }
    
    
    public function updateSetup() {
    // Using the variables and definitions made in Config(), here we
    // autoconfigure everything else we need. Magic happens here.
        $this->coreSetup();
        $this->perm = $this->perm . '-update';
    }
    
    
    public function updateGetForm() {
        $this->addupdateGetForm();
    }
    
    
    public function updateGetModel() {
        $this->addupdateGetModel();
    }
    
    
    public function updateProcessPost() {
        return $this->addupdateProcessPost();
    }
    
    
    public function updateRender() {
        return $this->addupdateRender();
    }
    
    
    public function updateAction() {
        if($this->noLoggedUser()) {
            return $this->redirect_to('admin');
        }
        
        $this->updateConfig();
        $this->updateSetup();
        
        if($this->is_allowed($this->perm) === false) {
            return $this->redirect_to('admin');
        }
        
        $this->updateGetForm();
        $this->updateGetModel();
        $t = $this->updateProcessPost();
        
        if($t !== true) {
            return $t;
        }
        
        return $this->updateRender();
    }
    
    
    public function addConfig() {
        $this->addupdateConfig();
    }
    
    
    public function addSetup() {
    // Using the variables and definitions made in Config(), here we
    // autoconfigure everything else we need. Magic happens here.
        $this->coreSetup();
        $this->perm = $this->perm . '-add';
    }
    
    
    public function addGetForm() {
        $this->addupdateGetForm();
    }
    
    
    public function addGetModel() {
        $this->addupdateGetModel();
    }
    
    
    public function addProcessPost() {
        return $this->addupdateProcessPost();
    }
    
    
    public function addRender() {
        return $this->addupdateRender();
    }
    
    
    public function addAction() {
        if($this->noLoggedUser()) {
            return $this->redirect_to('admin');
        }
        
        $this->addConfig();
        $this->addSetup();
        
        if($this->is_allowed($this->perm) === false) {
            return $this->redirect_to('admin');
        }
        
        $this->addGetForm();
        $this->addGetModel();
        $t = $this->addProcessPost();
        
        if($t !== true) {
            return $t;
        }
        
        return $this->addRender();
    }
    
    
    public function deleteAction() {
        $this->coreSetup();
        $id = intval($this->params()->fromRoute('id'));
        
        if($this->has_file_uploads && $this->use_files_module) {
            $table_name = $this->model($this->model_name)->getTableName();
            $this->model('Files\Model\File')->delete(array('table_name' => $table_name, 'table_id' => $id));
        }
        
        $this->model($this->model_name)->delete(array('id' => $id));
        
        return $this->redirect()->toRoute($this->route, array(), array('query' => array('page' => $this->page)));
    }
    
    
    public function render($tpl_name = '', $variables = array(), $options = array()) {
        $result = new ViewModel();
        if(array_key_exists('no_layout', $variables)) {
            $result->setTerminal(true);
        }
        else {
            if(!array_key_exists('is-login', $variables)) {
                $this->is_allowed($this->perm);
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

