<?php namespace stader\model ;

class Flags extends ObjectsDao
{
    public static $allowedKeys = 
        [ 'text'    => 'varchar' , 
          'unicode' => 'char'
        ] ;
    protected     $class       = '\\stader\\model\\Flag' ;

    function __construct ( ...$args )
    {   // echo 'class Flags extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys , $args ) ;

        $this->setupData( $args ) ;

    }

}

?>
