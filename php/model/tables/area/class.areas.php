<?php namespace stader\model ;

class Areas extends ObjectsDao
{
    public static $allowedKeys = [ 'name' => 'varchar' , 'description' => 'text' ] ;
    protected     $class       = '\\stader\\model\\Area' ;

    public function __construct ( ...$args )
    {   // echo 'class Area extends AreaDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys , $args ) ;

    }

}

?>
