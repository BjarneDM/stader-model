<?php namespace Stader\Model\Tables\User ;

use \Stader\Model\Abstract\DataObjectsDao ;

class Users extends DataObjectsDao
{
    public static $allowedKeys = 
        [ 'name'     => 'varchar' , 
          'surname'  => 'varchar' , 
          'phone'    => 'varchar' , 
          'username' => 'varchar' , 
          'passwd'   => 'varchar' , 
          'email'    => 'varchar' 
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\User\\User' ;

    function __construct ( ...$args )
    {   // echo 'class Users extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }
}

?>
