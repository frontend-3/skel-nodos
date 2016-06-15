<?php

namespace Base;

require 'functions.php';

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;


class BaseController extends AbstractActionController {

    protected $sm = null;
    private $settings = null;

    public function __construct() {
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection:1; mode=block');
        header('X-Permitted-Cross-Domain-Policies: master-only');
        header('X-Content-Type-Options: nosniff');
        header('X-Content-Security-Policy: allow \'self\'');
    }
    
    
    public function destroy(){
        $session = new Container('webuser_session');
        $session->getManager()->destroy();

        $session = new Container('route_session');
        $session->getManager()->destroy();
    }
    

    public function getDBAdapter() {
        $dbadapter = $this->getServiceLocator()->get(
            'Zend\Db\Adapter\Adapter');
        return $dbadapter;
    }


    public function SL() {
        if(!$this->sm) {
            $this->sm = $this->getServiceLocator();
        }
        return $this->sm;
    }
    

    public function render($tpl_name = '', $variables = array(), $options = array()){
    // You can call render in two ways:
    // Old one:
    //    $this->render($template_name, $variables, $options);
    // 
    // New one:
    //    $this->render();
    // Infers the template name from the current controller and name.
    // It uses the $view_variables and $view_options properties.
    // 
    // The old way is kept for backwards compatibility. It would be better to at
    // least use the $view_variables and $view_options.
        
        if($tpl_name == '') {
            $tpl_name = str_replace('_controller', '', $this->params('controller')) . '/' . ($this->get_config('site')['backend_template'] ? '_' : '') . $this->params('action');
        }
        else {
            if($this->get_config('site')['backend_template']) {
                // Split view file path
                $parts = array_reverse(explode('/', $tpl_name));
                $parts[0] = '_' . $parts[0];
                $tpl_name = join(array_reverse($parts), '/');
            }
        }
        
        if($variables == array() && isset($this->view_variables)) {
            $variables = $this->view_variables;
        }
        
        if($options == array() && isset($this->view_options)) {
            $options = $this->view_options;
        }
        
        $result = new ViewModel($variables, $options);
        $result->setTerminal(true);
        $result->setTemplate($tpl_name);
        return $result;
    }
    

    public function getPublicUrl($asset, $full=false){
        $helper_name = $full ? 'ServerUrl' : 'basePath';
        $helper = $this->getServiceLocator()->get('ViewHelperManager')->get($helper_name);
        return $helper->__invoke($asset);
    }


    public function get_current_url() {
        $helper = $this->getServiceLocator()->get('ViewHelperManager')->get('ServerUrl');
        $url =  $helper->__invoke(true);
        if(substr($url, -1)=='/'){
            return substr($url, 0,-1);
        }
        return $url;
    }


    public function set_value_session($key, $value){
        $session = new Container('web_site');
        $session->offsetSet($key, $value);
    }
    

    public function get_value_session($key){
        $session = new Container('web_site');
        $val = $session->offsetExists($key);
        if($val){
            return $session->offsetGet($key);
        }
        return NULL;
    }
    

    public function set_current_route($route) {
        $user_session = new Container('route_session');
        $user_session->offsetSet('route', $route);
    }
    

    public function get_routed_match() {
        $routeMatch = $this->SL()->get('Application')->getMvcEvent()->getRouteMatch();
        return $routeMatch->getMatchedRouteName();
    }
    
    
    public function get_service($name) {
        return $this->SL()->get($name);
    }
    
    
    public function model($classname) {
        $dbAdapter = $this->SL()->get('Zend\Db\Adapter\Adapter');
        $classname = '\\' . $classname;
        
        return new $classname($dbAdapter);
    }
    
    
    public function get_configs() {
        return $this->getServiceLocator()->get('Config');
    }
    
    
    public function get_config($index) {
        return $this->getServiceLocator()->get('Config')[$index];
    }
    
    public function get_settings() {
    // Fetch and return the Site's settings as a simple array
        if($this->settings === NULL) {
            $this->settings = array();
            $r = $this->model('Settings\Model\Setting')->all(array('key', 'value'));
            
            foreach($r as $s) {
                $this->settings[$s['key']] = $s['value'];
            }
        }
        
        return $this->settings;
    }
    
    
    public function get_setting($key) {
        if($this->settings !== NULL) {
            return $this->settings[$key];
        }
        else {
            $setting = $this->model('Settings\Model\Setting')->first(array('key' => $key));
            if($setting === false) {
                return false;
            }
            else {
                return $setting->value;
            }
        }
    }
    
    
    public function redirect_to($route, $params = array(), $options = array()) {
        $this->redirect()->toRoute($route, $params, $options);
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
    
    
    public function render_as_csv($data, $name = 'export.csv') {
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $name . '";');
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        
        foreach($data as $line) {
            echo $this->arrayToCsv($line, ',', '"', true) . "\n";
        }
        
        return true;
    }
    
    
    public function arrayToCsv( array &$fields, $delimiter = ';', $enclosure = '"', $encloseAll = false, $nullToMysqlNull = false ) {
        $delimiter_esc = preg_quote($delimiter, '/');
        $enclosure_esc = preg_quote($enclosure, '/');
        
        $output = array();
        foreach ( $fields as $field ) {
            if ($field === null && $nullToMysqlNull) {
                $output[] = 'NULL';
                continue;
            }
            
            // Enclose fields containing $delimiter, $enclosure or whitespace
            if ( $encloseAll || preg_match( "/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field ) ) {
                $output[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
            }
            else {
                $output[] = $field;
            }
        }
        
        return implode( $delimiter, $output );
    }
    
    
    public function get_post_for_multiple($post, $i) {
    // When working with multiple forms, POST values come as an array.
    // Pass the POST variables in $post and this function returns
    // the set with index $i of it.
        $r = array();
        
        foreach($post as $name => $p) {
            if(is_array($p) && isset($p[$i])) {
                $r[$name . '[]'] = $p[$i];
            }
        }
        
        return $r;
    }
    
    
    public function get_form_element_error($element) {
        $hasError = (bool) count($element->getMessages());
        
        if($hasError) {
            return array_values($element->getMessages())[0];
        }
        else {
            return '';
        }
    }
    
    
    public function get_all_form_errors($form) {
    // Returns an array of all elements' error messages in a form
       $errors = array();
       
       foreach($form->getElements() as $element) {
           $errors[$element->getName()] = $this->get_form_element_error($element);
       }
       return $errors;
    }
    
    
    public function _404() {
        $response = $this->getResponse();
        return $response->setStatusCode(404);
        exit;
    }
}
