<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 20/11/14
 * Time: 11:17 AM
 */

namespace Contact\Model;

use Base\BaseModel;

class WebContact extends BaseModel{

    public $full_name;
    public $email;
    public $comment;
    public $status;
    public $category_id;
    public function __construct(){
        parent::__construct('web_contact');
    }

} 