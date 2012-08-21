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
        $data['action'] = null;

        if ( self::$session->user ) {
            $data['active'] = true;
            $data['action'] = 'home';
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
                $data['action'] = 'home';
                $data['session'] = $info;
            }
        }

        $this->renderc( $data );
    }

    public function code( )    {
        $data = array( );
        $data['active'] = false;

        if( $this->params['code'] ) {
            if ( !self::$session->user ) {
                $app_access_token = file_get_contents( "https://graph.facebook.com/oauth/access_token?client_id=" . Doo::conf()->FACEBOOK['APP_ID'] . "&redirect_uri=" . urlencode( Doo::conf()->FACEBOOK['CANVAS_PAGE'] ) . "&client_secret=" . Doo::conf()->FACEBOOK['APP_SECRET'] . "&code=" . $this->params['code'] );
                $userInfo = file_get_contents( "https://graph.facebook.com/me?" . $app_access_token );

                if( $userInfo )
                    $userInfo = json_decode( $userInfo, true );

                self::$session->user = $userInfo;

                $user = null;

                if( isset( $userInfo['id'] ) && $userInfo['id'] )  {
                    $user = Doo::db()->find('User', array(
                        'where' => 'fb_user=?',
                        'param' => array( $userInfo['id'] ),
                        'limit' => 1
                    ));

                    if( !$user )    {
                        $user = new User( );
                        $user->creation = date( "Y-m-d h:m:s" );
                    }

                    $properties = array( );
                    $mapTransform = array( 'id' => 'fb_user' );
                    $userInfo['gender'] = strtoupper( $userInfo['gender']{0} );

                    foreach( $userInfo as $name => $value )    {
                        if( isset( $mapTransform[$name] ) )
                            $name = $mapTransform[$name];

                        if( property_exists( "User", $name ) )
                            $user->{$name} = $value;
                        else
                            $properties[$name] = $value;
                    };

                    $user->modification = date( "Y-m-d h:m:s" );
                    $user->active = 1;

                    if( !$user->id_user )   {
                        $result = $user->insert( );
                        if( $result )
                            $user->id_user = $result;
                    }
                    else
                        $user->update( );

                    if( $user->id_user )    {
                        foreach( $properties as $name => $value )    {
                            $prop = new UserProperty( );
                            $prop->id_user = $user->id_user;
                            $prop->name = $name;
                            $prop->delete( );
                            $prop->value = $value;
                            $prop->insert( );
                        }

                        self::$session->user = $user;
                   }
                }
            }
        }

        if ( self::$session->user ) {
            $data['active'] = true;
            $data['user'] = array(
                'fname' => self::$session->user->first_name,
                'lname' => self::$session->user->last_name,
                'timezone' => self::$session->user->timezone
            );
            $data['action'] = 'home';
        }

        $this->renderc( $data );
    }
}