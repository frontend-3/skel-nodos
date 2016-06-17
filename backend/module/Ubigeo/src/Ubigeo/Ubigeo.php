<?php
namespace Ubigeo;

class Ubigeo {
    private $service_locator;
    
    public function __construct($service_locator) {
        $this->service_locator = $service_locator;
    }
    
    
    public function getDepartments() {
        $table = $this->service_locator->get('ServiceDepartments');
        $data  = $table->all(array('id','name'), null, array('name asc'));
        
        $r = array();
        
        if($data) {
            foreach ($data as $d) {
                $r[] = array("id" => $d['id'], 'name' => $d['name']);
            }
        }
        
        return $r;
    }
    

    public function getProvinces($cod_dpto) {
        $table = $this->service_locator->get('ServiceProvinces');
        $data  = $table->all(array('id', 'name'), array('cod_dpto' => $cod_dpto), array('name asc'));
        
        $r = array();
        
        if($data) {
            foreach ($data as $d) {
                $r[] = array("id" => $d['id'], 'name' => $d['name']);
            }
        }
        
        return $r;
    }
    
    
    public function getDistricts($cod_dpto, $cod_prov) {
        $table = $this->service_locator->get('ServiceDistricts');
        $data  = $table->all(array('id', 'name'), array('cod_dpto' => $cod_dpto, 'cod_prov' => $cod_prov), array('name asc'));
        
        $r = array();
        
        if($data) {
            foreach ($data as $d) {
                $r[] = array("id" => $d['id'], 'name' => $d['name']);
            }
        }
        
        return $r;
    }

}

