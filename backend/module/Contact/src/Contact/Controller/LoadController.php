<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 30/12/14
 * Time: 04:39 PM
 */

namespace Contact\Controller;


use Contact\Model\WebContactCategory;
use Zend\Db\Sql\Sql;
use Zend\Mvc\Controller\AbstractActionController;

class LoadController extends AbstractActionController{
    public function loadCategoryAction(){
        $sm = $this->getServiceLocator();
        $m = new WebContactCategory();
        echo "start load Category Contactss".PHP_EOL;
        $table = $sm->get('ServiceContactCategoryTable');
        for($x=0;$x<3;$x++){
            $m->name = 'Categoria '.$x;
            $table->save($m);
        }

        echo "loading....".PHP_EOL;

        echo "end load Category Contactss";
    }

    private function deleteTable($table_name) {
        $sm = $this->getServiceLocator();
        $adapter = $sm->get('Zend\Db\Adapter\Adapter');
        $sql = new Sql($adapter);
        $select = $sql->delete($table_name);
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
    }

}