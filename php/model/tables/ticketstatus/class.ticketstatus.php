<?php namespace stader\model ;

/*

create table if not exists ticket_status
(
    ticket_status_id    int auto_increment primary key,
    name                varchar(255) ,
        constraint unique (name) ,
    default_colour      varchar(255) ,
    description         text ,
    type_byte_id        int ,
        foreign key (type_byte_id) references type_byte(type_byte_id) ,
        on update cascade
        on delete restrict
)

 */

require_once( __dir__ . '/class.ticketstatusdao.php' ) ;

class TicketStatus extends TicketStatusDao
{
    public static $allowedKeys = [ 'name' , 'default_colour' , 'description' , 'type_byte_id' ] ;

    public static $allowedColours =
    [
        'black'     , 'darkblue'  , 'darkgreen' , 'green'     , 'grey'      ,
        'lightblue' , 'lightgreen', 'lightgrey' , 'orange'    , 'purple'    ,
        'red'       , 'white'     , 'yellow'    
    ] ;

    public static $defaultColours = 
    [ 'da_DK' =>
        [
            'black'     => 'sort' ,
            'darkblue'  => 'mørkeblå' ,
            'darkgreen' => 'mørkegrøn' ,
            'green'     => 'grøn' ,
            'grey'      => 'grå' ,
            'lightblue' => 'lyseblå' ,
            'lightgreen'=> 'lysegrøn' ,
            'lightgrey' => 'lysegrå' ,
            'orange'    => 'orange' ,
            'purple'    => 'purpur' ,
            'red'       => 'rød' ,
            'white'     => 'hvid' ,
            'yellow'    => 'gul'
        ]
    ] ;


    function __construct ( ...$args )
    {   // echo 'class TicketStatus extends TicketStatusDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct() ;

        /*
         *  gettype( $args[0] ) === 'integer' 
         *      henbt en TicketStatus på basis af et ticket_status_id
         *      $testTicketStatus = new TicketStatus( ticket_status_id ) ;
         *  gettype( $args[0] ) === 'array'
         *      opret en ticket_status på basis af værdierne i $args[0]
         *      $testTicketStatus = new TicketStatus( $newTicketStatus )
         *  gettype( $args[0] ) === ['string','array'] , gettype( $args[1] ) === ['string','array']
         *      hent en ticket_status på basis af værdierne i $args[0],$args[1]
         *      $testTicketStatus = new TicketStatus( $keys , $values )
         */
        switch ( count( $args ) )
        {
            case 1 :
                switch ( strtolower( gettype( $args[0] ) ) )
                {
                    case 'integer' :
                        $this->read( $args[0] ) ;
                        break ;
                    case 'array' :
                        /*
                         *  count( $args[0] ) === 4 : ny ticket_status, der skal oprettes
                         */
                        switch ( count( $args[0] ) )
                        {
                            case 4 :
                                $this->check( $args[0] ) ;
                                $this->values['ticket_status_id'] = $this->create( $args[0] ) ;
                                break ;
                            default :
                                throw new \Exception( count( $args[0] ) . " : forkert antal parametre [4]" ) ;
                                break ;
                        }

                       foreach ( $args[0] as $key => $value ) 
                        { 
                            $this->values[$key] = $value ;
                        }   unset( $key , $value ) ;

                        break ;
                    default :
                        throw new \Exception( gettype( $args[0] ) . " : forkert input type [integer,array]" ) ;
                        break ;
                }
                break ;
            case 2 :
                switch ( strtolower( gettype( $args[0] ) ) )
                {
                    case 'string' :
                        if ( strtolower( gettype( $args[1] ) ) !== 'string' )
                            throw new \Exception( gettype( $args[0] ) . ' & ' . gettype( $args[1] ) . ' er ikke begge "string"' ) ;
                         if ( ! in_array( $args[0] , self::$allowedKeys ) )
                            throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , self::$allowedKeys ) . " ]" ) ;
                        break ;
                    case 'array' :
                        if ( count( $args[0] ) !== count( $args[1] ) )
                            throw new \Exception( 'count() for $args[0] & $args[1] er forskellige' ) ;
                        foreach ( $args[0] as $key )
                        {
                            if ( ! in_array( $key , self::$allowedKeys ) )
                                throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , self::$allowedKeys ) . " ]" ) ;
                        }
                        break ;
                    default :
                        throw new \Exception( gettype( $args[0] ) . " : forkert input type [string,array]" ) ;
                        break ;
                }
                $this->read( $args[0] , $args[1] ) ;
                break ;
            default :
                throw new \Exception( count( $args ) . " : forkert antal parametre [1,2]" ) ;
                break ;
        }
    }

    private function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! in_array( $key , self::$allowedKeys ) )
                throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , self::$allowedKeys ) . " ]" ) ;

            switch ( $key )
            {
                case 'default_colour' :
                    if ( ! in_array( $toCheck[ $key ] , self::$allowedColours ) )
                        throw new \Exception( "'{$toCheck[ $key ]}' doesn't exist in [ " . implode( ' , ' , self::$allowedColours ) . " ]" ) ;
                    
                    break ;
            }

        }   unset( $key ) ;
    }

    public function setValues( $values )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $values ) ;

        switch ( strtolower( gettype( $values ) ) )
        {
            case 'array' :
                $this->check( $values ) ;
                foreach ( $values as $key => $value )
                {
                    $this->values[ $key ] = $value ;
                    $this->update( $key , $value ) ;
                }
                break ;
            default :
                throw new \Exception( gettype( $values ) . " : forkert input type [array]" ) ;
                break ;
        }
    }

    public static function translateColour( $language , $colour )
    {
        return self::$defaultColours[ $language ][ $colour ] ;
    }

}

?>
