<?php namespace Stader\Control\Objects\AreaPlace ;

use \Stader\Model\Tables\Area\{Area,Areas} ;
use \Stader\Model\Tables\Place\{Place,Places} ;
use \Stader\Model\Tables\PlaceOwner\{PlaceOwner,PlaceOwners} ;
use \Stader\Model\OurDateTime ;
use \Stader\Control\Abstract\DataObjectDao ;


class AreaPlace extends DataObjectDao
{
    public static $allowedKeys = 
        [ 'areaplace' => 'varchar' ] ;
    protected   $class  = '\\Stader\\Control\\Object\\AreaPlace\\AreaPlace' ;

    protected Area       $area  ;
    protected Place      $place ;
    protected PlaceOwner $owner ;
    protected string     $areaName ;
    protected int        $placeNr ;

    function __construct ( ...$args )
    {   // echo 'class User extends DataObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

    protected function setupData ( $args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;
        /*
         *  gettype( $args[0] ) === 'string' 
         *      hent et Object på basis af et id
         *      $testObject = new Object( id ) ;
         */
        switch ( count( $args ) )
        {
            case 1 :
                switch ( strtolower( gettype( $args[0] ) ) )
                {
                    case 'string' :
                        if ( strlen( $args[0] ) !== 2 ) 
                            throw new \Exception( strlen( $args[0] ) . " : forkert strlen [2]" ) ;
                        list( $this->areaName , $this->placeNr ) = preg_split( '//', $args[0] , 2 , PREG_SPLIT_NO_EMPTY ) ;
                        $this->placeNr = (int) $this->placeNr ;
                        break;
                    default :
                        throw new \Exception( gettype( $args[0] ) . " : forkert input type [string]" ) ;
                        break ;
                }
                $this->values = $this->read() ;
                break ;
            default :
                throw new \Exception( count( $args ) . " : forkert antal parametre [1]" ) ;
                break ;
        }
        $this->valuesOld = ( new \ArrayObject( $this->values ) )->getArrayCopy() ;
    }

    protected function read () : Array
    {
        $this->area  = new Area( 'name' , $this->areaName ) ;
        $this->place = new Place( 
            [ 'area_id'                    , 'place_nr'     ] , 
            [ $this->area->getData()['id'] , $this->placeNr ] 
        ) ;
        $this->owner = new PlaceOwner( $this->place->getData()['place_owner_id'] ) ;

        $values['area']  = $this->area->getData() ;
        $values['place'] = $this->place->getData() ;
        $values['owner'] = $this->owner->getData() ;
        $values['placename']  = $this->area->getData()['name'] . $this->place->getData()['place_nr'] ;

    return $values ; }
 
    protected function create () : int  { return 0 ; }
    protected function update ( Array  $values ) : void {}

}

?>
