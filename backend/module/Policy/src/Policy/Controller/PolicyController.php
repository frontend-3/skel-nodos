<?php
namespace Policy\Controller;

use Base\BaseController;

class PolicyController extends BaseController
{
    public function indexAction()
    {
        $route = $this->get_routed_match();
        $table_seo = $this->SL()->get('ServiceSeo');
        $seo = $table_seo->getSeoByRoute($route);
        if($seo){
            $vars['seo'] = $seo;
        }
        $table = $this->SL()->get('ServicePolicyTable');
        $route = $this->get_routed_match();
        switch($route){
            case 'terms':
                $slug = 'terminos-y-condiciones';
            break;
        }
        $result = $table->first(array('slug'=>$slug),array('title','content'));
        $vars['result']=$result;
        return $this->render('site/tyc',$vars);
    }
} 