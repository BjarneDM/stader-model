<?php namespace stader\model ;

class Areas extends ObjectsDaoTest
{
    public static $allowedKeys = 
        [ 'name'        => 'varchar' , 
          'description' => 'text' 
        ] ;
    protected     $class       = '\\stader\\model\\Area' ;

    public function __construct ( ...$args )
    {   // echo 'class Area extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
