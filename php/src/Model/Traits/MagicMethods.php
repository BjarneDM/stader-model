<?php namespace Stader\Model\Traits ;

trait MagicMethods
{
    final public function __get($key)
    {
        throw new \Exception( "reading the value from a non-existing or inaccessible property" ) ;
    }

    final public function __set($key, $value)
    {
        throw new \Exception( "writing a value to a non-existing or inaccessible property" ) ;
    }

    public function __toString()
    {
        $outString = print_r( $this->values, true ) ;
        $outString = preg_replace( ['/^Array\n/','/Array/','/.*[(,)]\n/'], [''], $outString ) ;
        $outString = preg_replace( ['/\n\n/'], ["\n"], $outString ) ;
    return $outString ; }

}

?>
