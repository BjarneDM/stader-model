<?php namespace Stader\Model\Traits ;

use \Stader\Model\OurDateTime ;

trait LogFunctions
{
    function setValuesDefault ( &$args ) : void
    {
        $this->referenceID = array_keys( self::$allowedKeys )[0] ;
        $this->descriptID  = array_keys( self::$allowedKeys )[1] ;
    }

    function fixValuesType () : void
    {
        $this->values['id'] = (int) $this->values['id'] ;
        $this->values["{$this->referenceID}"] = (int) $this->values["{$this->referenceID}"] ;
        $this->values['log_timestamp']  = 
            @is_null( $this->values['log_timestamp'] ) 
            ? new OurDateTime()
            : OurDateTime::createFromFormat( 'Y-m-d H:i:s' , $this->values['creationtime'] ) ;
    }

    protected function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! array_key_exists( $key , self::$allowedKeys ) )
                throw new \Exception( "'{$key}' doesn't exist in [" . implode( ',' , array_keys( self::$allowedKeys ) ) . "]" ) ;

            switch ( $key )
            {
                case 'old_value' :
                case 'new_value' :
                    if ( ! in_array( strtolower( gettype( $toCheck[ $key ] ) ) , [ 'null' , 'string' ] ) )
                        throw new \Exception( gettype( $toCheck[ $key ] ) . " : forkert input type for {$key} [string]" ) ;
                    break ;
                case "{$this->descriptID}" :
                    if ( strtolower( gettype( $toCheck[ $key ] ) ) !== 'string' )
                        throw new \Exception( gettype( $toCheck[ $key ] ) . " : forkert input type for {$key} [string]" ) ;
                    break ;
                case "{$this->referenceID}" :
                    if ( strtolower( gettype( $toCheck[ $key ] ) ) !== 'integer' )
                        throw new \Exception( gettype( $toCheck[ $key ] ) . " : forkert input type for {$key} [integer]" ) ;
                    break ;
            }
        }
    }

}

?>
