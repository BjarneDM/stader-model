<?php namespace Stader\Model\Tables\UserBeredskab ;

use \Stader\Model\Abstract\ObjectsDao ;

class UsersBeredskabs extends ObjectsDao
{
    public static $allowedKeys = 
        [ 'user_id'      => 'int' ,
          'beredskab_id' => 'int'  
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\UserBeredskab\\UserBeredskab' ;

    function __construct ( ...$args )
    {   // echo 'class UsersBeredskabs extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
