<?php namespace Stader\Model\Tables\Role ;

use \Stader\Model\Abstract\DataObjectsDao ;

class URoles extends DataObjectsDao
{
    public static $allowedKeys = 
        [ 'role'     => 'varchar' , 
          'note'     => 'text'    , 
          'priority' => 'int'
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\Role\\URole' ;

    function __construct ( ...$args )
    {   // echo 'class URoles extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
