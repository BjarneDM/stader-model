<?php namespace stader\model ;

class TicketStatuses extends ObjectsDaoTest
{
    public static $allowedKeys = 
        [ 'name'           => 'varchar' , 
          'default_colour' => 'varchar' , 
          'description'    => 'text' , 
          'type_byte_id'   => 'int' 
        ] ;
    protected     $class       = '\\stader\\model\\TicketStatus' ;

    function __construct ( ...$args )
    {   // echo 'class TicketStatuses extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
