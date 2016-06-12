<?php
namespace Site\Model;

use Base\BaseModel;


class Setting extends BaseModel{

    public $id;
    public $key;
    public $value;
    public $status;
    public $created_at;
    public $updated_at;
    public $created_by;
    public $updated_by;

    public function __construct()
    {
        parent::__construct('web_settings', true,'',true);
    }
} 