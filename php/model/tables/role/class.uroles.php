<?php namespace stader\model ;

class URoles extends ObjectsDaoTest
{
    public static $allowedKeys = 
        [ 'role'     => 'varchar' , 
          'note'     => 'text'    , 
          'priority' => 'int'
        ] ;
    protected     $class       = '\\stader\\model\\URole' ;

    function __construct ( ...$args )
    {   // echo 'class URoles extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
