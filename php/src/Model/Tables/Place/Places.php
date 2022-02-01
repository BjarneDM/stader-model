<?php namespace Stader\Model\Tables\Place ;

use \Stader\Model\Abstract\ObjectsDao ;

class Places extends ObjectsDao
{
    public static $allowedKeys = 
        [ 'place_nr'       => 'varchar' , 
          'description'    => 'text'    , 
          'place_owner_id' => 'int'     , 
          'area_id'        => 'int'     , 
          'active'         => 'bool'
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\Place\\Place' ;

    function __construct ( ...$args )
    {   // echo 'class Places extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
