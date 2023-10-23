<?php namespace Info\Mathiesen\DateTime ;

/*
 *  programmer / createExel har brug for en __toString() t/ \DateTime
 *  men en sådan eksisterer ikke som standard,
 *  så derfor denne class
 */

class DateTimeString 
    extends \DateTime
    implements \Stringable
{

    private string $displayFormat  = 'mysql' ;
    public  static $displayFormats =
    [
        'mysql' => 'Y-m-d H:i:s' ,
    ] ;

    public function __construct( string $datetime = "now", ?\DateTimeZone $timezone = null)
    {   //  echo __CLASS__ . " : " . __function__ . \PHP_EOL ;

        parent::__construct( $datetime , $timezone ) ;
    }

    public function __toString()
    {   //  echo __CLASS__ . " : " . __function__ . \PHP_EOL ;

    return $this->format( self::$displayFormats[$this->displayFormat]  ) ; }

    public function setDisplayFormat ( string $displayFormat ) : bool
    {   //  echo __CLASS__ . " : " . __function__ . \PHP_EOL ;

        if   ( in_array( $displayFormat  , array_keys( self::$displayFormats ) ) )
             {   $this->displayFormat = $displayFormat ; } 
        else { return false ; }
    return true ; }

    public function getDisplayFormat () : string { return $this->displayFormat ; }

    public function addDisplayFormat ( array $formats ): void
    {   //  echo __CLASS__ . " : " . __function__ . \PHP_EOL ;

        foreach ( $formats as $format => $display )
        {
            self::$displayFormats[$format] = $display ;
        } unset( $format , $display ) ;

    }

}

?>
