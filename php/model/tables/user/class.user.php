<?php namespace stader\model ;

/*

create table users
(
    id          int auto_increment primary key , <- denne bliver genereret af DB
    name        varchar(255) not null ,          <- de resterende felter er krævede
    surname     varchar(255) default '' ,
    phone       varchar(255) not null ,
    username    varchar(255) not null ,
        constraint unique (username) ,
    passwd      varchar(255) not null ,
    email       varchar(255) not null ,
        constraint unique (email) ,
) engine = memory ;

 */

class User extends ObjectDao
{
    public static $allowedKeys = 
        [ 'name'     => 'varchar' , 
          'surname'  => 'varchar' , 
          'phone'    => 'varchar' , 
          'username' => 'varchar' , 
          'passwd'   => 'varchar' , 
          'email'    => 'varchar' 
        ] ;
    protected     $class       = '\\stader\\model\\User' ;

    function __construct ( ...$args )
    {   // echo 'class User extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys , $args ) ;

        $this->setupData( $args ) ;

    }

    private function pwdHash( string $password )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $password ] ) ;

        $options = [] ;
        $options['bcrypt'] = [ 'cost' => self::$iniSettings['crypt']['cost'] ] ;
        return 
            password_hash( 
                $password , 
                self::$iniSettings['crypt']['algoConst'] ,  
                $options['bcrypt'] 
            ) ;
    }

    protected function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        parent::check( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            switch ( $key )
            {
                case 'passwd' :
                    $options = [] ;
                    // un-hashed password
                    if ( in_array( password_get_info( $toCheck['passwd'] )['algo'] , [ 0 , null ] , true ) )
                    {
                        // If so, create a new hash, and replace the plain one
                        $toCheck[$key] = $this->pwdHash( $toCheck[$key] ) ;
                    }
                    break ;
            }
        }
    }

    public function pwdVerify( string $pwd )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $pwd ] ) ;

        $result = password_verify( $pwd , $this->values['passwd'] ) ;
        if ( $result === true )
        {
            $pwdInfo = password_get_info( $this->values['passwd'] ) ;
            if (
                    $pwdInfo['algoName'] !== self::$iniSettings['crypt']['algoName']
                ||  (int) $pwdInfo['options']['cost'] !== (int) self::$iniSettings['crypt']['cost']
            )   $this->setValues( [ 'passwd' => $pwd ] ) ;
        }
    return $result ; }

}

?>
