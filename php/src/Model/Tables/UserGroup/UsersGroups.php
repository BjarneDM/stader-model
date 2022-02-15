<?php namespace Stader\Model\Tables\UserGroup ;

use \Stader\Model\Abstract\DataObjectsDao ;

class UsersGroups extends DataObjectsDao
{
    public static $allowedKeys = 
        [ 'user_id'  => 'int' , 
          'group_id' => 'int'  
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\UserGroup\\UserGroup' ;

    function __construct ( ...$args )
    {   // echo 'class UsersGroups extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
