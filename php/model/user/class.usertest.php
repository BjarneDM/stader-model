<?php namespace stader\model ;

/*

create table users
(
    user_id     int auto_increment primary key , <- denne bliver genereret af DB
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

require_once( __dir__ . '/class.userdao.php' ) ;

class UserTest extends UserDao
{
    private $allowedKeys = [ 'name' , 'surname' , 'phone' , 'username' , 'passwd' , 'email' ] ;

    function __construct ( $connect , $args1 = null , $args2 = null )
    {   // echo 'class User extends UserDao __construct' . \PHP_EOL ;
        // print_r( $connect ) ;
        // echo 'connection type : ' . $connect->getType()  . \PHP_EOL ;

        if ( is_null( $args1 ) ) throw new \Exception( " : forkert antal parametre [2,3]" ) ;

        parent::__construct( $connect ) ;

        /*
         *  gettype( $args1 ) === 'integer' 
         *      opret en User på basis af et user_id
         *      $testUser = new User( user_id ) ;
         *  gettype( $args1 ) === 'array'
         *      opret en user på basis af værdierne i $args1
         *      $testUser = new User( $newUser )
         */
        switch ( strtolower( gettype( $args1 ) ) )
        {
            case 'integer' :
                $this->read( $args1 ) ;
                break ;
            case 'string' :
                $this->read( $args1 , $args2 ) ;
                break ;
            case 'array' :
                switch ( is_null( $args2 ) )
                {
                    case true :
                        /*
                         *  count( $args1 ) === 6 : ny user, der skal oprettes
                         */
                        switch ( count( $args1 ) )
                        {
                            case 6 :
                                $this->check( $args1 ) ;

                            foreach ( $args1 as $key => $value ) 
                            { 
                                $this->values[$key] = $value ;
                            }   unset( $key , $value ) ;

                            if ( in_array( password_get_info( $this->values['passwd'] )['algo'] , [ 0 , null ] , true ) )
                            {
                                // If so, create a new hash, and replace the plain one
                                $options = [] ;
                                $options['bcrypt'] = [ 'salt' => 'aaaaaaaaaaaaaaaaaaaaaa' , 'cost' => 10 ] ;
                                $options['argon2i'] = [] ;
                                $pwd_peppered = hash_hmac( 'sha256', $this->values['passwd'] , 'c1isvFdxMDdmjOlvxpecFw' ) ;
                                $pwd_peppered = $this->values['passwd'] ;
                                $this->values['passwd'] = password_hash( $pwd_peppered , PASSWORD_BCRYPT , $options['bcrypt'] ) ;
                                // print_r( [ strlen( $this->values['passwd'] ) , $this->values['username'] , $pwd_peppered , $this->values['passwd'] ] ) ;
                            }

                                $this->values['user_id'] = $this->create( $this->values ) ;
                                break ;
                            default :
                                throw new \Exception( count( $args1 ) . " : forkert antal parametre [6]" ) ;
                                break ;
                        }

 
                        break ;
                    case false :
                        $this->read( $args1 , $args2 ) ;
                        break ;
                    default :
                        throw new \Exception( " : forkert antal parametre [2,3]" ) ;
                        break ;
                }
                break ;
            default :
                throw new \Exception( gettype( $args1 ) . " : forkert input type [integer,string,array]" ) ;
                break ;
        }
    }

    private function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! in_array( $key , $this->allowedKeys ) )
                throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , $this->allowedKeys ) . " ]" ) ;

            switch ( $key )
            {
                case 'x_passwd_x' :
                    // un-hashed password
                    if ( in_array( password_get_info( $toCheck['passwd'] )['algo'] , [ 0 , null ] , true ) )
                    {
                        // If so, create a new hash, and replace the plain one
                        $options = [] ;
                        $options['bcrypt'] = [ 'salt' => 'aaaaaaaaaaaaaaaaaaaaaa' , 'cost' => 10 ] ;
                        $options['argon2i'] = [] ;
                        $pwd_peppered = hash_hmac( 'sha256', $toCheck['passwd'] , 'c1isvFdxMDdmjOlvxpecFw' ) ;
                        $pwd_peppered = $toCheck['passwd'] ;
                        $toCheck['passwd'] = password_hash( $pwd_peppered , PASSWORD_BCRYPT , $options['bcrypt'] ) ;
                        // print_r( [ strlen( $toCheck['passwd'] ) , $toCheck['username'] , $pwd_peppered , $toCheck['passwd'] ] ) ;
                    }
                    break ;
            }
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
                    $this->values[ $key ] = $value ;
                    $this->update( $key , $value ) ;
                }
                break ;
            default :
                throw new \Exception( gettype( $values ) . " : forkert input type [array]" ) ;
                break ;
        }
    }

    public function pwdVerify( string $pwd )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $pwd ] ) ;

        $pwd_peppered = hash_hmac( 'sha256', $pwd , 'c1isvFdxMDdmjOlvxpecFw' ) ;
        $pwd_peppered = $pwd ;
        $options = [] ;
        $options['bcrypt'] = [ 'salt' => 'aaaaaaaaaaaaaaaaaaaaaa' , 'cost' => 10 ] ;
        $options['argon2i'] = [] ;
        // print_r( 
            [
                strlen( $this->values['passwd'] ) ,
                password_get_info( $this->values['passwd'] ) ,
                $pwd_peppered ,
                preg_split( '/(\$)/' , $this->values['passwd'] ) ,
                preg_split( '/(\$)/' , password_hash( $pwd_peppered , PASSWORD_BCRYPT , $options['bcrypt'] ) )
            ] ) ;

    return password_verify( $pwd_peppered , $this->values['passwd'] ) ; }

}

?>
