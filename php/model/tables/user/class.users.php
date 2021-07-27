<?php namespace stader\model ;

class Users extends ObjectsDao
{
    public static $allowedKeys = 
        [ 'name'     => 'varchar' , 
          'surname'  => 'varchar' , 
          'phone'    => 'varchar' , 
          'username' => 'varchar' , 
          'passwd'   => 'varchar' , 
          'email'    => 'varchar' 
        ] ;
    protected     $class       = '\\stader\\model\\User' ;

    function __construct ( ...$args )
    {   // echo 'class Users extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys , $args ) ;

        $this->setupData( $args ) ;

    }
}

?>
