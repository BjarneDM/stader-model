<?php namespace stader\model ;

require_once( __dir__ . '/class.rolesdao.php' ) ;

class Roles extends RolesDao
{
    private $allowedKeys = [ 'role' , 'note' ] ;

    function __construct ( ...$args )
    {   // echo 'class Role extends RoleDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct() ;

        /*
         *  gettype( $args[0] ) === 'null' 
         *      hent alle roles
         *      $testRole = new Roles() ;
         *  gettype( $args[0] ) === 'array' | 'string'
         *      hent alle roles på basis af værdierne i $args[0] , $args[1] 
         *      $testRole = new Role( $args[0] , $args[1] )
         */
        if ( ! isset( $args[0] ) ) { $args = [] ; $args[0] = null ; }
        switch ( strtolower( gettype( $args[0] ) ) )
        {
            case 'null' :
                $this->readAll() ;
                break ;
            case 'string' :
                if ( strtolower( gettype( $args[1] ) ) !== 'string' )
                    throw new \Exception( gettype( $args[0] ) . ' & ' . gettype( $args[1] ) . ' er ikke begge "string"' ) ;
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
