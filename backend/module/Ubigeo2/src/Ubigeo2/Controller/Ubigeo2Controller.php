<?php
/**
 * This file is part of Ubigeo2 Zend Framework 2 module.
 */

namespace Ubigeo2\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Ubigeo2\Service\Ubigeo2Service;

/**
 * Controller
 * 
 * Provides the JSON responses for loading Ubigeo SELECTs using AJAX.
 * 
 * @package Ubigeo2
 * @version v1.0.0
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
*/
class Ubigeo2Controller extends AbstractActionController
{
    /**
     * Departments
     * 
     * Returns a JSON View with a list of Departments.
     * 
     * Can only be accessed by XHR, either GET or POST.
     * Will return a 404 error otherwise.
     * 
     * @return Zend\View\Model\JsonModel|Zend\Http\Response
     */
    public function departmentsAction()
    {
        $request = $this->getRequest();
        
        if (!$request->isXmlHttpRequest()) {
            return $this->notFoundAction();
        }
        
        $ubigeo = new Ubigeo2Service($this->getServiceLocator());
        $list = $ubigeo->getDepartmentsList();
        return new JsonModel($list);
    }
    
    
    /**
     * Provinces
     * 
     * Returns a JSON View with a list of Provinces. Needs a `departmentID`
     * integer GET or POST parameter.
     * 
     * Can only be accessed by XHR, either GET or POST.
     * Will return a 404 error otherwise.
     * 
     * @return Zend\View\Model\JsonModel|Zend\Http\Response
     * @return Zend\View\Model\JsonModel|Zend\Http\Response
     */
    public function provincesAction()
    {
        $request = $this->getRequest();
        
        if (!$request->isXmlHttpRequest()) {
            return $this->notFoundAction();
        }
        
        if ($request->isPost()) {
            $departmentID = $this->params()->fromPost('departmentID');
            
        } elseif ($request->isGet()) {
            $departmentID = $this->params()->fromQuery('departmentID');
        }
        
        if (is_null($departmentID) || !is_numeric($departmentID)) {
            return $this->notFoundAction();
        }
        
        $ubigeo = new Ubigeo2Service($this->getServiceLocator());
        $list = $ubigeo->getProvincesList($departmentID);
        return new JsonModel($list);
    }


    /**
     * Districts
     * 
     * Returns a JSON View with a list of Districts. Needs a `departmentID`
     * and `provinceID` integer GET or POST parameters.
     * 
     * Can only be accessed by XHR, either GET or POST.
     * Will return a 404 error otherwise.
     * 
     * @return Zend\View\Model\JsonModel|Zend\Http\Response
     * @return Zend\View\Model\JsonModel|Zend\Http\Response
     */
    public function districtsAction() {
        $request = $this->getRequest();
        
        if (!$request->isXmlHttpRequest()) {
            return $this->notFoundAction();
        }
        
        if ($request->isPost()) {
            $departmentID = $this->params()->fromPost('departmentID');
            $provinceID = $this->params()->fromPost('provinceID');
            
        } elseif ($request->isGet()) {
            $departmentID = $this->params()->fromQuery('departmentID');
            $provinceID = $this->params()->fromQuery('provinceID');
        }
        
        if (is_null($departmentID) || !is_numeric($departmentID) ||
            is_null($provinceID) || !is_numeric($provinceID)) {
            return $this->notFoundAction();
        }
        
        $ubigeo = new Ubigeo2Service($this->getServiceLocator());
        $list = $ubigeo->getDistrictsList($departmentID, $provinceID);
        return new JsonModel($list);
    }
}

