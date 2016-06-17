<?php
namespace Seo\Model;

use Base\BaseModelPlus;

class Seo extends BaseModelPlus {
    public $route;
    public $seo_title;
    public $seo_noindex;
    public $seo_description;
    public $seo_keywords;
    public $title;

    public function __construct($dbAdapter) {
        parent::__construct($dbAdapter, 'web_seo', array());
    }
    
    
    public function get($route) {
        return $this->first(array('route' => $route));
    }
}

?>