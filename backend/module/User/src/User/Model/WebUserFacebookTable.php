<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 30/12/14
 * Time: 04:03 PM
 */

namespace User\Model;

use Base\BaseTable;
use User\Model\WebUserFacebook;
use Zend\Db\Adapter\Adapter;

class WebUserFacebookTable extends BaseTable{
    public function __construct(Adapter $dbAdapter){
        parent::__construct($dbAdapter,new WebUserFacebook());
    }
} 