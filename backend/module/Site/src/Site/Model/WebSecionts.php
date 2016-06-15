<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 25/02/2015
 * Time: 11:29 AM
 */

namespace Site\Model;

use Base\Model\BaseModel;

class WebSecionts extends BaseModel{
    public $id;
    public $name;
    public function __construct(){
        parent::__construct('web_sections');
    }
}