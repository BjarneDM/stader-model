<?php namespace Stader\Model ;

/*
 *  programmer / createExel har brug for en __toString() t/ \DateTime
 *  men en sådan eksisterer ikke som standard,
 *  så derfor denne class
 */

class DateTimeString extends \DateTime
{

    private string $displayFormat  = 'mysql' ;
    public  static $displayFormats =
    [
        'mysql'
    ] ;

    public function __construct( string $datetime = "now", ?\DateTimeZone $timezone = null)
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        parent::__construct( $datetime , $timezone ) ;
    }

    public function __toString()
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        switch( $this->displayFormat )
        {
            case 'mysql' :
            default :
                return $this->format( 'Y-m-d H:i:s' ) ;
                break;
        }
    }

    public function setDisplayFormat ( string $displayFormat ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        if ( in_array( $displayFormat , self::$displayFormats ) )
        {   $this->displayFormat = $displayFormat ; } 
        else
        {}
    }

    public function getDisplayFormat () : string { return $this->displayFormat ; }

}

?>
