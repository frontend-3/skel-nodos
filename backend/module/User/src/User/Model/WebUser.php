<?php

namespace User\Model;

use Base\BaseModel;

class WebUser extends BaseModel{

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $document_type;
    public $document_number;
    public $address;
    public $cod_dpto;
    public $cod_prov;
    public $cod_dist;
    public $status;
    public $gender;
    public $telephone;
    public $birthday;
    public $subscribe;
    public $password;
    public $token_password;
    public $token_activate;

    public function __construct(){
        parent::__construct('web_users',false);
    }


}
