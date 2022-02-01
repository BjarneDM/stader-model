<?php namespace Stader\Model ;

class PlaceLogs extends ObjectsDao
{
    private $allowedKeys = 
        [ 'place_id'  => 'int'     , 
          'header'    => 'varchar' , 
          'old_value' => 'text'    , 
          'new_value' => 'text'
        ] ;
    protected     $class       = '\\stader\\model\\PlaceLog' ;

    function __construct ( ...$args )
    {   // echo 'class PlaceLogs extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'logs' , self::$allowedKeys , $args ) ;

        $this->setupData( $args ) ;

    }

}

?>
