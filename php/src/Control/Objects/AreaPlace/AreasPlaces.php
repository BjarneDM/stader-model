<?php namespace Stader\Control\Objects\AreaPlace ;

/*

create view areaplace as
select concat( a.name , p.place_nr ) as placename
from area as a , place as p 
where a.id = p.area_id
;

 */


use \Stader\Model\Tables\Area\{Area,Areas} ;
use \Stader\Model\Tables\Place\{Place,Places} ;
use \Stader\Model\Tables\PlaceOwner\{PlaceOwner,PlaceOwners} ;
use \Stader\Model\DateTimeString ;
use \Stader\Control\Abstract\DataObjectsDao ;
use \Stader\Control\Objects\AreaPlace\AreaPlace ;
use \Stader\Control\Traits\DataObjectsConstruct ;

class AreasPlaces extends DataObjectsDao
{
    public static $baseClass  = '\\Stader\\Control\\Objects\\AreaPlace\\AreaPlace' ;
    public static $thisClass  = '\\Stader\\Control\\Objects\\AreaPlace\\AreasPlaces' ;
    public static $allowedKeys ;

    private Area   $area   ;
    private Areas  $areas  ;
    private Place  $place  ;
    private Places $places ;


    use DataObjectsConstruct ;

    protected function setupData ( $thisClass , $args )
    {
        self::$allowedKeys = Area::$allowedKeys ;
        parent::setupData( $thisClass , $args ) ;
        $this->areas = new Areas( $this->getKeys(), $this->getValues() ) ;
    }

    public function setOrderBy ( array $columns ) : void
    {
        $this->areas->setOrderBy( $columns ) ;
    }

    public function getOrderBy () : ?string
    {
        return $this->orderBy ;
    }

// https://www.php.net/manual/en/class.iterator.php

    public function rewind() : void 
    {   //  echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $this->areas->rewind() ;
        if ( $this->areas->valid() )
        {
            $this->places = new Places( 'area_id' , $this->areas->key() ) ;
            $this->places->rewind() ;
        }
        $this->position = 0 ;
    }

    public function count() : int 
    {   //  echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        return $this->areas->count() ;
    }

    public function next() : void 
    {   //  echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $this->places->next() ;
        if ( ! $this->places->valid() )
        {
            do {
                $this->areas->next() ;
                if ( $this->areas->valid() )
                {
                    $this->places = new Places( 'area_id' , $this->areas->key() ) ;
                    $this->places->rewind() ;
                }
            } while ( $this->places->count() == 0 ) ;
        }
        ++$this->position ;
    }

    public function valid() : bool 
    {   //  echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        return ( $this->areas->valid() && $this->places->valid() ) ;
    }

    public function current() : Object 
    {   //  echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        return new AreaPlace(
            $this->areas->current()->getData()['name'] .
            $this->places->current()->getData()['place_nr']
        ) ;
    }

    public function key() : string | false 
    {   //  echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        return (
            $this->areas->current()->getData()['name'] .
            $this->places->current()->getData()['place_nr']
        ) ;
    }

}

?>
