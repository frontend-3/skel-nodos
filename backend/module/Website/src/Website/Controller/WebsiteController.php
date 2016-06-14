<?php
namespace Website\Controller;

use Base2\Base2Controller;

class WebsiteController extends Base2Controller {
    
    public function indexAction() {
        return $this->render();
    }
}

