<?php namespace stader\model ;

/*

create table users
(
    user_id     int auto_increment primary key , <- denne bliver genereret af DB
    navn_for    varchar(255) not null ,          <- de resterende felter er krævede
    navn_mellem varchar(255) default '' ,
    navn_efter  varchar(255) not null ,
    adresse1    varchar(255) not null ,
    adresse2    varchar(255) default '' ,
    postnr      varchar(8)   not null ,
    alias       varchar(255) not null ,
        constraint unique (alias) ,
    passwd      varchar(255) not null ,
    email       varchar(255) not null ,
        constraint unique (email) ,
    comments    varchar(8190)
) engine = memory ;

 */

require_once( __dir__ . '/class.usersdao.php' ) ;

class Users extends UsersDao
{
    private $allowedKeys = [ 'name' , 'surname' , 'phone' , 'username' , 'passwd' , 'email' ] ;

    function __construct ( ...$args )
    {   // echo 'class User extends UserDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct() ;

        /*
         *  gettype( $args[0] ) === 'null' 
         *      hent alle users
         *      $testUser = new Users() ;
         *  gettype( $args[0] ) === 'array' | 'string'
         *      hent alle users på basis af værdierne i $args[0] , $args[1] 
         *      $testUser = new Users($args[0] , $args[1] )
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
