<?php namespace stader\control ;

/*

 */


class TicketLog
{
//     private $allowedKeys = [ 'full_place' , 'place_id' , 'description' , 'place_owner_id' , 'lastchecked' ] ;
    protected $values = [] ;

    function __construct ( $args )
    {   // echo 'class Place extends PlaceDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        $values = $args->getData() ;
        try {
            $tGroup = new \Stader\Model\TicketGroup( $values['ticket_id'] ) ;
            $this->values = array_merge( $values , [ 'group_id' => $tGroup->getData()['group_id'] ] ) ;
        } catch ( \Exception $e ) {}
        $this->check( $this->values ) ;

    }

    private function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            switch ( $key )
            {
                case 'assigned_place_id' :
                    $place    = new \Stader\Model\Place( $toCheck[ $key ] ) ;
                    $area     = new \Stader\Model\Area( $place->getData()['area_id'] ) ;
                    $toCheck[ $key ]  = $area->getData()['name'] . $place->getData()['place_nr'] ;

                    break ;
                case 'ticket_status_id' :
                    $status   = new \Stader\Model\TicketStatus( $toCheck[ $key ] ) ;
                    $toCheck[ $key ]  = $status->getData()['name'] ;

                    break ;
                case 'assigned_user_id' :
                    try {
                        $user     = new \Stader\Model\User( $toCheck[ $key ] ) ;
                        $toCheck[ $key ]  = $user->getData()['username'] ;
                    } catch ( \Exception $e) {
                        $toCheck[ $key ]  = '---' ;
                    }

                    break ;
                case 'group_id' :
                    $group    = new \Stader\Model\Group( $toCheck[ $key ] ) ;
                    $toCheck[ $key ]  = $group->getData()['name'] ;

                    break ;
                case 'area_id'  :
                case 'place_nr' :
                    unset( $toCheck[ $key ] ) ;

                    break ;
            }
        }
    }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

}

?>
