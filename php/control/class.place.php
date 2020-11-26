<?php namespace stader\control ;

/*

create table if not exists areas
(
    area_id     int auto_increment primary key ,
    name        varchar(255) not null ,
        constraint unique (name) ,
    description text
) ;

create table if not exists place
(
    place_id        int auto_increment primary key ,
    place_nr        varchar(8) not null ,
    description     text ,
    place_owner_id  int ,
    area_id         int ,
    lastchecked     datetime
) ;

 */

class Place
{
    private $allowedKeys = [ 'full_place' , 'place_id' , 'description' , 'place_owner_id' , 'lastchecked' ] ;
    protected $values = [] ;

    function __construct ( $args )
    {   // echo 'class Place extends PlaceDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        $this->values['full_place'] = $args['name'] . $args['place_nr'] ;
        $this->check( $args ) ;
        foreach ( $args as $key => $value )
        {
            $this->values[ $key ] = $value ;
        }

    }

    private function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! in_array( $key , $this->allowedKeys ) )
                unset( $toCheck[ $key ] ) ;
        }
    }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

}

?>
