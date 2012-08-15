<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Nelson Daza
 * Date: 4/08/12
 * Time: 16:25
 * To change this template use File | Settings | File Templates.
 */
//Doo::loadController('IDGlobalController');


class IDSessionController extends IDGlobalController
{
    public function index( )    {
        $data = array( );
        $data['active'] = false;
        $data['action'] = '/user/home';

        if ( self::$session->user ) {
            $data['active'] = true;
        }
        else    {
            $signed_request = ( isset( $_REQUEST["signed_request"] ) ? $_REQUEST["signed_request"]: '' );
            $info = array( );

            if( $signed_request )   {
                list( $encoded_sig, $payload ) = explode( '.', $signed_request, 2 );
                $info = json_decode( base64_decode( strtr( $payload, '-_', '+/' ) ), true );
            }

            if ( empty( $info["user_id"] ) )    {
                $data['action'] = 'redirect';
                $data['url'] = "http://www.facebook.com/dialog/oauth?client_id=" . Doo::conf()->FACEBOOK['APP_ID'] . "&redirect_uri=" . urlencode( Doo::conf()->FACEBOOK['CANVAS_PAGE'] );
            }
            else    {
                self::$session->user = array( );
                self::$session->user['id'] = $info["user_id"];
                $data['action'] = '/user/home';
                $data['session'] = $info;
            }
        }

        $this->renderc( $data );
    }

    public function code( )    {
        $data = array( );
        $data['active'] = false;
        $data['action'] = '/home';

        if( $this->params['code'] ) {
            if ( self::$session->user ) {
                $data['active'] = true;
            }
            else    {
                $app_access_token = file_get_contents( "https://graph.facebook.com/oauth/access_token?client_id=" . Doo::conf()->FACEBOOK['APP_ID'] . "&redirect_uri=" . urlencode( Doo::conf()->FACEBOOK['CANVAS_PAGE'] ) . "&client_secret=" . Doo::conf()->FACEBOOK['APP_SECRET'] . "&code=" . $this->params['code'] );
                $user = file_get_contents( "https://graph.facebook.com/me?" . $app_access_token );
                self::$session->user = $user;
                $data['active'] = true;
                $data['user'] = $user;
            }
        }
        $this->renderc( $data );
    }
}