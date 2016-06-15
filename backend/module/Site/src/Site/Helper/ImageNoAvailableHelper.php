<?php

/**
 * Pepsico\module\Search
 * 
 * @author
 * @version 
 */

namespace Site\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * View Helper
 */
class ImageNoAvailableHelper extends AbstractHelper {

    public function __invoke($image_path) {
        if (file_exists($image_path)) {
            return $image_path;
        } else {
                 return $image_path;
           // return 'http://placehold.it/380x298';
        }
    }

}
