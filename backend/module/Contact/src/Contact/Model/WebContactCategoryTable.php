<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 30/12/14
 * Time: 04:42 PM
 */

namespace Contact\Model;


use Base\BaseTable;
use Contact\Model\WebContactCategory;
use Zend\Db\Adapter\Adapter;

class WebContactCategoryTable extends BaseTable{
    public function __construct(Adapter $dbAdapter){
        parent::__construct($dbAdapter,new WebContactCategory());
    }
} 