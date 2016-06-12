<?php
namespace Seo\Controller;

use Base\AdminBaseControllerPlus;
use Seo\Model\WebSeo;
use Files\Model\File;
use Seo\Form\WebSeoForm;
use Zend\Crypt\Password\Bcrypt;

class AdminSeoController extends AdminBaseControllerPlus {
    public $model_name       = 'Seo\Model\Seo';
    public $formdef          = 'seo';
    public $title            = 'SEO';
    public $has_file_uploads = true;
    public $use_files_module = true;

    public function listAction() {
        $this->display_fields = array('id','title','seo_title');
        $this->pages          = 20;
        
        return parent::listAction();
    }
} 
