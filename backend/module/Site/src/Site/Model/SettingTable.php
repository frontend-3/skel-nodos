<?php
namespace Site\Model;

use Base\BaseTable;
use Site\Model\Setting;

use Zend\Db\Adapter\Adapter;


class SettingTable extends BaseTable{

    public function __construct(Adapter $dbAdapter) {
        parent::__construct($dbAdapter, new Setting() );
    }
} 
