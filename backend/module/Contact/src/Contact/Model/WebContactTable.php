<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 20/11/14
 * Time: 11:20 AM
 */

namespace Contact\Model;

use Base\Table\BaseTable;
use Contact\Model\WebContact;
use Zend\Db\Adapter\Adapter;

class WebContactTable extends BaseTable{

    public function __construct(Adapter $dbAdapter) {
        parent::__construct($dbAdapter,new WebContact());
    }
} 