<?php namespace Stader\Control\Abstract ;

use \Stader\Model\Traits\MagicMethods ;


abstract class DataObjectDao
{
    protected   $values      = []   ;
    protected   $valuesOld   = []   ;
    
    function __construct ()
    {   // echo 'abstract class ObjectDao extends Setup __construct' . \PHP_EOL ;

    }

    protected function setupData ( $thisClass , $args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;
        /*
         *  gettype( $args[0] ) === 'integer' 
         *      hent et Object på basis af et id
         *      $testObject = new Object( id ) ;
         *  gettype( $args[0] ) === 'array'
         *      opret en Object på basis af værdierne i $args[0]
         *      $testObject = new Object( $args[0] )
         *  gettype( $args[0] ) === ['string','array'] , gettype( $args[1] ) === ['string','array']
         *      hent et Object på basis af værdierne i $args[0],$args[1]
         *      $testObject = new Object( $keys , $values )
         */
        switch ( count( $args ) )
        {
            case 1 :
                switch ( strtolower( gettype( $args[0] ) ) )
                {
                    case 'integer' :
                        $this->values['id'] = $args[0] ;
                        $this->values       = $this->read() ;
                       break ;
                    case 'array' :
                        switch ( count( $args[0] ) )
                        {
                            /*
                             *  count( $args[0] ) === count( $this->keysAllowed ) : nyt Object, der skal oprettes
                             */
                            case count( $thisClass::$allowedKeys ) :
                                $this->check( $thisClass , $args[0] ) ;
                                $this->values       = ( new \ArrayObject( $args[0] ) )->getArrayCopy() ;
                                $this->values['id'] = $this->create() ;
                                $this->values       = $this->read() ;
                                $this->notify( 'create' ) ;
                                break ;
                            default :
                                throw new \Exception( count( $args[0] ) . " : forkert antal parametre [" . count( $thisClass::$allowedKeys ) . "]" ) ;
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
                        $args[0] = [ $args[0] ] ;
                        $args[1] = [ $args[1] ] ;
                    case 'array' :
                        if ( count( $args[0] ) !== count( $args[1] ) )
                            throw new \Exception( 'count() for $args[0] & $args[1] er forskellige' ) ;
                        $args[0] = array_combine( $args[0] , $args[1] ) ;
                        $this->check( $thisClass , $args[0] ) ;
                        $this->values = ( new \ArrayObject( $args[0] ) )->getArrayCopy() ;
                        break ;
                    default :
                        throw new \Exception( gettype( $args[0] ) . " : forkert input type [string,array]" ) ;
                        break ;
                }
                $this->values = $this->read() ;
                break ;
            default :
                throw new \Exception( count( $args ) . " : forkert antal parametre [1,2]" ) ;
                break ;
        }
        $this->valuesOld = ( new \ArrayObject( $this->values ) )->getArrayCopy() ;
}

    abstract protected function create ()                 : int   ;
    abstract protected function read   ()                 : Array  ;
    abstract protected function update ( Array  $values ) : void  ;
    public             function delete () 
    {
        $this->values    = [] ;
        $this->valuesOld = [] ;
    }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }
 
    public function setValues( Array $values )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // echo $this->class. \PHP_EOL ;
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
                $this->update( $values ) ;
                break ;
            default :
                throw new \Exception( gettype( $values ) . " : forkert input type [array]" ) ;
                break ;
        }
        $this->values = $this->read() ;
    }

    /*
     *  default minimalt integritets check
     */
    protected function check( $class , Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! array_key_exists( $key , $class::$allowedKeys ) )
                unset( $toCheck[ $key ] ) ;
                // throw new \Exception( "'{$key}' doesn't exist in [" . implode( ',' , array_keys( $thisClass::$allowedKeys ) ) . "]" ) ;

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

    use MagicMethods ;
}

?>
