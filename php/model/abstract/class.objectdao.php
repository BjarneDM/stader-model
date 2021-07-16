<?php namespace stader\model ;

abstract class ObjectDao extends Setup
{
    private       $keysAllowed = []   ;
    private       $functions   = null ;
    protected     $values      = []   ;
    protected     $valuesOld   = []   ;
    protected     $class       = ''   ;
    
    function __construct ( string $dbType , Array $allowedKeys , $args )
    {   // echo 'abstract class ObjectDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct( $dbType ) ;
        $this->keysAllowed = $allowedKeys ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "pgsql"    : $this->functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "sqlite"   : $this->functions = new TableDaoPdo( self::$connect , $this->class ) ; break ;
            case "xml"      : $this->functions = new TableDaoXml( self::$connect , $this->class ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        }

        $this->setupData( $args ) ;

    }

    protected function setupData ( $args )
    {
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
                        $this->read( $this ) ;
                        $this->valuesOld = ( new \ArrayObject( $this->values ) )->getArrayCopy() ;
                       break ;
                    case 'array' :
                        /*
                         *  count( $args[0] ) === count( $this->allowedKeys ) : nyt Object, der skal oprettes
                         */
                        switch ( count( $args[0] ) )
                        {
                            case count( $this->keysAllowed ) :
                                $this->check( $args[0] ) ;
                                $this->values       = ( new \ArrayObject( $args[0] ) )->getArrayCopy() ;
                                $this->values['id'] = $this->create( $this ) ;
                                $this->valuesOld    = ( new \ArrayObject( $this->values ) )->getArrayCopy() ;
                                break ;
                            default :
                                throw new \Exception( count( $args[0] ) . " : forkert antal parametre [" . count( $this->keysAllowed ) . "]" ) ;
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

    protected function create( $object ) 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $array ) ;

            return $this->functions->create( $object ) ; }

    protected function read( $object )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

            $this->values = $this->functions->readOne( $object ) ; }

    protected function update( $object ) 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $key , $value ] ) ;

            $rowCount = $this->functions->update( $object , array_diff( $this->values , $this->valuesOld ) ) ;
        return $rowCount ; }

    public function deleteThis( $object )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

            $rowCount = $this->functions->delete( $object ) ;
            $this->values = null ;
        return $rowCount ; }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

    public function delete()
    {
        $this->deleteThis( $this ) ;
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

    protected function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! array_key_exists( $key , $this->keysAllowed ) )
                throw new \Exception( "'{$key}' doesn't exist in [" . implode( ',' , array_keys( $this->keysAllowed ) ) . "]" ) ;
        }
    }


}

?>
