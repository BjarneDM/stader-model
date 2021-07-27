<?php namespace stader\model ;

class UsersRoles extends ObjectsDao
{
    public static $allowedKeys = 
        [ 'user_id' => 'int' , 
          'role_id' => 'int' 
        ] ;
    protected     $class       = '\\stader\\model\\UserRole' ;

    function __construct ( ...$args )
    {   // echo 'class UsersRoles extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys , $args ) ;

        $this->setupData( $args ) ;

    }

}

?>
