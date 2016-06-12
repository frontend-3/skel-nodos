<?php

namespace Seo\Controller;
use Base\AdminBaseController;
use Seo\Model\WebSeo;



class LoadController extends AdminBaseController{


    public function indexAction()
    {
    	$table_auth = $this->SL()->get('ServiceAuthUser');
    	$user = $table_auth->first(array(),array('id'));
      $routes = array('home','error','server_error','contact','suscribe','terms');

      $table_seo = $this->SL()->get('ServiceSeo');

   		foreach ($routes as $key => $value) {
   			$m = new WebSeo();
   			$m->route = $value;
        $m->title = $value;
   			$m->seo_title = 'Seo Tile '.$value;
   			$m->seo_description = "Seo Description ".$value;
   			$m->seo_keywords = "Seo Keyworkds ".$value;
   			$m->seo_noindex = "0";
   			$m->created_by = $user['id'];
   			$table_seo->save($m);
   		}

    }

} 