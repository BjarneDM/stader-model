<?php namespace Stader\Model\Tables\TicketStatus ;

use \Stader\Model\Abstract\DataObjectsDao ;

class TicketStatuses extends DataObjectsDao
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

        parent::__construct( self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
