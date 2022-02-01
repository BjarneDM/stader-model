<?php namespace Stader\Model ;

class OurDateTime extends \DateTime
{
    public function __construct( string $datetime = "now", ?\DateTimeZone $timezone = null)
    {
        parent::__construct( $datetime , $timezone ) ;
    }

    public function __toString()
    {
	    return $this->format( 'Y-m-d H:i:s' ) ;
    }
}

?>
