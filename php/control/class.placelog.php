<?php namespace stader\model ;

/*

create table if not exists placelog_log
(
    place_log_id    int auto_increment primary key ,
    header          varchar(255) ,
    full_place      varchar(8) ,
    log_timestamp   datetime
        default current_timestamp ,
    data            text
) ;

 */

class PlaceLog
{
//     private $allowedKeys = [ 'header' , 'data' ] ;

    function __construct ( $args )
    {   // echo 'class PlaceLog __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        $values = $args->getData() ;
        $data   = json_decode( $values['data'] , true ) ;
        unset( $values['data'] ) ;
        $this->values = array_merge( $values , $data ) ;

    }

    private function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {

            switch ( $key )
            {
                case 'header' :

                    break ;
                case 'data' :

                    break ;
            }
        }
    }

}

?>
