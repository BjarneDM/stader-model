<?php namespace Stader\Model\Tables\Area ;

use \Stader\Model\Abstract\ObjectsDao ;

class Areas extends ObjectsDao
{
    public static $allowedKeys = 
        [ 'name'        => 'varchar' , 
          'description' => 'text' 
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\Area\\Area' ;

    public function __construct ( ...$args )
    {   // echo 'class Area extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
