<?php namespace stader\model ;

class UsersBeredskabs extends ObjectsDaoTest
{
    public static $allowedKeys = 
        [ 'user_id'      => 'int' ,
          'beredskab_id' => 'int'  
        ] ;
    protected     $class       = '\\stader\\model\\UserBeredskab' ;

    function __construct ( ...$args )
    {   // echo 'class UsersBeredskabs extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
