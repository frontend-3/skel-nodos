<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 25/02/2015
 * Time: 11:22 AM
 */

namespace Social\Model;


use Base\Model\BaseModel;

class WebSocialOg extends BaseModel{
    public $share_name;
    public $share_caption;
    public $share_description;
    public $share_image;
    public $share_pla_title;
    public $share_pla_description;
    public $share_pla_image;
    public $section_id;
    public function __construct(){
        parent::__construct('web_social_og');
    }
}