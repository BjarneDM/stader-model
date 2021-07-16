<?php namespace stader\model ;

require_once( __dir__ . '/class.placesdao.php' ) ;

class Places extends PlacesDao
{
    private $allowedKeys = [ 'place_nr' , 'description' , 'place_owner_id' , 'area_id' ] ;

    function __construct ( ...$args )
    {   // echo 'class User extends UserDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct() ;

        /*
         *  gettype( $args[0] ) === 'null' 
         *      hent alle places
         *      $testUser = new Places() ;
         *  gettype( $args[0] ) === 'array' | 'string'
         *      hent alle places på basis af værdierne i $args[0] , $args[1] 
         *      $testUser = new Places($args[0] , $args[1] )
         */
        if ( ! isset( $args[0] ) ) { $args = [] ; $args[0] = null ; }
        switch ( strtolower( gettype( $args[0] ) ) )
        {
            case 'null' :
                $this->readAll() ;
                break ;
            case 'string' :
                if ( ! in_array( strtolower( gettype( $args[1] ) ) , [ 'integer' , 'string' , 'bool' ] ) )
                    throw new \Exception( gettype( $args[0] ) . ' & ' . gettype( $args[1] ) . ' er ikke i ["integer","string","bool"]' ) ;
                if ( ! in_array( $args[0] , $this->allowedKeys ) )
                    throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , $this->allowedKeys ) . " ]" ) ;
                $this->readAll( $args[0] , $args[1] ) ;
                break ;
            case 'array' :
                if ( count( $args[0] ) !== count( $args[1] ) )
                    throw new \Exception( 'count() for $args[0] & $args[1] er forskellige' ) ;
                foreach ( $args[0] as $key )
                {
                    if ( ! in_array( $key , $this->allowedKeys ) )
                        throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , $this->allowedKeys ) . " ]" ) ;
                }
                $this->readAll( $args[0] , $args[1] ) ;
                break ;
            default :
                throw new \Exception( gettype( $args[0] ) . " : forkert input type [null,string,array]" ) ;
                break ;
        }
    }

}

?>
