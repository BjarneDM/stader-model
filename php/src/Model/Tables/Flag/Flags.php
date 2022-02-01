<?php namespace Stader\Model\Tables\Flag ;

use \Stader\Model\Abstract\ObjectsDao ;

class Flags extends ObjectsDao
{
    public static $allowedKeys = 
        [ 'text'    => 'varchar' , 
          'unicode' => 'char'
        ] ;
    protected     $class       = '\\Stader\\Model\\Tables\\Flag\\Flag' ;

    function __construct ( ...$args )
    {   // echo 'class Flags extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
