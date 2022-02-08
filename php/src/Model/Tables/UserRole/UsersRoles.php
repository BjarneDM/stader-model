<?php namespace Stader\Model\Tables\UserRole ;

use \Stader\Model\Abstract\DataObjectsDao ;

class UsersRoles extends DataObjectsDao
{
    public static $allowedKeys = 
        [ 'user_id' => 'int' , 
          'role_id' => 'int' 
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\UserRole\\UserRole' ;

    function __construct ( ...$args )
    {   // echo 'class UsersRoles extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
