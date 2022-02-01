<?php namespace Stader\Model\Tables\Ticket ;

use \Stader\Model\Abstract\ObjectsDao ;

class Tickets extends ObjectsDao
{
    public static $allowedKeys = 
        [ 'header'            => 'varchar' , 
          'description'       => 'text'    , 
          'assigned_place_id' => 'int'     , 
          'ticket_status_id'  => 'int'     , 
          'assigned_user_id'  => 'int'     , 
          'active'            => 'bool'
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\Ticket\\Ticket' ;

    function __construct ( ...$args )
    {   // echo 'class Tickets extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
