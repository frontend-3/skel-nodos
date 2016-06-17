<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 25/02/2015
 * Time: 11:24 AM
 */

namespace Social\Model;


use Base\BaseTable;
use Social\Model\WebSocialOg;
use Zend\Db\Adapter\Adapter;

class WebSocialOgTable extends BaseTable{
    public function __construct(Adapter $adapter){
        parent::__construct($adapter,new WebSocialOg());
    }
}