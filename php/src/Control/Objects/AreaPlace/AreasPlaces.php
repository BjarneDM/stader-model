<?php namespace Stader\Control\Objects\AreaPlace ;

use \Stader\Model\Tables\Area\{Area,Areas} ;
use \Stader\Model\Tables\Place\{Place,Places} ;
use \Stader\Model\Tables\PlaceOwner\{PlaceOwner,PlaceOwners} ;
use \Stader\Model\OurDateTime ;
use \Stader\Control\Abstract\DataObjectsDao ;
use \Stader\Control\Objects\AreaPlace\AreaPlace ;
use \Stader\Control\Traits\DataObjectsConstruct ;

class AreasPlaces extends DataObjectsDao
{
    public static $baseClass  = '\\Stader\\Control\\Objects\\AreaPlace\\AreaPlace' ;

    private Area   $area   ;
    private Areas  $areas  ;
    private Place  $place  ;
    private Places $places ;

    use DataObjectsConstruct ;

    protected function setupData ( $thisClass , $args )
    {
        parent::setupData( $thisClass , $args ) ;
        $this->areas = new Areas() ;
        self::$allowedKeys = [ 'area' => 'varchar' ] ;
    }

// https://www.php.net/manual/en/class.iterator.php

    public function rewind() : void 
    {   echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $this->areas->rewind() ;
        if ( $this->areas->valid() )
        {
            $this->places = new Places( 'area_id' , $this->areas->key() ) ;
            $this->places->rewind() ;
        }
        $this->position = 0 ;
    }

    public function count() : int 
    {   echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        return $this->areas->count() ;
    }

    public function next() : void 
    {   echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
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
    {   echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        return ( $this->areas->valid() && $this->places->valid() ) ;
    }

    public function current() : Object 
    {   echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        return new AreaPlace(
            $this->areas->current()->getData()['name'] .
            $this->places->current()->getData()['place_nr']
        ) ;
    }

    public function key() : int | false 
    {   echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        return $this->usersLogin->key() ;
    }

}

?>
