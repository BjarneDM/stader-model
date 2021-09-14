<?php namespace stader\model ;

class TypeBytes extends ObjectsDaoTest
{
    public static $allowedKeys = [ 'name' => 'varchar' ] ;
    protected     $class       = '\\stader\\model\\TypeByte' ;

    function __construct ( ...$args )
    {   // echo 'class TypeBytes extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
