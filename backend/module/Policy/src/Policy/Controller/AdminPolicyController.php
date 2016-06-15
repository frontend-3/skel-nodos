<?php
namespace Policy\Controller;

use Base\AdminBaseControllerPlus;
use Policy\Model\WebPolicy;

class AdminPolicyController extends AdminBaseControllerPlus {
    public $model_name             = 'Policy\Model\WebPolicy';
    public $formdef                = 'form';
    public $has_created_updated_by = false;
    
    public function listAction() {
        $this->display_fields = array('id', 'title');
        $this->pages          = 15;
        
        return parent::listAction();
    }
    
    
    public function addAction() {
        return parent::addAction();
    }
}

?>