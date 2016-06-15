<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 30/12/14
 * Time: 04:41 PM
 */

namespace Contact\Model;


use Base\BaseModel;

class WebContactCategory extends BaseModel{
    public $name;
    public $status;

    public function __construct(){
        parent::__construct('web_contact_categories');
    }
} 