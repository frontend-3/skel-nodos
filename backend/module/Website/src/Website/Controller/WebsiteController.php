<?php

namespace Website\Controller;
use Base\BaseController;
use Florence\Florence;

class WebsiteController extends BaseController {
    public function indexAction() {
        return $this->render('website/index');
    }
}

