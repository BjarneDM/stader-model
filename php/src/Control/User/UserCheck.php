<?php namespace Stader\Model\Tables\User ;

class UserLogin extends User
{
    public static $allowedKeys = 
        [ 'username' => 'varchar' , 
          'passwd'   => 'varchar' 
        ] ;

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

    /*
     *  default minimalt integritets check
     */
    protected function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! array_key_exists( $key , self::$allowedKeys ) )
                throw new \Exception( "'{$key}' doesn't exist in [" . implode( ',' , array_keys( $this->keysAllowed ) ) . "]" ) ;
        }
    }

    private function nullThis ()
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        self::$allowedKeys = null ;
        $this->values = null ;
    }
}

?>
