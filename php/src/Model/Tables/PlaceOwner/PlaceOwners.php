<?php namespace Stader\Model\Tables\PlaceOwner ;

use \Stader\Model\Abstract\ObjectsDao ;

class PlaceOwners extends ObjectsDao
{
    public static $allowedKeys = 
        [ 'name'         => 'varchar' , 
          'surname'      => 'varchar' , 
          'phone'        => 'varchar' , 
          'email'        => 'varchar' , 
          'organisation' => 'varchar' 
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\PlaceOwner\\PlaceOwner' ;

    function __construct ( ...$args )
    {   // echo 'class PlaceOwners extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
