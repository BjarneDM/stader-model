<?php namespace stader\model ;

/*

create table if not exists areas
(
    area_id     int auto_increment primary key ,
    name        varchar(255) not null ,
        constraint unique (name) ,
    description text
) ;

 */

class Area extends AreaDao
{
    public static $allowedKeys = [ 'name' => 'varchar' , 'description' => 'text' ] ;
    public static $class       = '\\stader\\model\\Area' ;
    private       $callArgs    = null ;

    public function __construct ( ...$args )
    {   // echo 'class Area extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' ) ;

        /*
         *  gettype( $args[0] ) === 'integer' 
         *      hent et Area på basis af et id
         *      $testArea = new Area( id ) ;
         *  gettype( $args[0] ) === 'array'
         *      opret en area på basis af værdierne i $args[0]
         *      $testArea = new Area( $newArea )
         *  gettype( $args[0] ) === ['string','array'] , gettype( $args[1] ) === ['string','array']
         *      hent en area på basis af værdierne i $args[0],$args[1]
         *      $testArea = new Area( $keys , $values )
         */
        $this->callArgs = $args ;
        switch ( count( $args ) )
        {
            case 1 :
                switch ( strtolower( gettype( $args[0] ) ) )
                {
                    case 'integer' :
                        $this->values['id'] = $args[0] ;
                        $this->read( $this ) ;
                        $this->valuesOld = ( new \ArrayObject( $this->values ) )->getArrayCopy() ;
                       break ;
                    case 'array' :
                        /*
                         *  count( $args[0] ) === 2 : ny area, der skal oprettes
                         */
                        switch ( count( $args[0] ) )
                        {
                            case 2 :
                                $this->check( $args[0] ) ;
                                $this->values       = ( new \ArrayObject( $args[0] ) )->getArrayCopy() ;
                                $this->values['id'] = $this->create( $this ) ;
                                $this->valuesOld    = ( new \ArrayObject( $this->values ) )->getArrayCopy() ;
                                break ;
                            default :
                                throw new \Exception( count( $args[0] ) . " : forkert antal parametre [2]" ) ;
                                break ;
                        }
                        break ;
                    default :
                        throw new \Exception( gettype( $args[0] ) . " : forkert input type [integer,array]" ) ;
                        break ;
                }
                break ;
            case 2 :
                switch ( strtolower( gettype( $args[0] ) ) )
                {
                    case 'string' :
                        if ( strtolower( gettype( $args[1] ) ) !== 'string' )
                            throw new \Exception( gettype( $args[0] ) . ' & ' . gettype( $args[1] ) . ' er ikke begge "string"' ) ;
                        if ( ! in_array( $args[0] , self::$allowedKeys ) )
                            throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , self::$allowedKeys ) . " ]" ) ;
                        break ;
                    case 'array' :
                        if ( count( $args[0] ) !== count( $args[1] ) )
                            throw new \Exception( 'count() for $args[0] & $args[1] er forskellige' ) ;
                        foreach ( $args[0] as $key )
                        {
                            if ( ! in_array( $key , self::$allowedKeys ) )
                                throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , self::$allowedKeys ) . " ]" ) ;
                        }
                        break ;
                    default :
                        throw new \Exception( gettype( $args[0] ) . " : forkert input type [string,array]" ) ;
                        break ;
                }
                $this->read( $this ) ;
                break ;
            default :
                throw new \Exception( count( $args ) . " : forkert antal parametre [1,2]" ) ;
                break ;
        }
    }

    private function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! array_key_exists( $key , self::$allowedKeys ) )
                throw new \Exception( "'{$key}' doesn't exist in [" . implode( ',' , array_keys( self::$allowedKeys ) ) . "]" ) ;
        }
    }

    public function setValues( $values )
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

    public function delete()
    {
        $this->deleteThis( $this ) ;
    }

}

?>
