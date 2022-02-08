<?php namespace Stader\Model\Tables\Beredskab ;

use \Stader\Model\Abstract\DataObjectsDao ;

class Beredskabs extends DataObjectsDao
{
    public static $allowedKeys = 
        [ 'message'       => 'text'    , 
          'header'        => 'text'    , 
          'created_by_id' => 'int'     , 
          'flag'          => 'varchar' , 
          'colour'        => 'varchar' , 
          'active'        => 'bool' 
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\Beredskab\\Beredskab' ;

    function __construct ( ...$args )
    {   // echo 'class Beredskabs extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
