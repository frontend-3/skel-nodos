<?php

namespace User\Model;

use User\Model\WebUser;
use Zend\Db\Adapter\Adapter;
use Base\BaseTable;

class WebUserTable extends BaseTable{

    public function __construct(Adapter $dbAdapter) {
        parent::__construct($dbAdapter,new WebUser());
    }
}
