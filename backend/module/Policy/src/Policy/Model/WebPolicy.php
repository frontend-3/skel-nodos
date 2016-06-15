<?php
namespace Policy\Model;

use Base\BaseModelPlus;

class WebPolicy extends BaseModelPlus {

    public $title;
    public $slug;
    public $content;
    public $position;
    
    public function __construct($dbAdapter) {
        parent::__construct($dbAdapter, 'web_policy', array());
        $this->slug_field = 'title';
    }
    
    
    public function get($slug) {
    // Returns the title and content given the slug
        return $this->first("slug='" . $slug . "'", array('title', 'content', 'slug'));
    }
}

?>