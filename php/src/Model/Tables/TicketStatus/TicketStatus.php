<?php namespace Stader\Model\Tables\TicketStatus ;

use \Stader\Model\Abstract\ObjectDao ;

/*

create table if not exists ticket_status
(
    ticket_status_id    int auto_increment primary key,
    name                varchar(255) ,
        constraint unique (name) ,
    default_colour      varchar(255) ,
    description         text ,
    type_byte_id        int ,
        foreign key (type_byte_id) references type_byte(id) ,
        on update cascade
        on delete restrict
)

 */

class TicketStatus extends ObjectDao
{
    public static $allowedKeys = 
        [ 'name'           => 'varchar' , 
          'default_colour' => 'varchar' , 
          'description'    => 'text' , 
          'type_byte_id'   => 'int' 
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\TicketStatus\\TicketStatus' ;

    public static $allowedColours =
        [
            'black'     , 'darkblue'  , 'darkgreen' , 'green'   , 'grey'    ,
            'lightblue' , 'lightgreen', 'lightgrey' , 'orange'  , 'purple'  ,
            'red'       , 'white'     , 'yellow'    
        ] ;

    public static $defaultColours = 
        [ 'da_DK' =>
            [
                'black'     => 'sort'      ,
                'darkblue'  => 'mørkeblå'  ,
                'darkgreen' => 'mørkegrøn' ,
                'green'     => 'grøn'      ,
                'grey'      => 'grå'       ,
                'lightblue' => 'lyseblå'   ,
                'lightgreen'=> 'lysegrøn'  ,
                'lightgrey' => 'lysegrå'   ,
                'orange'    => 'orange'    ,
                'purple'    => 'purpur'    ,
                'red'       => 'rød'       ,
                'white'     => 'hvid'      ,
                'yellow'    => 'gul'
            ]
        ] ;


    function __construct ( ...$args )
    {   // echo 'class TicketStatus extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;
        $this->values['type_byte_id'] = (int) $this->values['type_byte_id'] ;

    }

    protected function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        parent::check( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            switch ( $key )
            {
                case 'default_colour' :
                    if ( ! in_array( $toCheck[ $key ] , self::$allowedColours ) )
                        throw new \Exception( "'{$toCheck[ $key ]}' doesn't exist in [ " . implode( ' , ' , self::$allowedColours ) . " ]" ) ;
                    break ;
            }

        }   unset( $key ) ;
    }

    public static function translateColour( $language , $colour )
    {
        return self::$defaultColours[ $language ][ $colour ] ;
    }

}

?>
