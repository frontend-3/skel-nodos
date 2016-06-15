<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 25/02/2015
 * Time: 11:23 AM
 */

namespace Social\Table;

use Base\BaseTable;
use Social\Model\WebSocial;
use Zend\Db\Adapter\Adapter;

class WebSocialTable extends BaseTable{
    public function __construct(Adapter $adapter){
        parent::__construct($adapter,new WebSocial());
    }
}