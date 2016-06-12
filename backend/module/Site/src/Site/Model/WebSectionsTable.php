<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 25/02/2015
 * Time: 11:32 AM
 */

namespace Site\Model;

use Base\BaseTable;
use Site\Model\WebSecionts;
use Zend\Db\Adapter\Adapter;

class WebSectionsTable extends BaseTable{
    public function __construct(Adapter $adapter){
        parent::__construct($adapter,new WebSecionts());
    }
}