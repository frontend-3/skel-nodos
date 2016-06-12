<?php
namespace Ubigeo\Model;

use Base\BaseModelPlus;

class WebUbigeoDepartments extends BaseModelPlus {
    public $id;
    public $name;

    public function __construct($dbAdapter) {
        parent::__construct($dbAdapter, 'web_ubigeo_departments', array());
    }
}

?>
