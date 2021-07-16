<?php namespace stader\model ;

class Areas extends AreasDao
{
    public static $allowedKeys = [ 'name' => 'varchar' , 'description' => 'text' ] ;
    public static $class       = '\\stader\\model\\Area' ;
    protected     $values      = [] ;

    public function __construct ( ...$args )
    {   // echo 'class Area extends AreaDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' ) ;

        /*
         *  gettype( $args[0] ) === 'null' 
         *      hent alle areas
         *      $testArea = new Areas() ;
         *  gettype( $args[0] ) === 'array' | 'string'
         *      hent alle areas på basis af værdierne i $args[0] , $args[1] 
         *      $testArea = new Area( $args[0] , $args[1] )
         */
        if ( ! isset( $args[0] ) ) { $args = [] ; $args[0] = null ; }
        $this->callArgs = $args ;
        switch ( strtolower( gettype( $args[0] ) ) )
        {
            case 'null' :
                $this->readAll( $this ) ;
                break ;
            case 'string' :
                if ( strtolower( gettype( $args[1] ) ) !== 'string' )
                    throw new \Exception( gettype( $args[0] ) . ' & ' . gettype( $args[1] ) . ' er ikke begge "string"' ) ;
                 if ( ! array_key_exists( $args[0] , self::$allowedKeys ) )
                    throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , array_keys( self::$allowedKeys ) ) . " ]" ) ;
                $this->$values[$args[0]] = $args[1] ;
                $this->readAll( $this ) ;
                break ;
            case 'array' :
                if ( count( $args[0] ) !== count( $args[1] ) )
                    throw new \Exception( 'count() for $args[0] & $args[1] er forskellige' ) ;
                foreach ( $args[0] as $key )
                {
                    if ( ! array_key_exists( $key , self::$allowedKeys ) )
                        throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , array_keys( self::$allowedKeys ) ) . " ]" ) ;
                }
                $this->values = $args[0] ;
                $this->readAll( $this ) ;
                break ;
            default :
                throw new \Exception( gettype( $args[0] ) . " : forkert input type [null,string,array]" ) ;
                break ;
        }
    }

}

?>
