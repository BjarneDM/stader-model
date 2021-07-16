<?php namespace stader\model ;

/*

 */

require_once( __dir__ . '/class.user.php' ) ;

class UserLogin extends User
{
    private $allowedKeys = [ 'username' , 'passwd' ] ;

    function __construct ( ...$args )
    {   // echo 'class UserLogin extends User __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        $this->check( $args[0] ) ;

        try 
        {
//             if ( $args[0]['username'] == 'dummy' ) 
//                 throw new \Exception('dummy user kan ikke logge ind') ;

            parent::__construct( 'username' , $args[0]['username'] ) ;
            if ( ! $this->pwdVerify( $args[0]['passwd'] ) )
                throw new \Exception('passsword passer ikke') ;

        }
        catch ( \Exception $e ) { $this->nullThis() ; }

    }

    private function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! in_array( $key , self::$allowedKeys ) )
                throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , self::$allowedKeys ) . " ]" ) ;
        }
    }

    private function nullThis ()
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        self::$allowedKeys = null ;
        $this->values = null ;
    }
}

?>
