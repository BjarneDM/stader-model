<?php namespace stader\model ;

class TicketsGroups extends ObjectsDao
{
    public static $allowedKeys = 
        [ 'ticket_id' => 'int' , 
          'group_id'  => 'int' 
        ] ;
    protected     $class       = '\\stader\\model\\TicketGroup' ;

    function __construct ( ...$args )
    {   // echo 'class TicketGroups extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
