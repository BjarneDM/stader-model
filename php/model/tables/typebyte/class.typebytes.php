<?php namespace stader\model ;

class TypeBytes extends ObjectsDao
{
    public static $allowedKeys = [ 'name' => 'varchar' ] ;
    protected     $class       = '\\stader\\model\\TypeByte' ;

    function __construct ( ...$args )
    {   // echo 'class TypeBytes extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys , $args ) ;

        $this->setupData( $args ) ;

    }

}

?>
