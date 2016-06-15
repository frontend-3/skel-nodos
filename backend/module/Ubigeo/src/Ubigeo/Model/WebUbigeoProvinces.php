<?php
namespace Ubigeo\Model;

use Base\BaseModelPlus;

class WebUbigeoProvinces extends BaseModelPlus {
    public $id;
    public $name;
    public $cod_dpto;

    public function __construct($dbAdapter) {
        parent::__construct($dbAdapter, 'web_ubigeo_provinces', array());
    }
    
    
    public function getList($cod_dpto) {
        return $this->all(NULL, array('cod_dpto' => $cod_dpto));
    }
    
    
    public function getValueOptions($cod_dpto) {
        $provinces = $this->getList($cod_dpto);
        
        $list = array();
        foreach($provinces as $p) {
            $list[$p->id] = $p->name;
        }
        
        return $list;
    }
}

?>
