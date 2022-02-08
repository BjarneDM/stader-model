<?php namespace Stader\Model\Tables\TypeByte ;

use \Stader\Model\Abstract\DataObjectsDao ;

class TypeBytes extends DataObjectsDao
{
    public static $allowedKeys = [ 'name' => 'varchar' ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\TypeByte\\TypeByte' ;

    function __construct ( ...$args )
    {   // echo 'class TypeBytes extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
