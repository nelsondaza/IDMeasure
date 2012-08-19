<?php
Doo::loadCore('db/DooModel');

class UserPropertyBase extends DooModel{

    /**
     * @var bigint Max length is 11.  unsigned.
     */
    public $id_user_property;

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

    public $_table = 'user_property';
    public $_primarykey = 'id_user_property';
    public $_fields = array('id_user_property','id_user','name','value');

    public function getVRules() {
        return array(
                'id_user_property' => array(
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