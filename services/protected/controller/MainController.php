<?php
/**
 * Description of MainController
 *
 * @author darkredz
 */
class MainController extends DooController{

    public function index( )	{
        $data['baseurl'] = Doo::conf()->APP_URL;
        $this->view( )->renderc( 'guide/guide', $data );
    }

    public function gen_models(){
        Doo::loadCore('db/DooModelGen');
        DooModelGen::genMySQL();
    }
}
