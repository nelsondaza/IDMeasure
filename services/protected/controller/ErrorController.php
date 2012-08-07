<?php
/**
 * Description of ErrorController
 *
 * @author
 */
class ErrorController extends DooController {
    
    //put your code here
    function index(){
        $this->renderc( '404' );
    }
}
