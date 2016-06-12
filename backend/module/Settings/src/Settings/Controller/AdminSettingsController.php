<?php
namespace Settings\Controller;

use Base\AdminBaseControllerPlus;

class AdminSettingsController extends AdminBaseControllerPlus {
    public $model_name = 'Settings\Model\Setting';
    public $formdef    = 'settings';

    public function listAction() {
        $this->display_fields = array('id', 'key', 'value');
        $this->order = 'key';
        return parent::listAction();
    }
}