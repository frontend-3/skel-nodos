<?php
namespace Settings\Model;

use Base\BaseModelPlus;

class Setting extends BaseModelPlus {
    public $key;
    public $value;
    public $status;

    public function __construct($dbAdapter) {
        parent::__construct($dbAdapter, 'web_settings', array());
    }
}

?>