<?php namespace stader\model ;

class Beredskabs extends ObjectsDaoTest
{
    public static $allowedKeys = 
        [ 'message'       => 'text'    , 
          'header'        => 'text'    , 
          'created_by_id' => 'int'     , 
          'flag'          => 'varchar' , 
          'colour'        => 'varchar' , 
          'active'        => 'bool' 
        ] ;
    protected     $class       = '\\stader\\model\\Beredskab' ;

    function __construct ( ...$args )
    {   // echo 'class Beredskabs extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
