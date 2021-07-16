<?php namespace stader\model ;

/*

drop table if exists place ;
create table if not exists place
(
    place_id        int auto_increment primary key ,
    place_nr        varchar(8) not null ,
        constraint unique (place_nr) ,
    description     text ,
    place_owner_id  int ,
        foreign key (place_owner_id) references place_owner(place_owner_id)
        on update cascade 
        on delete restrict ,
    area_id         int ,
        foreign key (area_id) references areas(area_id)
        on update cascade 
        on delete cascade ,
    lastchecked     datetime
        default   current_timestamp
        on update current_timestamp
) ;

 */

require_once( __dir__ . '/class.placedao.php' ) ;

class Place extends PlaceDao
{
    public static $allowedKeys = [ 'place_nr' , 'description' , 'place_owner_id' , 'area_id' , 'active' ] ;

    function __construct ( ...$args )
    {   // echo 'class Place extends PlaceDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct() ;

        /*
         *  gettype( $args[0] ) === 'integer' 
         *      henbt en Place på basis af et place_id
         *      $testPlace = new Place( place_id ) ;
         *  gettype( $args[0] ) === 'array'
         *      opret en place på basis af værdierne i $args[0]
         *      $testPlace = new Place( $newPlace )
         *  gettype( $args[0] ) === ['string','array'] , gettype( $args[1] ) === ['string','array']
         *      hent en place på basis af værdierne i $args[0],$args[1]
         *      $testPlace = new Place( $keys , $values )
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
                         *  count( $args[0] ) === 4 : ny place, der skal oprettes
                         */
                        switch ( count( $args[0] ) )
                        {
                            case 5 :
                                $this->check( $args[0] ) ;
                                $this->values['place_id'] = $this->create( $args[0] ) ;
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

//             switch ( $key )
//             {
//                 case 'place_owner_id' :
//                     if ( in_array( $toCheck[ $key ] , [ '' , null ] ) )
//                         $toCheck[ $key ] = ( new PlaceOwner( 'name' , 'dummy' ) )->getData()[ $key ] ;
//                 break ;
//             }
        }
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

}

?>
