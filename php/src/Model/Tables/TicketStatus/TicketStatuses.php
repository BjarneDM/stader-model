<?php namespace Stader\Model\Tables\TicketStatus ;

use \Stader\Model\Abstract\ObjectsDao ;

class TicketStatuses extends ObjectsDao
{
    public static $allowedKeys = 
        [ 'name'           => 'varchar' , 
          'default_colour' => 'varchar' , 
          'description'    => 'text' , 
          'type_byte_id'   => 'int' 
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\TicketStatus\\TicketStatus' ;

    function __construct ( ...$args )
    {   // echo 'class TicketStatuses extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
