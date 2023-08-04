<?php namespace Stader\Model\Traits ;

trait ObjectDaoFunctions
{
    protected function setValuesDefault ( &$args ) : void {}
    protected function fixValuesType () : void {}

    protected function create( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;
        $method = self::$iniSettings->getSetting( $this->theDBtype, 'method') ;
    return self::$functions[ $method ]->create( $object ) ; }

    protected function read( $object ) : Array
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $method = self::$iniSettings->getSetting( $this->theDBtype, 'method') ;
        $this->notify( 'read' ) ;
    return self::$functions[ $method ]->readOne( $object ) ; }

    protected function readNULL( $object ) : Array
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $method = self::$iniSettings->getSetting( $this->theDBtype, 'method') ;
        $this->notify( 'read' ) ;
//         $values = self::$functions[ $method ]->readNULL( $object ) ;
//         print_r(  $values ) ;
//     return $values ; }
    return self::$functions[ $method ]->readNULL( $object ) ; }

    protected function update( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ; // exit ;

        $method = self::$iniSettings->getSetting( $this->theDBtype, 'method') ;
        $rowCount = self::$functions[ $method ]->update( $object ) ;
        $this->notify( 'update' ) ;
    return $rowCount ; }

    protected function deleteThis( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        $method = self::$iniSettings->getSetting( $this->theDBtype, 'method') ;
        $rowCount = self::$functions[ $method ]->delete( $object ) ;
        $this->notify( 'delete' ) ;
        $this->values    = [] ;
        $this->valuesOld = [] ;
    return $rowCount ; }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

    public function getDiff()   { return array_diff( $this->values , $this->valuesOld ) ; }

    public function delete() : int
    {
    return $this->deleteThis( $this ) ; }

    public function setValues( $values ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $values ) ;

        switch ( strtolower( gettype( $values ) ) )
        {
            case 'array' :
                $this->check( $this , $values ) ;
                foreach ( $values as $key => $value )
                {
                    $this->valuesOld[ $key ] = $this->values[ $key ] ;
                    $this->values[ $key ] = $value ;
                }
                $this->update( $this ) ;
                break ;
            default :
                throw new \Exception( gettype( $values ) . " : forkert input type [array]" ) ;
                break ;
        }
    }

    /*
     *  default minimalt integritets check
     */
    protected function check( $class , Array &$toCheck ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! array_key_exists( $key , $class::$allowedKeys ) )
                throw new \Exception( "'{$key}' doesn't exist in [" . implode( ',' , array_keys( $class::$allowedKeys ) ) . "]" ) ;

            switch ( $class::$allowedKeys[$key] )
            {
                case 'int' :
                case 'integer' :
                    break ;
                case 'bool' :
                    break ;
            }

        }
    }

    /*
     *  denne skal udfyldes i de aktuelle class
     *  der har brug for dette
     */
    protected function notify ( string $action ) : void {}

}

?>
