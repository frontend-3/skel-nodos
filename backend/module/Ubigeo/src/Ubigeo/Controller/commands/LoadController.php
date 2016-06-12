<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 29/12/14
 * Time: 05:16 PM
 */

namespace Ubigeo\Controller\commands;

use Base\Controller\BaseController;
use Model\Ubigeo\WebUbigeoDistricts;
use Ubigeo\Model\WebUbigeoDepartments;
use Zend\Db\Sql\Sql;

class LoadController extends  BaseController{

    public function loadAction(){

    }

    private function deleteTable($table_name) {
        $sm = $this->getServiceLocator();
        $adapter = $sm->get('Zend\Db\Adapter\Adapter');
        $sql = new Sql($adapter);
        $select = $sql->delete($table_name);
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
    }

    public function departmentLoadAction(){
        $sm = $this->getServiceLocator();
        $department_table = $sm->get('ServiceDepartmentsTable');
        $depts = json_decode(file_get_contents('ubigeo/departments.json'));
        $this->deleteTable('web_ubigeo_districts');
        $this->deleteTable('web_ubigeo_provinces');
        $this->deleteTable('web_ubigeo_departments');
        $m = new WebUbigeoDepartments();
        echo "start load departaments".PHP_EOL;
        echo "loading....".PHP_EOL;
        foreach ($depts as $dept) {
            $m->id = $dept->id;
            $m->name = $dept->name;
            $department_table->save($m,'load');
        }
        echo "end load departaments";
    }

    public function provinceLoadAction(){
        $sm = $this->getServiceLocator();
        $province_table = $sm->get('ServiceProvincesTable');
        $provs = json_decode(file_get_contents('ubigeo/provinces.json'));
        $this->deleteTable('web_ubigeo_provinces');
        $m = new WebUbigeoProvinces();
        echo "start load province".PHP_EOL;
        echo "loading....".PHP_EOL;
        foreach ($provs as $prov) {
            $m->id = $prov->id;
            $m->name = $prov->name;
            $m->cod_dpto = $prov->cod_dpto;
            $province_table->save($m,'load');
        }
        echo "end load province";
    }

    public function districLoadAction(){
        $sm = $this->getServiceLocator();
        $district_table = $sm->get('ServiceDistrictsTable');
        $dists = json_decode(file_get_contents('ubigeo/districts.json'));
        $this->deleteTable('web_ubigeo_districts');
        $m = new WebUbigeoDistricts();
        echo "start load district".PHP_EOL;
        echo "loading....".PHP_EOL;
        foreach ($dists as $dist) {
            $m->id = $dist->id;
            $m->name = $dist->name;
            $m->cod_dpto = $dist->cod_dpto;
            $m->cod_prov = $dist->cod_prov;
            $district_table->save($m,'load');
        }
        echo "end load district".PHP_EOL;
    }


} 