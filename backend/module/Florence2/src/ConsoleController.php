<?php
/**
 * This file is part of Florence2 Zend Framework 2 module.
 */

namespace Florence2;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\Exception\RuntimeException;

/**
 * Console commands
 * 
 * @version v1.0.0
 * @author Jaime G. Wong <jaime.wong@nodosdigital.pe>
 */
class ConsoleController extends AbstractActionController
{
    /**
     * Export
     * 
     * @return string
     * @since v1.0.0
     */
    public function exportAction()
    {
        $console = $this->getServiceLocator()->get('console');
        
        $format = $this->getRequest()->getParam('format');
        $form   = $this->getRequest()->getParam('form');
        
        try {
            $flr = Florence2::parse($form, $this->getServiceLocator());
            $form = $flr->getForm();
        }
        catch (\Exception $e) {
            $console->writeLine($e->getMessage());
            return 1;
        }
        
        
        $output = '';
        
        if ($format == 'form') {
            $output .= <<<'EOD'
<?php $form->prepare() ?>
<?= $this->form()->openTag($form) ?>


EOD;
            
            foreach ($form->getElements() as $e) {
                $name = $e->getName();
                
                $output .= "<b>$name</b><br />" . PHP_EOL;
                $output .= '<?= $this->formElement($form->get(\'' . $name . '\')) ?><br />' . PHP_EOL;
                $output .= '<?= $this->formError($form->get(\'' . $name . '\')) ?><br />' . PHP_EOL;
                $output .= '<br />' . PHP_EOL;
                $output .= PHP_EOL;
            }
            
            $output .= '<input type="submit" value="Submit"><br />' . PHP_EOL;
            $output .= '</form>' . PHP_EOL;
            $output .= PHP_EOL;
        }
        
        echo $output;
    }
}


