<?php
Doo::loadCore('db/DooModel');

class UserPropertiesBase extends DooModel{

    /**
     * @var bigint Max length is 11.  unsigned.
     */
    public $id_user_properties;

    /**
     * @var bigint Max length is 11.  unsigned.
     */
    public $id_user;

    /**
     * @var varchar Max length is 20.
     */
    public $name;

    /**
     * @var varchar Max length is 200.
     */
    public $value;

    public $_table = 'user_properties';
    public $_primarykey = 'id_user_properties';
    public $_fields = array('id_user_properties','id_user','name','value');

    public function getVRules() {
        return array(
                'id_user_properties' => array(
                        array( 'integer' ),
                        array( 'min', 0 ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'id_user' => array(
                        array( 'integer' ),
                        array( 'min', 0 ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'name' => array(
                        array( 'maxlength', 20 ),
                        array( 'notnull' ),
                ),

                'value' => array(
                        array( 'maxlength', 200 ),
                        array( 'notnull' ),
                )
            );
    }

}