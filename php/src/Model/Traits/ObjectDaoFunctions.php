<?php namespace Stader\Model\Traits ;

trait ObjectDaoFunctions
{
    protected function setValuesDefault ( &$args ) : void {}
    protected function fixValuesType () : void {}

    protected function create( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $array ) ;

    return self::$functions->create( $object ) ; }

    protected function read( $object ) : Array
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $this->notify( 'read' ) ;
    return self::$functions->readOne( $object ) ; }

    protected function readNULL( $object ) : Array
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $this->notify( 'read' ) ;
//         $values = self::$functions->readNULL( $object ) ;
//         print_r(  $values ) ;
//     return $values ; }
    return self::$functions->readNULL( $object ) ; }

    protected function update( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ; // exit ;

        $rowCount = self::$functions->update( 
            $object , 
            array_diff( $this->values , $this->valuesOld ) ) ;
        $this->notify( 'update' ) ;
    return $rowCount ; }

    protected function deleteThis( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        $rowCount = self::$functions->delete( $object ) ;
        $this->notify( 'delete' ) ;
        unset( $this->values , $this->valuesOld ) ;
    return $rowCount ; }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

    public function delete() : int
    {
    return $this->deleteThis( $this ) ; }

    public function setValues( $values ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $values ) ;

        switch ( strtolower( gettype( $values ) ) )
        {
            case 'array' :
                $this->check( $values ) ;
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
    protected function check( Array &$toCheck ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! array_key_exists( $key , $this->keysAllowed ) )
                throw new \Exception( "'{$key}' doesn't exist in [" . implode( ',' , array_keys( $this->keysAllowed ) ) . "]" ) ;

            switch ( $this->keysAllowed[$key] )
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
