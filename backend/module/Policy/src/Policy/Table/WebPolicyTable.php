<?php

namespace Policy\Table;

use Zend\Db\Adapter\Adapter;

use Base\BaseTable;
use Policy\Model\WebPolicy;

class WebPolicyTable extends BaseTable{

    public function __construct(Adapter $dbAdapter) {
        parent::__construct($dbAdapter,new WebPolicy());
    }
} 