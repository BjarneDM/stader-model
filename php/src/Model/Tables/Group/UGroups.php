<?php namespace Stader\Model\Tables\Group ;

use \Stader\Model\Abstract\ObjectsDao ;

class UGroups extends ObjectsDao
{
    public static $allowedKeys = 
        [ 'name'        => 'varchar' , 
          'description' => 'varchar'
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\Group\\UGroup' ;

    function __construct ( ...$args )
    {   // echo 'class UGroups extends ObjectsDao __construct' . \PHP_EOL ;
        // var_dump( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
