<?php namespace Stader\Model\Tables\Flag ;

use \Stader\Model\Abstract\DataObjectsDao ;

class Flags extends DataObjectsDao
{
    public static $allowedKeys = 
        [ 'text'    => 'varchar' , 
          'unicode' => 'char'
        ] ;
    protected     $class       = '\\Stader\\Model\\Tables\\Flag\\Flag' ;

    function __construct ( ...$args )
    {   // echo 'class Flags extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
