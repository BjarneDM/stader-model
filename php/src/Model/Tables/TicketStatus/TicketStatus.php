<?php namespace Stader\Model\Tables\TicketStatus ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\Traits\DataObjectConstruct ;

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

class TicketStatus extends DataObjectDao
{
    public static $dbType      = 'data' ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\TicketStatus\\TicketStatus' ;
    public static $allowedKeys = 
        [ 'name'           => 'varchar' , 
          'default_colour' => 'varchar' , 
          'description'    => 'text' , 
          'type_byte_id'   => 'int' 
        ] ;

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

    use DataObjectConstruct ;

    protected function fixValuesType () : void
    {
        $this->values['type_byte_id'] = (int) $this->values['type_byte_id'] ;
    }

    protected function check( $thisClass , Array &$toCheck ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        parent::check( $thisClass , $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            switch ( $key )
            {
                case 'default_colour' :
                    if ( ! in_array( $toCheck[ $key ] , $thisClass::$allowedColours ) )
                        throw new \Exception( "'{$toCheck[ $key ]}' doesn't exist in [ " . implode( ' , ' , $thisClass::$allowedColours ) . " ]" ) ;
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
