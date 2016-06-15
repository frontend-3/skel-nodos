<?php

namespace AdminAuth\Model;

use Base\BaseModel;


class AdminRole extends BaseModel{

    public $name;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct(){
        parent::__construct('web_system_auth_roles');
    }
}