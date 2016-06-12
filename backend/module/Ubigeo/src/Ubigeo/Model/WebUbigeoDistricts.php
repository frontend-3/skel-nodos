<?php
namespace Ubigeo\Model;

use Base\BaseModelPlus;

class WebUbigeoDistricts extends BaseModelPlus {
    public $id;
    public $name;
    public $cod_dpto;
    public $cod_prov;

    public function __construct($dbAdapter) {
        parent::__construct($dbAdapter, 'web_ubigeo_districts', array());
    }
    
    
    public function getList($cod_dpto, $cod_prov) {
        return $this->all(NULL, array('cod_dpto' => $cod_dpto, 'cod_prov' => $cod_prov));
    }
    
    
    public function getValueOptions($cod_dpto, $cod_prov) {
        $districts = $this->getList($cod_dpto, $cod_prov);
        
        $list = array();
        foreach($districts as $d) {
            $list[$d->id] = $d->name;
        }
        
        return $list;
    }
}

?>
