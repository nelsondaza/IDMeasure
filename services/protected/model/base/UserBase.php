<?php
Doo::loadCore('db/DooModel');

class UserBase extends DooModel{

    /**
     * @var bigint Max length is 11.  unsigned.
     */
    public $id_user;

    /**
     * @var bigint Max length is 11.  unsigned.
     */
    public $fb_user;

    /**
     * @var varchar Max length is 100.
     */
    public $name;

    /**
     * @var varchar Max length is 50.
     */
    public $first_name;

    /**
     * @var varchar Max length is 50.
     */
    public $last_name;

    /**
     * @var varchar Max length is 100.
     */
    public $link;

    /**
     * @var varchar Max length is 20.
     */
    public $username;

    /**
     * @var varchar Max length is 1.
     */
    public $gender;

    /**
     * @var tinyint Max length is 2.
     */
    public $timezone;

    /**
     * @var varchar Max length is 5.
     */
    public $locale;

    /**
     * @var tinyint Max length is 1.  unsigned.
     */
    public $active;

    /**
     * @var timestamp
     */
    public $modification;

    /**
     * @var timestamp
     */
    public $creation;

    public $_table = 'user';
    public $_primarykey = 'id_user';
    public $_fields = array('id_user','fb_user','name','first_name','last_name','link','username','gender','timezone','locale','active','modification','creation');

    public function getVRules() {
        return array(
                'id_user' => array(
                        array( 'integer' ),
                        array( 'min', 0 ),
                        array( 'maxlength', 11 ),
                        array( 'optional' ),
                ),

                'fb_user' => array(
                        array( 'integer' ),
                        array( 'min', 0 ),
                        array( 'maxlength', 11 ),
                        array( 'notnull' ),
                ),

                'name' => array(
                        array( 'maxlength', 100 ),
                        array( 'notnull' ),
                ),

                'first_name' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'last_name' => array(
                        array( 'maxlength', 50 ),
                        array( 'notnull' ),
                ),

                'link' => array(
                        array( 'maxlength', 100 ),
                        array( 'optional' ),
                ),

                'username' => array(
                        array( 'maxlength', 20 ),
                        array( 'optional' ),
                ),

                'gender' => array(
                        array( 'maxlength', 1 ),
                        array( 'notnull' ),
                ),

                'timezone' => array(
                        array( 'integer' ),
                        array( 'maxlength', 2 ),
                        array( 'notnull' ),
                ),

                'locale' => array(
                        array( 'maxlength', 5 ),
                        array( 'optional' ),
                ),

                'active' => array(
                        array( 'integer' ),
                        array( 'min', 0 ),
                        array( 'maxlength', 1 ),
                        array( 'notnull' ),
                ),

                'modification' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                ),

                'creation' => array(
                        array( 'datetime' ),
                        array( 'notnull' ),
                )
            );
    }

}