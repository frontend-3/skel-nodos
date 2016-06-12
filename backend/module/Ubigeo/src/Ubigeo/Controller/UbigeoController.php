<?php
namespace Ubigeo\Controller;

use Zend\View\Model\JsonModel;
use Base\BaseController;
use Ubigeo\Ubigeo;

class UbigeoController extends BaseController {

    public function getDepartmentsAction() {
        $request = $this->getRequest();
        $valid = false;
        if ($request->isPost() || $request->isGet()) {
            if($request->isXmlHttpRequest()){
                $valid = true;
            }
        }
        if($valid) {
            $ubigeo = new Ubigeo($this->SL());
            $depts = $ubigeo->getDepartments();
        }
        else {
            $depts = array();
        }
        
        if(count($depts) > 0) {
            return new JsonModel($depts);
        }
        $response = $this->getResponse();
        $response->setStatusCode(404);
    }


    public function getProvincesAction() {
        $request = $this->getRequest();
        $province_table = $this->get_service('ServiceProvinces');
        $cod_dpto = '';
        $prov_json = array();
        if ($request->isPost()) {
            if($request->isXmlHttpRequest()){
                $data = $this->getRequest()->getPost();
                $cod_dpto = $data['cod_dpto'];
            }
        }
        if($request->isGet()) {
            if($request->isXmlHttpRequest()){
                $cod_dpto = $this->params()->fromQuery('cod_dpto');
            }
        }
        if($cod_dpto != '') {
            $ubigeo = new Ubigeo($this->SL());
            $prov_json = $ubigeo->getProvinces($cod_dpto);
        }
        if(count($prov_json)>0){
            return new JsonModel($prov_json);
        }
        $response = $this->getResponse();
        $response->setStatusCode(404);
    }


    public function getDistrictsAction() {
        $request = $this->getRequest();
        $district_table = $this->get_service('ServiceDistricts');
        $dist_json = array();
        $cod_dpto = '';
        $cod_prov = '';
        if ($request->isPost()) {
            if($request->isXmlHttpRequest()) {
                $data = $this->getRequest()->getPost();
                $cod_dpto = $data['cod_dpto'];
                $cod_prov = $data['cod_prov'];
            }
        }
        if($request->isGet()) {
            if($request->isXmlHttpRequest()) {
                $cod_dpto = $this->params()->fromQuery('cod_dpto');
                $cod_prov = $this->params()->fromQuery('cod_prov');
            }
        }
        if($cod_dpto!='' && $cod_prov !='') {
            $ubigeo = new Ubigeo($this->SL());
            $dist_json = $ubigeo->getDistricts($cod_dpto, $cod_prov);
        }
        if(count($dist_json)>0){
            return new JsonModel($dist_json);
        }
        $response = $this->getResponse();
        $response->setStatusCode(404);
    }

}

?>
