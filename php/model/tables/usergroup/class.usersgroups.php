<?php namespace stader\model ;

class UsersGroups extends ObjectsDao
{
    public static $allowedKeys = 
        [ 'user_id'  => 'int' , 
          'group_id' => 'int'  
        ] ;
    protected     $class       = '\\stader\\model\\UserGroup' ;

    function __construct ( ...$args )
    {   // echo 'class UsersGroups extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
