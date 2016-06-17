<?php
/**
 * Created by PhpStorm.
 * User: Flash
 * Date: 19/11/14
 * Time: 11:35 AM
 */

namespace Contact\Controller;

use Base\Controller\BaseController;
use Contact\Form\WebContactForm;
use Contact\InputFilter\WebContactInputFilter;
use Contact\Model\WebContact;

class ContactController extends BaseController{
    public function indexAction(){
        $request = $this->getRequest();
        $table = $this->SL()->get('ServiceContactTable');

        $renderer = $this->SL()->get(
            'Zend\View\Renderer\RendererInterface');
        $url = $renderer->basePath('captcha');
        $form = new WebContactForm(true,$url);
        $table_categories = $this->SL()->get('ServiceContactCategoryTable');
        $categories = $table_categories->all(array('id','name'));
        $form->get('category_id')->setValueOptions($this->toArray($categories));

        if($request->isPost()){
            $form->setData($request->getPost());
            $inputFilter = new WebContactInputFilter($this->getDBAdapter());
            $form->setInputFilter($inputFilter->getInputFilter());
            if($form->isValid()){
                $model = new WebContact();
                $model->exchangeArray($form->getData());
                $table->save($model);
            }else{
                var_dump($form->getMessages());
            }
        }
        $variables = array('form'=>$form);
        return $this->render('site/contact/home',$variables);
    }

    private function toArray($data){
        $return = array();
        foreach ($data as $dat) {
            $return[$dat['id']]=$dat['name'];
        }
        return $return;

    }
} 