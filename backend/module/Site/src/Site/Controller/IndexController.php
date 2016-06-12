<?php

namespace Site\Controller;
use Base\BaseController;

class IndexController extends BaseController {

    public function indexAction() {
        return $this->render('site/home');
    }
    
    
    public function error404Action() {
        return $this->render('site/404');
    }
}

